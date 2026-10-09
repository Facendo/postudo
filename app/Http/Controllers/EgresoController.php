<?php

namespace App\Http\Controllers;

use App\Models\Egreso;
use App\Models\Pagos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EgresoController extends Controller
{
    /**
     * Reporte de ingresos (pagos verificados) y egresos registrados.
     */
    public function index()
    {
        // Ingresos: solo los pagos ya verificados/actualizados por el administrador
        $ingresos = Pagos::with('estudiante')
            ->where('estado', 'Actualizado')
            ->orderBy('fecha_pago', 'desc')
            ->get();

        // Egresos: todo lo registrado por el administrador
        $egresos = Egreso::with('usuario')
            ->orderBy('fecha', 'desc')
            ->get();

        // Resumen de totales
        $totalIngresos = (float) $ingresos->sum('monto');
        $totalEgresos = (float) $egresos->sum('monto');
        $balance = $totalIngresos - $totalEgresos;

        return view('administrador.finanzas', compact('ingresos', 'egresos', 'totalIngresos', 'totalEgresos', 'balance'));
    }

    /**
     * Formulario para registrar un nuevo egreso.
     */
    public function create()
    {
        $tasaCambio = null;
        if (Storage::exists('tasa_cambio.json')) {
            $tasaCambio = json_decode(Storage::get('tasa_cambio.json'), true);
        }

        return view('administrador.registroegreso', [
            'motivos' => Egreso::MOTIVOS,
            'tasaCambio' => $tasaCambio,
        ]);
    }

    /**
     * Guardar un nuevo egreso.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'monto' => ['required', 'numeric', 'min:0.01'],
            'fecha' => ['required', 'date'],
            'motivo' => ['required', 'in:'.implode(',', Egreso::MOTIVOS)],
            'detalle' => ['nullable', 'string', 'max:255'],
        ], [
            'monto.min' => 'El monto debe ser mayor a 0.',
            'motivo.in' => 'El motivo seleccionado no es válido.',
        ]);

        // Cuando el motivo es "Otro", el detalle es obligatorio
        if ($datos['motivo'] === 'Otro' && empty($datos['detalle'])) {
            return redirect()
                ->route('administrador.egreso.create')
                ->withInput()
                ->with('error', 'Debe indicar el detalle del motivo cuando selecciona "Otro".');
        }

        // Capturar la tasa de cambio vigente al momento de registrar
        $tasaMomento = null;
        if (Storage::exists('tasa_cambio.json')) {
            $tasaJson = json_decode(Storage::get('tasa_cambio.json'), true);
            $tasaMomento = $tasaJson['valor'] ?? null;
        }

        Egreso::create([
            'monto' => $datos['monto'],
            'tasa_momento' => $tasaMomento,
            'fecha' => $datos['fecha'],
            'motivo' => $datos['motivo'],
            'detalle' => $datos['detalle'] ?? null,
            'registrado_por' => Auth::id(),
        ]);

        return redirect()
            ->route('administrador.finanzas')
            ->with('success', 'Egreso registrado exitosamente.');
    }
}
