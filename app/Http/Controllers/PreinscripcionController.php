<?php

namespace App\Http\Controllers;

use App\Models\Mencion;
use App\Models\Postgrado;
use App\Models\Preinscripcion;
use App\Services\GeneradorPlanillaPreinscripcion;
use Illuminate\Http\Request;

/**
 * Preinscripción de estudiantes: formulario público que replica la planilla
 * .doc de la coordinación y devuelve el .docx relleno al enviarlo; el panel
 * (administrador y coordinador general) lista, aprueba, rechaza y permite
 * regenerar la planilla.
 *
 * Aprobar NO crea usuarios ni filas en 'estudiantes': solo sella la
 * preinscripción, que servirá para validar la futura inscripción.
 */
class PreinscripcionController extends Controller
{
    public function __construct(private GeneradorPlanillaPreinscripcion $generador) {}

    /**
     * Formulario público de preinscripción (anónimo, sin sesión).
     */
    public function create()
    {
        $postgrados = Postgrado::with('menciones')->orderBy('nombre')->get();

        return view('preinscripcion.create', [
            'postgrados' => $postgrados,
            'sedes' => Preinscripcion::SEDES,
        ]);
    }

    /**
     * Guarda la preinscripción y devuelve la planilla .docx rellena.
     */
    public function store(Request $request)
    {
        $datos = $request->validate($this->reglas());

        // La mención elegida debe pertenecer al postgrado seleccionado en el
        // formulario (el postgrado no se guarda: se deriva de la mención).
        $mencion = Mencion::with('postgrado')->find($datos['codigo_mencion']);
        if ($request->filled('id_postgrado')
            && $mencion
            && (string) $mencion->id_postgrado !== (string) $request->id_postgrado) {
            return back()
                ->withErrors(['codigo_mencion' => 'La mención seleccionada no pertenece al postgrado elegido.'])
                ->withInput();
        }

        $preinscripcion = Preinscripcion::create([
            'cedula' => $datos['cedula'],
            'nombre' => $datos['nombres'],
            'apellido' => $datos['apellidos'],
            'correo' => $datos['correo'],
            'telefono' => $datos['telefono'],
            'edad' => $datos['edad'],
            'sexo' => $datos['sexo'],
            'estado_civil' => $datos['estado_civil'],
            'nacionalidad' => $datos['nacionalidad'],
            'lugar_nacimiento' => $datos['lugar_nacimiento'],
            'etnia' => $datos['etnia'] ?? null,
            'direccion_permanente' => $datos['direccion_permanente'],
            'direccion_otra' => $datos['direccion_otra'] ?? null,
            'telefono_otro' => $datos['telefono_otro'] ?? null,
            'correo_otro' => $datos['correo_otro'] ?? null,
            'titulo_pregrado' => $datos['titulo_pregrado'],
            'institucion_pregrado' => $datos['institucion_pregrado'],
            'anio_grado' => $datos['anio_grado'],
            'trabaja' => $datos['trabaja'],
            'ocupacion' => $datos['ocupacion'] ?? null,
            'cargo' => $datos['cargo'] ?? null,
            'tiempo_cargo' => $datos['tiempo_cargo'] ?? null,
            'empresa' => $datos['empresa'] ?? null,
            'direccion_empresa' => $datos['direccion_empresa'] ?? null,
            'supervisor' => $datos['supervisor'] ?? null,
            'telefono_supervisor' => $datos['telefono_supervisor'] ?? null,
            'correo_supervisor' => $datos['correo_supervisor'] ?? null,
            'fuente_financiamiento' => $datos['fuente_financiamiento'],
            'historial_trabajos' => $datos['historial_trabajos'] ?? null,
            'experiencia_laboral' => $datos['experiencia_laboral'] ?? null,
            'grados' => $this->construirGrados($datos),
            'referencias' => $this->construirReferencias($datos),
            'documentos' => $this->construirDocumentos($datos),
            'codigo_mencion' => $mencion?->id_mencion,
            'sede' => $datos['sede'],
            'voucher_nro' => $datos['voucher_nro'] ?? null,
            'voucher_fecha' => $datos['voucher_fecha'] ?? null,
            'voucher_banco' => $datos['voucher_banco'] ?? null,
            'estado' => 'pendiente',
        ]);

        return $this->descargarPlanilla($preinscripcion);
    }

    /**
     * Listado de preinscripciones (panel de administrador/coordinador).
     */
    public function index()
    {
        $preinscripciones = Preinscripcion::with('mencion.postgrado')
            ->orderByDesc('created_at')
            ->get();

        return view('administrador.preinscripciones', compact('preinscripciones'));
    }

    /**
     * Aprueba la preinscripción (habilita al aspirante para la inscripción).
     */
    public function aprobar(Preinscripcion $preinscripcion)
    {
        $preinscripcion->update(['estado' => 'aprobada']);

        return redirect()
            ->route('administrador.preinscripciones.index')
            ->with('success', 'Preinscripción aprobada. El aspirante queda habilitado para su inscripción.');
    }

    /**
     * Rechaza la preinscripción.
     */
    public function rechazar(Preinscripcion $preinscripcion)
    {
        $preinscripcion->update(['estado' => 'rechazada']);

        return redirect()
            ->route('administrador.preinscripciones.index')
            ->with('success', 'Preinscripción rechazada.');
    }

    /**
     * Regenera la planilla .docx desde la fila guardada y la descarga.
     */
    public function descarga(Preinscripcion $preinscripcion)
    {
        return $this->descargarPlanilla($preinscripcion);
    }

    /**
     * Respuesta de descarga del .docx (el archivo temporal se borra al
     * terminar el envío).
     */
    private function descargarPlanilla(Preinscripcion $preinscripcion)
    {
        $ruta = $this->generador->generar($preinscripcion);

        return response()->download(
            $ruta,
            'PREINSCRIPCION_'.$preinscripcion->cedula.'.docx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
        )->deleteFileAfterSend(true);
    }

    /**
     * Reglas de validación del formulario completo (réplica de la planilla).
     */
    private function reglas(): array
    {
        $reglas = [
            // Voucher del depósito
            'voucher_nro' => ['nullable', 'string', 'max:50'],
            'voucher_fecha' => ['nullable', 'date'],
            'voucher_banco' => ['nullable', 'string', 'max:100'],

            // Interés académico
            'id_postgrado' => ['nullable', 'string', 'max:20'],
            'codigo_mencion' => ['required', 'integer', 'exists:mencion,id_mencion'],
            'sede' => ['required', 'string', 'in:'.implode(',', Preinscripcion::SEDES)],

            // Datos personales
            'apellidos' => ['required', 'string', 'max:100'],
            'nombres' => ['required', 'string', 'max:100'],
            'cedula' => ['required', 'string', 'max:20', 'regex:/^[0-9.\-]+$/', 'unique:preinscripcion,cedula'],
            'edad' => ['required', 'integer', 'min:18', 'max:99'],
            'sexo' => ['required', 'in:M,F'],
            'estado_civil' => ['required', 'in:Soltero(a),Casado(a),Divorciado(a),Viudo(a),Unión libre'],
            'nacionalidad' => ['required', 'string', 'max:50'],
            'lugar_nacimiento' => ['required', 'string', 'max:150'],
            'etnia' => ['nullable', 'string', 'max:50'],
            'direccion_permanente' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:30'],
            'correo' => ['required', 'email', 'max:150'],
            'direccion_otra' => ['nullable', 'string', 'max:255'],
            'telefono_otro' => ['nullable', 'string', 'max:30'],
            'correo_otro' => ['nullable', 'email', 'max:150'],

            // Formación de pregrado
            'titulo_pregrado' => ['required', 'string', 'max:150'],
            'institucion_pregrado' => ['required', 'string', 'max:150'],
            'anio_grado' => ['required', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],

            // Situación laboral
            'trabaja' => ['required', 'in:dependiente,cuenta_propia,libre_ejercicio,no'],
            'ocupacion' => ['nullable', 'string', 'max:150'],
            'cargo' => ['nullable', 'string', 'max:150'],
            'tiempo_cargo' => ['nullable', 'string', 'max:50'],
            'empresa' => ['nullable', 'string', 'max:255'],
            'direccion_empresa' => ['nullable', 'string', 'max:255'],
            'supervisor' => ['nullable', 'string', 'max:150'],
            'telefono_supervisor' => ['nullable', 'string', 'max:30'],
            'correo_supervisor' => ['nullable', 'email', 'max:150'],

            // Financiamiento y textos libres
            'fuente_financiamiento' => ['required', 'in:propia,organismo_publico,otra'],
            'historial_trabajos' => ['nullable', 'string', 'max:5000'],
            'experiencia_laboral' => ['nullable', 'string', 'max:5000'],
        ];

        // Tabla de grados (3 filas fijas por tipo) y referencias (2 personas).
        foreach (['universitario', 'postgrado', 'otros'] as $tipo) {
            $reglas["grado_{$tipo}_institucion"] = ['nullable', 'string', 'max:150'];
            $reglas["grado_{$tipo}_titulo"] = ['nullable', 'string', 'max:150'];
            $reglas["grado_{$tipo}_anos"] = ['nullable', 'string', 'max:20'];
            $reglas["grado_{$tipo}_pais"] = ['nullable', 'string', 'max:80'];
            $reglas["grado_{$tipo}_fecha"] = ['nullable', 'string', 'max:30'];
        }

        foreach ([1, 2] as $numero) {
            $reglas["ref{$numero}_nombre"] = ['required', 'string', 'max:150'];
            $reglas["ref{$numero}_edad"] = ['required', 'integer', 'min:1', 'max:110'];
            $reglas["ref{$numero}_direccion"] = ['required', 'string', 'max:255'];
            $reglas["ref{$numero}_telefono"] = ['required', 'string', 'max:30'];
            $reglas["ref{$numero}_email"] = ['required', 'email', 'max:150'];
            $reglas["ref{$numero}_ocupacion"] = ['required', 'string', 'max:150'];
            $reglas["ref{$numero}_cargo"] = ['required', 'string', 'max:150'];
            $reglas["ref{$numero}_empresa"] = ['required', 'string', 'max:150'];
            $reglas["ref{$numero}_telefono_trabajo"] = ['nullable', 'string', 'max:30'];
            $reglas["ref{$numero}_email_trabajo"] = ['nullable', 'email', 'max:150'];
            $reglas["ref{$numero}_direccion_trabajo"] = ['nullable', 'string', 'max:255'];
        }

        // Checklist de documentación (7 casillas SI/NO del .doc).
        foreach (range(1, 7) as $numero) {
            $reglas["doc{$numero}"] = ['required', 'in:si,no'];
        }

        return $reglas;
    }

    /**
     * Agrupa los inputs planos de la tabla de grados por tipo.
     */
    private function construirGrados(array $datos): array
    {
        $grados = [];
        foreach (['universitario', 'postgrado', 'otros'] as $tipo) {
            $grados[$tipo] = [
                'institucion' => $datos["grado_{$tipo}_institucion"] ?? null,
                'titulo' => $datos["grado_{$tipo}_titulo"] ?? null,
                'anos' => $datos["grado_{$tipo}_anos"] ?? null,
                'pais' => $datos["grado_{$tipo}_pais"] ?? null,
                'fecha' => $datos["grado_{$tipo}_fecha"] ?? null,
            ];
        }

        return $grados;
    }

    /**
     * Agrupa los inputs planos de las dos referencias personales.
     */
    private function construirReferencias(array $datos): array
    {
        $referencias = [];
        foreach ([1, 2] as $numero) {
            $referencias[] = [
                'nombre' => $datos["ref{$numero}_nombre"],
                'edad' => $datos["ref{$numero}_edad"],
                'direccion' => $datos["ref{$numero}_direccion"],
                'telefono' => $datos["ref{$numero}_telefono"],
                'email' => $datos["ref{$numero}_email"],
                'ocupacion' => $datos["ref{$numero}_ocupacion"],
                'cargo' => $datos["ref{$numero}_cargo"],
                'empresa' => $datos["ref{$numero}_empresa"],
                'telefono_trabajo' => $datos["ref{$numero}_telefono_trabajo"] ?? null,
                'email_trabajo' => $datos["ref{$numero}_email_trabajo"] ?? null,
                'direccion_trabajo' => $datos["ref{$numero}_direccion_trabajo"] ?? null,
            ];
        }

        return $referencias;
    }

    /**
     * Mapea las casillas doc1..doc7 a claves estables por documento.
     */
    private function construirDocumentos(array $datos): array
    {
        return [
            'cedula' => $datos['doc1'],
            'partida' => $datos['doc2'],
            'militar' => $datos['doc3'],
            'titulo' => $datos['doc4'],
            'notas' => $datos['doc5'],
            'sintesis' => $datos['doc6'],
            'constancia' => $datos['doc7'],
        ];
    }
}
