<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FiltrablePorCoordinador;
use App\Models\Cohorte;
use App\Models\Materias;
use App\Models\Mencion;
use App\Models\Postgrado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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

        return view('administrador.gestioncohorte', compact('cohortes'));
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
     * elegida (nunca confiar en el dato del cliente). Número de cohorte único
     * dentro de la misma mención.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'codigo_cohorte' => ['required', 'string', 'max:255', Rule::unique('cohorte', 'codigo_cohorte')],
            'codigo_mencion' => ['required', 'integer', $this->reglaMencionPropia()],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'nro_de_cohorte' => [
                'required', 'integer', 'min:1',
                Rule::unique('cohorte', 'nro_de_cohorte')->where('codigo_mencion', $request->codigo_mencion),
            ],
        ]);

        $mencion = Mencion::findOrFail($datos['codigo_mencion']);

        $cohorte = new Cohorte;
        $cohorte->codigo_cohorte = $datos['codigo_cohorte'];
        $cohorte->codigo_mencion = $mencion->id_mencion;
        $cohorte->codigo_postgrado = $mencion->id_postgrado; // espejo derivado de la mención
        $cohorte->fecha_inicio = $datos['fecha_inicio'];
        $cohorte->fecha_fin = $datos['fecha_fin'];
        $cohorte->nro_de_cohorte = $datos['nro_de_cohorte'];
        $cohorte->save();

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

        $materias = Materias::where('codigo_cohorte', $cohorte->codigo_cohorte)->get(); // Obtener materias asociadas a la cohorte

        return view('administrador.detallescohortes', compact('cohorte', 'materias'));
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
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'nro_de_cohorte' => [
                'required', 'integer', 'min:1',
                Rule::unique('cohorte', 'nro_de_cohorte')
                    ->ignore($cohorte->codigo_cohorte, 'codigo_cohorte')
                    ->where('codigo_mencion', $request->codigo_mencion),
            ],
        ]);

        $mencion = Mencion::findOrFail($datos['codigo_mencion']);

        $cohorte->codigo_cohorte = $datos['codigo_cohorte'];
        $cohorte->codigo_mencion = $mencion->id_mencion;
        $cohorte->codigo_postgrado = $mencion->id_postgrado; // espejo derivado de la mención
        $cohorte->fecha_inicio = $datos['fecha_inicio'];
        $cohorte->fecha_fin = $datos['fecha_fin'];
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
     */
    private function reglaMencionPropia(): Rule
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
