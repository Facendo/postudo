<?php

namespace App\Http\Controllers;

use App\Models\Asunto;
use App\Models\Pagos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $pagos = Pagos::where('cedula', $user->cedula)->get();
        return view('estudiante.pago', compact('pagos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $asuntos= Asunto::all();
        $user=Auth::user();
        return view('estudiante.registropago',compact('user','asuntos'));
    }

    public function controlpagos()
    {
        $pagos = Pagos::with('estudiante')->get();
        return view('asuntos.controldepagos',compact('pagos'));
    }

    public function pagosActualizados()
    {
        $pagos = Pagos::with('estudiante')->where('estado', 'Actualizado')->get();
        return view('asuntos.pagos_actualizados', compact('pagos'));
    }

    public function pagosPendientes()
    {
        $pagos = Pagos::with('estudiante')->where('estado', 'Pendiente')->get();
        return view('asuntos.pagos_pendientes', compact('pagos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user=Auth::user();

        // Capturar la tasa de cambio de la fecha del pago (NO la de hoy)
        $tasaAplicable = 0;
        $fechaPago = \Carbon\Carbon::parse($request->fecha_pago)->format('Y-m-d');
        
        if (Storage::exists('tasa_cambio_historial.json')) {
            $history = json_decode(Storage::get('tasa_cambio_historial.json'), true) ?? [];
            // Ordenamos las fechas de mayor a menor para encontrar la más cercana hacia atrás
            krsort($history);
            foreach ($history as $date => $rate) {
                // Buscamos la tasa de ese mismo día o la última tasa registrada antes de ese día
                if ($date <= $fechaPago) {
                    $tasaAplicable = $rate;
                    break;
                }
            }
        }

        // Fallback a la tasa actual si el historial está vacío (por ser la primera vez)
        if ($tasaAplicable == 0 && Storage::exists('tasa_cambio.json')) {
            $tasaJson = json_decode(Storage::get('tasa_cambio.json'), true);
            $tasaAplicable = $tasaJson['valor'] ?? 0;
        }

        $pago= new Pagos();
        $pago->nombre=$user->name;
        $pago->cedula=$user->cedula;
        $pago->banco_emisor=$request->banco_emisor;
        $pago->banco_receptor=$request->banco_receptor;
        $pago->monto=$request->monto;
        $pago->tasa_momento=$tasaAplicable;
        $pago->asunto=$request->asunto;
        $pago->fecha_pago=$request->fecha_pago;
        $pago->referencia=$request->referencia;
        $pago->estado="Pendiente";
        
        $pago->save();
        return redirect()->route('pago.index')->with('success', 'Pago registrado exitosamente');
    }

    /**
     * Verificar pagos pendientes contra el Google Sheet publicado.
     */
    public function verificarPagos()
    {
        // Leer la URL del Google Sheet desde la configuración
        $sheetConfig = null;
        if (Storage::exists('google_sheet_config.json')) {
            $sheetConfig = json_decode(Storage::get('google_sheet_config.json'), true);
        }

        if (!$sheetConfig || empty($sheetConfig['url'])) {
            return redirect()->route('pago.pendientes')
                ->with('error', 'No se ha configurado la URL del Google Sheet. Configure la URL primero.');
        }

        // Leer el CSV desde el Google Sheet publicado
        try {
            $response = Http::timeout(15)->get($sheetConfig['url']);
            
            if (!$response->successful()) {
                return redirect()->route('pago.pendientes')
                    ->with('error', 'No se pudo conectar con el Google Sheet. Verifique la URL y que esté publicado.');
            }

            // Google Sheets publica con retardo a veces si el cache no se ha limpiado, 
            // pero normalmente se actualiza rápido.
            $csvContent = $response->body();
        } catch (\Exception $e) {
            return redirect()->route('pago.pendientes')
                ->with('error', 'Error al conectar con Google Sheets: ' . $e->getMessage());
        }

        // Parsear el CSV
        $sheetData = [];
        $lines = array_filter(explode("\n", $csvContent));
        
        if (count($lines) > 0) {
            // Determinar delimitador (en español a veces usa ';' en lugar de ',' porque ',' es para decimales)
            $delimiter = strpos($lines[0], ';') !== false ? ';' : ',';
            
            // Saltar el header (primera fila)
            $header = str_getcsv(array_shift($lines), $delimiter);
            
            foreach ($lines as $line) {
                $row = str_getcsv($line, $delimiter);
                if (count($row) >= 3) {
                    // Limpiar la referencia (quitar espacios y caracteres especiales)
                    $referencia = trim(preg_replace('/[^0-9a-zA-Z]/', '', $row[0]));
                    
                    // Asegurarnos de limpiar el monto correctamente ("8.700,00" -> "8700.00")
                    $montoStr = trim($row[1]);
                    // Eliminamos todos los puntos (asumiendo que son separadores de miles)
                    $montoStr = str_replace('.', '', $montoStr);
                    // Cambiamos la coma por punto (para decimales)
                    $montoStr = str_replace(',', '.', $montoStr);
                    $monto = floatval($montoStr);
                    
                    $fecha = trim($row[2]);

                    if (!empty($referencia)) {
                        // Quitar ceros a la izquierda para evitar problemas (ej: "000123" vs "123")
                        $referencia = ltrim($referencia, '0');
                        $sheetData[$referencia] = [
                            'monto' => $monto,
                            'fecha' => $fecha,
                        ];
                    }
                }
            }
        }

        // Obtener pagos pendientes con sus asuntos
        $pagosPendientes = Pagos::with('estudiante')
            ->where('estado', 'Pendiente')
            ->get();

        // Cruzar datos
        $resultados = [];
        foreach ($pagosPendientes as $pago) {
            $referenciaLimpia = trim(preg_replace('/[^0-9a-zA-Z]/', '', $pago->referencia));
            $referenciaLimpia = ltrim($referenciaLimpia, '0');
            
            // Buscar el asunto para calcular el monto esperado
            $asunto = Asunto::where('nombre', $pago->asunto)
                ->whereNull('cedula_estudiante')
                ->first();
            
            $montoEsperado = 0;
            if ($asunto && $pago->tasa_momento > 0) {
                $montoEsperado = round($asunto->valor_usd * $pago->tasa_momento, 2);
            }

            $resultado = [
                'pago' => $pago,
                'monto_esperado' => $montoEsperado,
                'referencia_encontrada' => false,
                'monto_coincide' => false,
                'fecha_coincide' => false,
                'estado_verificacion' => 'no_encontrado',
                'monto_sheet' => null,
                'fecha_sheet' => null,
            ];

            if (isset($sheetData[$referenciaLimpia])) {
                $datosSheet = $sheetData[$referenciaLimpia];
                $resultado['referencia_encontrada'] = true;
                $resultado['monto_sheet'] = $datosSheet['monto'];
                $resultado['fecha_sheet'] = $datosSheet['fecha'];

                // Verificar monto: comparar el monto del sheet con el monto esperado del asunto
                // Tolerancia de 0.50 Bs para diferencias de redondeo
                $tolerancia = 0.50;
                if ($montoEsperado > 0) {
                    $resultado['monto_coincide'] = abs($datosSheet['monto'] - $montoEsperado) <= $tolerancia;
                } else {
                    // Si no hay monto esperado, comparar con el monto registrado por el estudiante
                    $resultado['monto_coincide'] = abs($datosSheet['monto'] - $pago->monto) <= $tolerancia;
                }

                // Verificar fecha
                try {
                    $fechaSheetStr = trim($datosSheet['fecha']);
                    
                    // Si tiene barras, asumimos formato latino DD/MM/YYYY
                    if (strpos($fechaSheetStr, '/') !== false) {
                        try {
                            $fechaSheet = \Carbon\Carbon::createFromFormat('d/m/Y', $fechaSheetStr)->format('Y-m-d');
                        } catch (\Exception $e) {
                            // Si falla, intentamos reemplazando barras por guiones para que PHP asuma DD-MM-YYYY
                            $fechaSheet = \Carbon\Carbon::parse(str_replace('/', '-', $fechaSheetStr))->format('Y-m-d');
                        }
                    } else {
                        $fechaSheet = \Carbon\Carbon::parse($fechaSheetStr)->format('Y-m-d');
                    }

                    $fechaPago = \Carbon\Carbon::parse($pago->fecha_pago)->format('Y-m-d');
                    $resultado['fecha_coincide'] = ($fechaSheet === $fechaPago);
                } catch (\Exception $e) {
                    $resultado['fecha_coincide'] = false;
                }

                // Determinar estado final
                if ($resultado['monto_coincide'] && $resultado['fecha_coincide']) {
                    $resultado['estado_verificacion'] = 'verificado';
                } elseif (!$resultado['monto_coincide'] && !$resultado['fecha_coincide']) {
                    $resultado['estado_verificacion'] = 'monto_fecha_diferente';
                } elseif (!$resultado['monto_coincide']) {
                    $resultado['estado_verificacion'] = 'monto_diferente';
                } else {
                    $resultado['estado_verificacion'] = 'fecha_diferente';
                }
            }

            $resultados[] = $resultado;
        }

        // Ordenar: verificados primero, luego parciales, luego no encontrados
        $orden = ['verificado' => 0, 'fecha_diferente' => 1, 'monto_diferente' => 2, 'monto_fecha_diferente' => 3, 'no_encontrado' => 4];
        usort($resultados, function ($a, $b) use ($orden) {
            return ($orden[$a['estado_verificacion']] ?? 5) <=> ($orden[$b['estado_verificacion']] ?? 5);
        });

        return view('asuntos.verificar_pagos', compact('resultados'));
    }

    /**
     * Confirmar y actualizar los pagos verificados.
     */
    public function confirmarVerificados(Request $request)
    {
        $ids = $request->input('pago_ids', []);
        
        if (empty($ids)) {
            return redirect()->route('pago.pendientes')
                ->with('error', 'No se seleccionaron pagos para actualizar.');
        }

        $count = 0;
        foreach ($ids as $id) {
            $pago = Pagos::find($id);
            if ($pago && $pago->estado === 'Pendiente') {
                // Crear el asunto para el estudiante (misma lógica que ActualizarEstado)
                $asunto = Asunto::where('nombre', $pago->asunto)
                    ->whereNull('cedula_estudiante')
                    ->first();
                
                if ($asunto) {
                    $asuntoEstudiante = new Asunto();
                    $asuntoEstudiante->nombre = $asunto->nombre;
                    $asuntoEstudiante->descripcion = $asunto->descripcion;
                    $asuntoEstudiante->cedula_estudiante = $pago->cedula;
                    $asuntoEstudiante->activo = true;
                    $asuntoEstudiante->save();
                }

                $pago->estado = "Actualizado";
                $pago->save();
                $count++;
            }
        }

        return redirect()->route('pago.pendientes')
            ->with('success', "Se actualizaron {$count} pago(s) exitosamente.");
    }

    /**
     * Guardar la URL del Google Sheet.
     */
    public function configurarSheet(Request $request)
    {
        $request->validate([
            'sheet_url' => 'required|url',
        ]);

        $config = [
            'url' => $request->sheet_url,
            'fecha_configuracion' => now()->format('d/m/Y H:i'),
        ];

        Storage::put('google_sheet_config.json', json_encode($config));

        return redirect()->route('pago.pendientes')
            ->with('success', 'URL del Google Sheet configurada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function ActualizarEstado(int $id)
    {
        $pago = Pagos::find($id);
        $asunto= Asunto::where('nombre', $pago->asunto)->first();
        $asuntoestudiante=new Asunto();
        $asuntoestudiante->nombre = $asunto->nombre;
        $asuntoestudiante->descripcion = $asunto->descripcion;
        $asuntoestudiante->cedula_estudiante = $pago->cedula;
        $asuntoestudiante->activo= true;
        $asuntoestudiante->save();
        $pago->estado = "Actualizado";
        $pago->save();
        return redirect()->route('pago.controlpagos')->with('success', 'Estado de pago actualizado exitosamente');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pagos $pagos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pagos $pagos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pagos $pagos)
    {
        //
    }
}
