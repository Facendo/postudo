<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

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

    public function edit(Area $area)
    {
        //
    }

    public function update(Request $request, Area $area){
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        $area->update($request->all());
        return redirect()->route('administrador.creacion.index')->with('success', 'Área actualizada correctamente');

    }

    public function destroy(Area $area){
        try {
            $area->delete();
            return redirect()->route('administrador.creacion.index')
                ->with('success', 'Área eliminada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'No se pudo eliminar el área. Verifica que no tenga carreras asociadas.');
        }

    }
}
