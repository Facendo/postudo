<?php

namespace App\Http\Controllers;

use App\Models\Cohorte;
use App\Models\Materias;
use App\Models\Mencion;
use App\Models\Postgrado;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CohorteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // eager-load mención y postgrado para pintar las columnas de la tabla.
        $cohortes = Cohorte::with(['mencion', 'postgrado'])->get();

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
        $postgradoSel = $postgrado ? Postgrado::findOrFail($postgrado) : null;
        $menciones = Mencion::with('postgrado')->orderBy('id_postgrado')->get();

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
            'codigo_mencion' => ['required', 'integer', Rule::exists('mencion', 'id_mencion')],
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
        $cohorte = Cohorte::find($id);
        $materias = Materias::where('codigo_cohorte', $cohorte->codigo_cohorte)->get(); // Obtener materias asociadas a la cohorte

        return view('administrador.detallescohortes', compact('cohorte', 'materias'));
    }

    /**
     * Formulario de edición: la mención también es obligatoria aquí (las
     * cohortes sin mención dejaron de existir con la limpieza del 2026-10-20).
     */
    public function edit(string $id)
    {
        $cohorte = Cohorte::with('mencion')->findOrFail($id);
        $menciones = Mencion::with('postgrado')->orderBy('id_postgrado')->get();

        return view('administrador.editarcohorte', compact('cohorte', 'menciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cohorte = Cohorte::findOrFail($id);

        $datos = $request->validate([
            'codigo_cohorte' => ['required', 'string', 'max:255', Rule::unique('cohorte', 'codigo_cohorte')->ignore($cohorte->codigo_cohorte)],
            'codigo_mencion' => ['required', 'integer', Rule::exists('mencion', 'id_mencion')],
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
        $codigo_postgrado = $cohorte->codigo_postgrado; // Guardar el código del postgrado antes de eliminar la cohorte
        $cohorte->delete();

        return redirect()->route('administrador.gestion_postgrado.showdetalles', $codigo_postgrado)->with('success', 'Cohorte eliminada correctamente.');
    }
}
