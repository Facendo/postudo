<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FiltrablePorCoordinador;
use App\Models\Cohorte;
use App\Models\Mencion;
use App\Models\Postgrado;
use App\Models\Preinscripcion;
use App\Services\GeneradorPlanillaPreinscripcion;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Preinscripción de estudiantes: formulario público que replica la planilla
 * .doc de la coordinación y devuelve el .docx relleno al enviarlo; el panel
 * (administrador, coordinador general y coordinador de postgrado) lista,
 * aprueba, rechaza y permite regenerar la planilla.
 *
 * El coordinador de postgrado solo ve (y puede actuar sobre) las
 * preinscripciones de SUS postgrados asignados — trait FiltrablePorCoordinador.
 *
 * Aprobar NO crea usuarios ni filas en 'estudiantes': solo sella la
 * preinscripción, que servirá para validar la futura inscripción.
 */
class PreinscripcionController extends Controller
{
    use FiltrablePorCoordinador;

    /**
     * Formatos compartidos con la vista (create.blade.php): teléfono móvil
     * 04xx o fijo 02xx con separadores opcionales y +58 opcional; cédula con
     * prefijo V/E/P/J/G opcional; fecha de grado en dd-mm-aaaa.
     */
    private const REGEX_TELEFONO = '/^(?:\+?58[\s.-]*)?0(?:4\d{2}|2\d{2})[\s.-]*\d{7}$/';

    private const REGEX_CEDULA = '/^(?:[VEPJG][\s.-]*)?\d+(?:[.\s-]\d+)*$/i';

    private const REGEX_GRADO_FECHA = '/^(0[1-9]|[12]\d|3[01])[-\/](0[1-9]|1[0-2])[-\/]\d{4}$/';

    /** Mensajes compartidos de las reglas «solo letras»/«solo números». */
    private const MENSAJE_SOLO_LETRAS = 'Solo se permiten letras, espacios, guiones y apóstrofos.';

    private const MENSAJE_SOLO_NUMEROS = 'Solo se permiten números.';

    public function __construct(private GeneradorPlanillaPreinscripcion $generador) {}

    /**
     * Formulario público de preinscripción (anónimo, sin sesión).
     *
     * Solo lista los postgrados con preinscripción ABIERTA hoy (que tengan
     * alguna cohorte con la ventana desde/hasta vigente). Admite
     * ?postgrado=COD — así llegan los botones por postgrado de la portada —
     * que precarga el select; si ese postgrado no está habilitado, vuelve a
     * la portada con aviso en vez de mostrar un formulario inútil.
     */
    public function create(Request $request)
    {
        $postgrados = Postgrado::with('menciones')
            ->whereHas('cohortes', fn ($query) => $query->preinscripcionAbierta())
            ->orderBy('nombre')
            ->get();

        $postgradoPreseleccionado = null;
        $mencionPreseleccionada = null;
        $ventanaPreinscripcion = null;

        $solicitado = $request->query('postgrado');

        if ($solicitado !== null) {
            if (! $postgrados->contains('id_postgrado', $solicitado)) {
                return redirect()
                    ->route('inicio')
                    ->with('error', 'Las preinscripciones de este postgrado no están abiertas actualmente.');
            }

            $postgradoPreseleccionado = $solicitado;

            // Fecha límite (la mayor «hasta» entre las cohortes abiertas del
            // postgrado) para el aviso de la ventana en el formulario.
            $ventanaPreinscripcion = Cohorte::preinscripcionAbierta()
                ->where('codigo_postgrado', $solicitado)
                ->max('preinscripcion_hasta');

            // Mención de las cohortes abiertas: si todas apuntan a la MISMA
            // mención (caso típico: una sola cohorte creada), se precarga
            // también; con varias menciones abiertas el visitante elige
            // (preseleccionar una al azar en la planilla sería arriesgado).
            $mencionesAbiertas = Cohorte::preinscripcionAbierta()
                ->where('codigo_postgrado', $solicitado)
                ->whereNotNull('codigo_mencion')
                ->pluck('codigo_mencion')
                ->unique();

            $mencionPreseleccionada = $mencionesAbiertas->count() === 1
                ? $mencionesAbiertas->first()
                : null;
        }

        return view('preinscripcion.create', [
            'postgrados' => $postgrados,
            'sedes' => Preinscripcion::SEDES,
            'postgradoPreseleccionado' => $postgradoPreseleccionado,
            'mencionPreseleccionada' => $mencionPreseleccionada,
            'ventanaPreinscripcion' => $ventanaPreinscripcion,
        ]);
    }

    /**
     * Guarda la preinscripción y devuelve la planilla .docx rellena.
     */
    public function store(Request $request)
    {
        $datos = $request->validate($this->reglas($request), $this->mensajes());

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

        // Revalidación de apertura (2026-11-11): el postgrado real es el de
        // la mención elegida (nunca el del cliente) y debe tener la
        // preinscripción abierta HOY — por URL directa no se cuela nadie.
        if (! $this->preinscripcionAbiertaEn($mencion?->id_postgrado)) {
            return back()
                ->withErrors(['codigo_mencion' => 'Las preinscripciones de este postgrado no están abiertas actualmente.'])
                ->withInput();
        }

        // Bloque laboral: si no trabaja, todo el bloque se limpia (los campos
        // están ocultos en la vista, así que no deben guardar datos aunque
        // lleguen manipulados por POST).
        if ($datos['trabaja'] === 'no') {
            foreach (['ocupacion', 'cargo', 'tiempo_cargo', 'empresa', 'direccion_empresa', 'supervisor', 'telefono_supervisor', 'correo_supervisor'] as $campoLaboral) {
                $datos[$campoLaboral] = null;
            }
        }

        // doc3 (libreta militar) solo aplica a varones: si es mujer y no vino
        // marcado (viene deshabilitado y no se envía), queda «No» para que
        // construirDocumentos no acceda a un índice inexistente.
        if ($datos['sexo'] !== 'M') {
            $datos['doc3'] = $datos['doc3'] ?? 'no';
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
     *
     * El coordinador de postgrado solo ve las de SUS postgrados asignados
     * (null = sin filtro para administrador y coordinador general). Las
     * preinscripciones sin mención no pertenecen a ningún postgrado: solo
     * las ven admin y coordinador general.
     */
    public function index()
    {
        $permitidos = $this->idsPostgradosPermitidos(Auth::user());

        $preinscripciones = Preinscripcion::with('mencion.postgrado')
            ->when($permitidos, fn ($query) => $query->whereHas(
                'mencion',
                fn ($mencion) => $mencion->whereIn('id_postgrado', $permitidos)
            ))
            ->orderByDesc('created_at')
            ->get();

        return view('administrador.preinscripciones', [
            'preinscripciones' => $preinscripciones,
            // Para el aviso de estado vacío: coordinador sin postgrados vs. sin preinscripciones.
            'sinPostgradosAsignados' => $permitidos !== null && $permitidos->isEmpty(),
        ]);
    }

    /**
     * Aprueba la preinscripción: el aspirante pasa al apartado de
     * Inscripciones del coordinador de postgrado, que es quien lo da de
     * alta en 'estudiantes' y lo inscribe en una cohorte (2026-11-23).
     */
    public function aprobar(Preinscripcion $preinscripcion)
    {
        if (! $this->puedeGestionarPostgrado(Auth::user(), $preinscripcion->mencion?->id_postgrado)) {
            return redirect()
                ->route('administrador.preinscripciones.index')
                ->with('error', 'Esta preinscripción no pertenece a tus postgrados.');
        }

        $preinscripcion->update(['estado' => 'aprobada']);

        // El coordinador de postgrado continúa en el apartado de
        // Inscripciones (ahí aprueba/rechaza al aspirante y lo inscribe en
        // una cohorte); los demás roles vuelven al listado de preinscripciones.
        $esCoordinadorDePostgrado = Auth::user()->rol === 'coordinador_postgrado';

        return redirect()
            ->route($esCoordinadorDePostgrado
                ? 'coordinador.inscripciones.index'
                : 'administrador.preinscripciones.index')
            ->with(
                'success',
                $esCoordinadorDePostgrado
                    ? 'Preinscripción aprobada. Continúa en Inscripciones para aprobar al aspirante e inscribirlo en una cohorte.'
                    : 'Preinscripción aprobada. El coordinador del postgrado gestionará su inscripción desde su apartado de Inscripciones.'
            );
    }

    /**
     * Rechaza la preinscripción.
     */
    public function rechazar(Preinscripcion $preinscripcion)
    {
        if (! $this->puedeGestionarPostgrado(Auth::user(), $preinscripcion->mencion?->id_postgrado)) {
            return redirect()
                ->route('administrador.preinscripciones.index')
                ->with('error', 'Esta preinscripción no pertenece a tus postgrados.');
        }

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
        if (! $this->puedeGestionarPostgrado(Auth::user(), $preinscripcion->mencion?->id_postgrado)) {
            return redirect()
                ->route('administrador.preinscripciones.index')
                ->with('error', 'Esta preinscripción no pertenece a tus postgrados.');
        }

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
     *
     * Recibe el request porque varias reglas dependen de otros campos: el
     * bloque laboral (si trabaja), doc3 (solo varones) y las referencias,
     * que no pueden repetir el teléfono ni el correo del titular.
     */
    private function reglas(Request $request): array
    {
        $reglas = [
            // Voucher del depósito
            'voucher_nro' => ['nullable', 'string', 'max:50', $this->soloNumeros()],
            'voucher_fecha' => ['nullable', 'date', 'before_or_equal:today'],
            'voucher_banco' => ['nullable', 'string', 'max:100'],

            // Interés académico
            'id_postgrado' => ['nullable', 'string', 'max:20'],
            'codigo_mencion' => ['required', 'integer', 'exists:mencion,id_mencion'],
            'sede' => ['required', 'string', 'in:'.implode(',', Preinscripcion::SEDES)],

            // Datos personales
            'apellidos' => ['required', 'string', 'max:100', $this->soloLetras()],
            'nombres' => ['required', 'string', 'max:100', $this->soloLetras()],
            'cedula' => ['required', 'string', 'max:20', $this->cedulaValida(), 'unique:preinscripcion,cedula'],
            'edad' => ['required', 'integer', 'min:18', 'max:99'],
            'sexo' => ['required', 'in:M,F'],
            'estado_civil' => ['required', 'in:Soltero(a),Casado(a),Divorciado(a),Viudo(a),Unión libre'],
            'nacionalidad' => ['required', 'string', 'max:50'],
            'lugar_nacimiento' => ['required', 'string', 'max:150'],
            'etnia' => ['nullable', 'string', 'max:50'],
            'direccion_permanente' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:30', $this->telefonoValido()],
            'correo' => ['required', 'email', 'max:150'],
            'direccion_otra' => ['nullable', 'string', 'max:255'],
            'telefono_otro' => ['nullable', 'string', 'max:30', $this->telefonoValido()],
            'correo_otro' => ['nullable', 'email', 'max:150'],

            // Formación de pregrado
            'titulo_pregrado' => ['required', 'string', 'max:150'],
            'institucion_pregrado' => ['required', 'string', 'max:150'],
            'anio_grado' => ['required', 'integer', 'min:1900', 'max:'.date('Y')],

            // Situación laboral (los tres obligatorios condicionales se
            // añaden más abajo cuando trabaja ≠ «no»)
            'trabaja' => ['required', 'in:dependiente,cuenta_propia,libre_ejercicio,no'],
            'ocupacion' => ['nullable', 'string', 'max:150'],
            'cargo' => ['nullable', 'string', 'max:150'],
            'tiempo_cargo' => ['nullable', 'string', 'max:50'],
            'empresa' => ['nullable', 'string', 'max:255'],
            'direccion_empresa' => ['nullable', 'string', 'max:255'],
            'supervisor' => ['nullable', 'string', 'max:150', $this->soloLetras()],
            'telefono_supervisor' => ['nullable', 'string', 'max:30', $this->telefonoValido()],
            'correo_supervisor' => ['nullable', 'email', 'max:150'],

            // Financiamiento y textos libres
            'fuente_financiamiento' => ['required', 'in:propia,organismo_publico,otra'],
            'historial_trabajos' => ['nullable', 'string', 'max:5000'],
            'experiencia_laboral' => ['nullable', 'string', 'max:5000'],
        ];

        // Trabaja (≠ «no»): ocupación, cargo y empresa pasan a ser
        // obligatorios (bloque «básico»); con «no» store() limpia el bloque.
        if (in_array($request->input('trabaja'), ['dependiente', 'cuenta_propia', 'libre_ejercicio'], true)) {
            $reglas['ocupacion'] = ['required', 'string', 'max:150'];
            $reglas['cargo'] = ['required', 'string', 'max:150'];
            $reglas['empresa'] = ['required', 'string', 'max:255'];
        }

        // Tabla de grados (3 filas fijas por tipo) y referencias (2 personas).
        foreach (['universitario', 'postgrado', 'otros'] as $tipo) {
            $reglas["grado_{$tipo}_institucion"] = ['nullable', 'string', 'max:150'];
            $reglas["grado_{$tipo}_titulo"] = ['nullable', 'string', 'max:150'];
            $reglas["grado_{$tipo}_anos"] = ['nullable', 'string', 'max:20', $this->soloNumeros()];
            $reglas["grado_{$tipo}_pais"] = ['nullable', 'string', 'max:80', $this->soloLetras()];
            $reglas["grado_{$tipo}_fecha"] = ['nullable', 'string', 'max:30', $this->fechaGradoValida()];
        }

        foreach ([1, 2] as $numero) {
            $reglas["ref{$numero}_nombre"] = ['required', 'string', 'max:150', $this->soloLetras()];
            $reglas["ref{$numero}_edad"] = ['required', 'integer', 'min:1', 'max:110'];
            $reglas["ref{$numero}_direccion"] = ['required', 'string', 'max:255'];
            $reglas["ref{$numero}_telefono"] = [
                'required',
                'string',
                'max:30',
                $this->telefonoValido(),
                // Referencia ≠ titular (comparación normalizada: dígitos sin +58).
                function (string $atributo, mixed $valor, Closure $fallar) use ($request): void {
                    $digitos = $this->normalizarTelefono((string) $valor);
                    if ($digitos !== '' && $digitos === $this->normalizarTelefono((string) $request->input('telefono'))) {
                        $fallar('El teléfono de la referencia no puede ser igual al teléfono del titular.');
                    }
                },
            ];
            $reglas["ref{$numero}_email"] = [
                'required',
                'email',
                'max:150',
                // Referencia ≠ titular (comparación sin distinguir mayúsculas).
                function (string $atributo, mixed $valor, Closure $fallar) use ($request): void {
                    $correo = mb_strtolower(trim((string) $valor));
                    if ($correo !== '' && $correo === mb_strtolower(trim((string) $request->input('correo')))) {
                        $fallar('El correo de la referencia no puede ser igual al correo del titular.');
                    }
                },
            ];
            $reglas["ref{$numero}_ocupacion"] = ['required', 'string', 'max:150'];
            $reglas["ref{$numero}_cargo"] = ['required', 'string', 'max:150'];
            $reglas["ref{$numero}_empresa"] = ['required', 'string', 'max:150'];
            $reglas["ref{$numero}_telefono_trabajo"] = ['nullable', 'string', 'max:30', $this->telefonoValido()];
            $reglas["ref{$numero}_email_trabajo"] = ['nullable', 'email', 'max:150'];
            $reglas["ref{$numero}_direccion_trabajo"] = ['nullable', 'string', 'max:255'];
        }

        // Checklist de documentación (7 casillas SI/NO del .doc); doc3 es la
        // libreta militar y solo es obligatoria para varones.
        foreach (range(1, 7) as $numero) {
            $reglas["doc{$numero}"] = $numero === 3 && $request->input('sexo') !== 'M'
                ? ['nullable', 'in:si,no']
                : ['required', 'in:si,no'];
        }

        return $reglas;
    }

    /**
     * Mensajes en español para las reglas nuevas (el proyecto no tiene
     * carpeta lang/: el resto de mensajes siguen en inglés por defecto).
     */
    private function mensajes(): array
    {
        return [
            'cedula.unique' => 'Ya existe una preinscripción registrada con esta cédula.',
            'voucher_fecha.date' => 'La fecha del voucher no es una fecha válida.',
            'voucher_fecha.before_or_equal' => 'La fecha del voucher no puede ser futura.',
            'anio_grado.min' => 'El año de grado debe ser 1900 o posterior.',
            'anio_grado.max' => 'El año de grado no puede ser posterior al año actual.',
            'ocupacion.required' => 'La ocupación es obligatoria cuando indicó que trabaja.',
            'cargo.required' => 'El cargo es obligatorio cuando indicó que trabaja.',
            'empresa.required' => 'La empresa es obligatoria cuando indicó que trabaja.',
            'doc3.required' => 'Debe indicar si presenta la libreta militar (solo varones).',
        ];
    }

    /**
     * Formato de teléfono (idéntico al de la vista): móvil 04xx o fijo 02xx,
     * 7 dígitos, guion/espacio opcional y +58 opcional. Los campos
     * opcionales se envían vacíos: se acepta ''.
     */
    private function telefonoValido(): Closure
    {
        return function (string $atributo, mixed $valor, Closure $fallar): void {
            $telefono = trim((string) $valor);
            if ($telefono === '') {
                return;
            }
            if (! preg_match(self::REGEX_TELEFONO, $telefono)) {
                $fallar('El teléfono debe ser formato 0414-1234567 (también 0212-8765432 o +58-0414-1234567).');
            }
        };
    }

    /**
     * Cédula: forma libre con prefijo opcional (V/E/P/J/G) y entre 5 y 10
     * dígitos en total (se ignoran puntos y guiones como separadores).
     */
    private function cedulaValida(): Closure
    {
        return function (string $atributo, mixed $valor, Closure $fallar): void {
            $cedula = trim((string) $valor);
            if ($cedula === '') {
                return;
            }
            if (! preg_match(self::REGEX_CEDULA, $cedula)) {
                $fallar('El formato de la cédula no es válido (ejemplos: 30123456, V-30.123.456).');

                return;
            }
            $digitos = (string) preg_replace('/\D/', '', (string) preg_replace('/^[VEPJG]/i', '', $cedula));
            if (strlen($digitos) < 5 || strlen($digitos) > 10) {
                $fallar('La cédula debe tener entre 5 y 10 dígitos.');
            }
        };
    }

    /**
     * Fecha de la tabla de grados: dd-mm-aaaa (también con /), fecha real
     * válida y no posterior al año en curso.
     */
    private function fechaGradoValida(): Closure
    {
        return function (string $atributo, mixed $valor, Closure $fallar): void {
            $fecha = trim((string) $valor);
            if ($fecha === '') {
                return;
            }
            if (! preg_match(self::REGEX_GRADO_FECHA, $fecha)) {
                $fallar('La fecha de grado debe tener el formato dd-mm-aaaa (ej.: 15-07-2025).');

                return;
            }
            [$dia, $mes, $anio] = array_map('intval', explode('-', str_replace('/', '-', $fecha)));
            if (! checkdate($mes, $dia, $anio)) {
                $fallar('La fecha de grado no es una fecha válida.');

                return;
            }
            if ($anio > (int) date('Y')) {
                $fallar('La fecha de grado no puede ser futura.');
            }
        };
    }

    /**
     * Teléfono normalizado para compararlo con el de una referencia: solo
     * dígitos y sin el prefijo 58 inicial.
     */
    private function normalizarTelefono(string $telefono): string
    {
        $digitos = (string) preg_replace('/\D/', '', $telefono);
        if (strlen($digitos) > 11 && str_starts_with($digitos, '58')) {
            $digitos = substr($digitos, 2);
        }

        return $digitos;
    }

    /**
     * Solo letras (con acentos y Ñ), espacios, guion y apóstrofo: nombres
     * de personas (titular, referencias, supervisor) y países. Admite
     * vacío para los campos opcionales.
     */
    private function soloLetras(): Closure
    {
        return function (string $atributo, mixed $valor, Closure $fallar): void {
            $texto = trim((string) $valor);
            if ($texto === '') {
                return;
            }
            if (! preg_match('/^[a-zA-ZáéíóúüñÁÉÍÓÚÜÑ\s\'-]+$/u', $texto)) {
                $fallar(self::MENSAJE_SOLO_LETRAS);
            }
        };
    }

    /**
     * Solo dígitos: nº de voucher y años cursados. Admite vacío.
     */
    private function soloNumeros(): Closure
    {
        return function (string $atributo, mixed $valor, Closure $fallar): void {
            $texto = trim((string) $valor);
            if ($texto === '') {
                return;
            }
            if (! preg_match('/^\d+$/', $texto)) {
                $fallar(self::MENSAJE_SOLO_NUMEROS);
            }
        };
    }

    /**
     * ¿El postgrado tiene la preinscripción abierta hoy (alguna cohorte con
     * la ventana vigente)? Devuelve false con null: sin mención no hay
     * postgrado al que validar.
     */
    private function preinscripcionAbiertaEn(?string $idPostgrado): bool
    {
        if ($idPostgrado === null) {
            return false;
        }

        return Cohorte::preinscripcionAbierta()
            ->where('codigo_postgrado', $idPostgrado)
            ->exists();
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
