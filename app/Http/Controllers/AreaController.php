<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(Area $area)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Area $area){
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        $area->update($request->all());
        return redirect()->route('administrador.creacion.index')->with('success', 'Área actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
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
