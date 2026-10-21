<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:255|unique:area,codigo',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        Area::create($request->all());

        return redirect()->route('administrador.creacion.index')->with('success', 'Area Creada Correctamente.');
    }

    public function show(Area $area)
    {
        //
    }

    /**
     * Devuelve en JSON todo lo que se borrará en cascada si se elimina el área.
     * Lo usa el panel antes de mostrar la confirmación al usuario.
     */
    public function impacto(Area $area)
    {
        $idsPostgrados = DB::table('postgrado')
            ->where('codigo_area', $area->codigo)
            ->pluck('id_postgrado');

        $idsCohortes = DB::table('cohorte')
            ->whereIn('codigo_postgrado', $idsPostgrados)
            ->pluck('codigo_cohorte');

        return response()->json([
            'postgrados' => $idsPostgrados->count(),
            'menciones' => DB::table('mencion')->whereIn('id_postgrado', $idsPostgrados)->count(),
            'cohortes' => $idsCohortes->count(),
        ]);
    }

    public function edit(Area $area)
    {
        //
    }

    public function update(Request $request, Area $area)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        $area->update($request->all());

        return redirect()->route('administrador.creacion.index')->with('success', 'Área actualizada correctamente');

    }

    public function destroy(Area $area)
    {
        try {
            $area->delete();

            return redirect()->route('administrador.creacion.index')
                ->with('success', 'Área eliminada correctamente, junto con sus postgrados y menciones.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'No se pudo eliminar el área.');
        }

    }
}
