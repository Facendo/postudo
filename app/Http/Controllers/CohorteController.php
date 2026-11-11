<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FiltrablePorCoordinador;
use App\Models\Cohorte;
use App\Models\Mencion;
use App\Models\Postgrado;
use App\Models\SolicitudAperturaCohorte;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;

class CohorteController extends Controller
{
    use FiltrablePorCoordinador;

    /**
     * Listado de cohortes. El coordinador de postgrado solo ve las de
     * SUS postgrados asignados.
     */
    public function index()
    {
        // eager-load mención y postgrado para pintar las columnas de la tabla.
        $permitidos = $this->idsPostgradosPermitidos(Auth::user());

        $cohortes = Cohorte::with(['mencion', 'postgrado'])
            ->when($permitidos, fn ($query) => $query->whereIn('codigo_postgrado', $permitidos))
            ->get();

        // Cupos de apertura disponibles: informativo solo para el rol
        // coordinador_postgrado (admin y coordinador general crean libre).
        $cuposDisponibles = Auth::user()->rol === 'coordinador_postgrado'
            ? SolicitudAperturaCohorte::cuposDisponiblesEn($permitidos?->all() ?? [])
            : null;

        return view('administrador.gestioncohorte', compact('cohortes', 'cuposDisponibles'));
    }

    /**
     * Formulario de creación de cohorte.
     *
     * $postgrado es opcional: llega desde «Detalles del Postgrado» (se muestra
     * solo ese postgrado) o sin parámetro desde el listado de cohortes (se
     * muestran todas las menciones agrupadas por postgrado). La mención es
     * obligatoria: no existe la cohorte "suelta" sin mención.
     */
    public function create(?string $postgrado = null)
    {
        $usuario = Auth::user();

        // Si viene un postgrado, debe estar asignado al usuario.
        if ($postgrado !== null && ! $this->puedeGestionarPostgrado($usuario, $postgrado)) {
            return redirect()
                ->route('administrador.gestioncohorte')
                ->with('error', 'Este postgrado no está asignado a tu cuenta.');
        }

        $postgradoSel = $postgrado ? Postgrado::findOrFail($postgrado) : null;

        // Solo las menciones de los postgrados que el usuario puede gestionar
        // (null = sin filtro para admin y coordinador general).
        $permitidos = $this->idsPostgradosPermitidos($usuario);

        // Gate de apertura (2026-11-11): sin cupos aprobados el formulario
        // ni siquiera se abre. El chequeo definitivo está en store(), aquí
        // solo es UX (admin y coordinador general crean libre).
        if ($usuario->rol === 'coordinador_postgrado'
            && SolicitudAperturaCohorte::cuposDisponiblesEn($permitidos?->all() ?? []) < 1) {
            return redirect()
                ->route('administrador.gestioncohorte')
                ->with('error', 'No tienes cupos de apertura disponibles. Solicita la apertura al coordinador general desde «Apertura de Cohortes».');
        }

        $menciones = Mencion::with('postgrado')
            ->when($permitidos, fn ($query) => $query->whereIn('id_postgrado', $permitidos))
            ->orderBy('id_postgrado')
            ->get();

        return view('administrador.registrocohorte', compact('postgradoSel', 'menciones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * El codigo_postgrado NO se toma del formulario: se deriva de la mención
     * elegida (nunca confiar en el dato del cliente). Tampoco el número de
     * cohorte: se autoincrementa con el máximo existente en la mención.
     * La fecha de fin tampoco viene del cliente: duración fija de 4 años
     * calculada en el servidor a partir de fecha_inicio.
     *
     * Apertura de cohortes (2026-11-11): si la sesión es coordinador de
     * postgrado, exige y consume UN cupo aprobado para el postgrado de la
     * mención, dentro de la misma transacción que crea la cohorte. Los
     * demás roles (admin, coordinador general) crean sin cupo.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'codigo_cohorte' => ['required', 'string', 'max:255', Rule::unique('cohorte', 'codigo_cohorte')],
            'codigo_mencion' => ['required', 'integer', $this->reglaMencionPropia()],
            'fecha_inicio' => ['required', 'date'],
            // Ventana de preinscripción: obligatoria (habilita el postgrado
            // en la portada mientras hoy esté dentro del rango).
            'preinscripcion_desde' => ['required', 'date'],
            'preinscripcion_hasta' => ['required', 'date', 'after_or_equal:preinscripcion_desde'],
            // Ventana de inscripción: registrada por ahora sin usarse (fase 2).
            'inscripcion_desde' => ['nullable', 'date', 'required_with:inscripcion_hasta'],
            'inscripcion_hasta' => ['nullable', 'date', 'after_or_equal:inscripcion_desde'],
            // Defensa: el número real se recalcula más abajo, nunca se toma del cliente.
            'nro_de_cohorte' => ['nullable', 'integer', 'min:1'],
        ], [
            'preinscripcion_desde.required' => 'Indica desde qué día se abre la preinscripción.',
            'preinscripcion_desde.date' => 'La fecha «Preinscripción: desde» no es válida.',
            'preinscripcion_hasta.required' => 'Indica hasta qué día se cierra la preinscripción.',
            'preinscripcion_hasta.after_or_equal' => 'El cierre de preinscripción debe ser igual o posterior a la apertura.',
            'inscripcion_desde.required' => 'Indica desde qué día se abre la inscripción (o deja ambos vacíos).',
            'inscripcion_hasta.after_or_equal' => 'El cierre de inscripción debe ser igual o posterior a la apertura.',
        ]);

        $mencion = Mencion::findOrFail($datos['codigo_mencion']);
        $esCoordinador = Auth::user()->rol === 'coordinador_postgrado';

        // Transacción: el cupo de apertura y la cohorte nacen juntos o no lo
        // hace ninguno (si la cohorte falla, el cupo no se consume).
        $cohorte = DB::transaction(function () use ($datos, $mencion, $esCoordinador) {
            // Sin cupo aprobado no hay cohorte (el gate es por la mención
            // elegida, cuyo postgrado sale de la BD, nunca del formulario).
            if ($esCoordinador && ! SolicitudAperturaCohorte::consumirCupo($mencion->id_postgrado)) {
                return null;
            }

            // Número de cohorte siguiente DENTRO de la mención (contador por mención):
            // la primera cohorte de la mención es la 1, la siguiente la 2, y así sucesivamente.
            $siguiente = (int) Cohorte::where('codigo_mencion', $mencion->id_mencion)
                ->max('nro_de_cohorte') + 1;

            $cohorte = new Cohorte;
            $cohorte->codigo_cohorte = $datos['codigo_cohorte'];
            $cohorte->codigo_mencion = $mencion->id_mencion;
            $cohorte->codigo_postgrado = $mencion->id_postgrado; // espejo derivado de la mención
            $cohorte->fecha_inicio = $datos['fecha_inicio'];
            $cohorte->fecha_fin = Carbon::parse($datos['fecha_inicio'])->addYears(4); // duración fija
            // Ventanas de proceso: preinscripción obligatoria; inscripción opcional.
            $cohorte->preinscripcion_desde = $datos['preinscripcion_desde'];
            $cohorte->preinscripcion_hasta = $datos['preinscripcion_hasta'];
            $cohorte->inscripcion_desde = $datos['inscripcion_desde'] ?? null;
            $cohorte->inscripcion_hasta = $datos['inscripcion_hasta'] ?? null;
            $cohorte->nro_de_cohorte = $siguiente;
            $cohorte->save();

            return $cohorte;
        });

        if ($cohorte === null) {
            return back()
                ->with('error', 'No puedes crear cohortes en este postgrado: no tienes cupos de apertura disponibles. Solicítalos al coordinador general desde «Apertura de Cohortes».')
                ->withInput();
        }

        return redirect()
            ->route('administrador.gestion_postgrado.showdetalles', $mencion->id_postgrado)
            ->with('success', 'Cohorte registrada correctamente.');
    }

    /**
     * Show the specified resource.
     */
    public function showdetalles(string $id)
    {
        $cohorte = Cohorte::findOrFail($id);

        // El coordinador de postgrado solo entra a cohortes de SUS postgrados.
        if (! $this->puedeGestionarPostgrado(Auth::user(), $cohorte->codigo_postgrado)) {
            return redirect()
                ->route('administrador.gestioncohorte')
                ->with('error', 'Esta cohorte no pertenece a tus postgrados.');
        }

        $cohorte->load('mencion', 'postgrado');

        return view('administrador.detallescohortes', compact('cohorte'));
    }

    /**
     * Formulario de edición: la mención también es obligatoria aquí (las
     * cohortes sin mención dejaron de existir con la limpieza del 2026-10-20).
     */
    public function edit(string $id)
    {
        $cohorte = Cohorte::findOrFail($id);

        // Solo cohortes de los postgrados asignados al usuario.
        if (! $this->puedeGestionarPostgrado(Auth::user(), $cohorte->codigo_postgrado)) {
            return redirect()
                ->route('administrador.gestioncohorte')
                ->with('error', 'Esta cohorte no pertenece a tus postgrados.');
        }

        $cohorte->load('mencion');
        $menciones = Mencion::with('postgrado')
            ->whereIn('id_postgrado', $this->idsPostgradosPermitidos(Auth::user()) ?? Mencion::query()->pluck('id_postgrado'))
            ->orderBy('id_postgrado')
            ->get();

        return view('administrador.editarcohorte', compact('cohorte', 'menciones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * Igual que al crear: la fecha de fin no viene del cliente, se recalcula
     * con duración fija de 4 años a partir de fecha_inicio.
     */
    public function update(Request $request, string $id)
    {
        $cohorte = Cohorte::findOrFail($id);

        if (! $this->puedeGestionarPostgrado(Auth::user(), $cohorte->codigo_postgrado)) {
            return redirect()
                ->route('administrador.gestioncohorte')
                ->with('error', 'Esta cohorte no pertenece a tus postgrados.');
        }

        $datos = $request->validate([
            'codigo_cohorte' => ['required', 'string', 'max:255', Rule::unique('cohorte', 'codigo_cohorte')->ignore($cohorte->codigo_cohorte)],
            'codigo_mencion' => ['required', 'integer', $this->reglaMencionPropia()],
            'fecha_inicio' => ['required', 'date'],
            // Ventana de preinscripción: obligatoria (es lo que habilita el
            // postgrado en la portada; aquí es donde se editan las cohortes
            // antiguas que nacieron sin fechas).
            'preinscripcion_desde' => ['required', 'date'],
            'preinscripcion_hasta' => ['required', 'date', 'after_or_equal:preinscripcion_desde'],
            // Ventana de inscripción: registrada por ahora sin usarse (fase 2).
            'inscripcion_desde' => ['nullable', 'date', 'required_with:inscripcion_hasta'],
            'inscripcion_hasta' => ['nullable', 'date', 'after_or_equal:inscripcion_desde'],
            'nro_de_cohorte' => [
                'required', 'integer', 'min:1',
                Rule::unique('cohorte', 'nro_de_cohorte')
                    ->ignore($cohorte->codigo_cohorte, 'codigo_cohorte')
                    ->where('codigo_mencion', $request->codigo_mencion),
            ],
        ], [
            'preinscripcion_desde.required' => 'Indica desde qué día se abre la preinscripción.',
            'preinscripcion_desde.date' => 'La fecha «Preinscripción: desde» no es válida.',
            'preinscripcion_hasta.required' => 'Indica hasta qué día se cierra la preinscripción.',
            'preinscripcion_hasta.after_or_equal' => 'El cierre de preinscripción debe ser igual o posterior a la apertura.',
            'inscripcion_desde.required' => 'Indica desde qué día se abre la inscripción (o deja ambos vacíos).',
            'inscripcion_hasta.after_or_equal' => 'El cierre de inscripción debe ser igual o posterior a la apertura.',
        ]);

        $mencion = Mencion::findOrFail($datos['codigo_mencion']);

        $cohorte->codigo_cohorte = $datos['codigo_cohorte'];
        $cohorte->codigo_mencion = $mencion->id_mencion;
        $cohorte->codigo_postgrado = $mencion->id_postgrado; // espejo derivado de la mención
        $cohorte->fecha_inicio = $datos['fecha_inicio'];
        $cohorte->fecha_fin = Carbon::parse($datos['fecha_inicio'])->addYears(4); // duración fija
        // Ventanas de proceso: preinscripción obligatoria; inscripción opcional.
        $cohorte->preinscripcion_desde = $datos['preinscripcion_desde'];
        $cohorte->preinscripcion_hasta = $datos['preinscripcion_hasta'];
        $cohorte->inscripcion_desde = $datos['inscripcion_desde'] ?? null;
        $cohorte->inscripcion_hasta = $datos['inscripcion_hasta'] ?? null;
        $cohorte->nro_de_cohorte = $datos['nro_de_cohorte'];
        $cohorte->save();

        return redirect()
            ->route('administrador.gestion_postgrado.showdetalles', $mencion->id_postgrado)
            ->with('success', 'Cohorte actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cohorte = Cohorte::find($id);

        if (! $cohorte || ! $this->puedeGestionarPostgrado(Auth::user(), $cohorte->codigo_postgrado)) {
            return redirect()
                ->route('administrador.gestioncohorte')
                ->with('error', 'Esta cohorte no pertenece a tus postgrados.');
        }

        $codigo_postgrado = $cohorte->codigo_postgrado; // Guardar el código del postgrado antes de eliminar la cohorte
        $cohorte->delete();

        return redirect()->route('administrador.gestion_postgrado.showdetalles', $codigo_postgrado)->with('success', 'Cohorte eliminada correctamente.');
    }

    /**
     * La mención del formulario debe existir Y pertenecer a un postgrado que
     * el usuario pueda gestionar (sin filtro para admin y coordinador general).
     *
     * Nota: el retorno es Exists (lo que devuelve Rule::exists), NO la clase
     * Rule (fábrica estática) — con `: Rule` explotaba en TypeError al validar.
     */
    private function reglaMencionPropia(): Exists
    {
        $permitidos = $this->idsPostgradosPermitidos(Auth::user());

        $regla = Rule::exists('mencion', 'id_mencion');

        if ($permitidos !== null) {
            $regla->where(function ($query) use ($permitidos) {
                $query->whereIn('id_postgrado', $permitidos);
            });
        }

        return $regla;
    }
}
