<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Carrera;
use Illuminate\Http\Request;

class CarreraController extends Controller
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
        // Validate the request data
        $request->validate([
            'id_carrera' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
        ]);
        $carrera = new Carrera();
        $carrera->id_carrera = $request->id_carrera;
        $carrera->codigo_Area = $request->id_area;
        $carrera->nombre = $request->nombre;
        $carrera->save();
        return redirect()->route('administrador.creacion.index')->with('success', 'Carrera creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Carrera $carrera)
    {
        //
    }

    public function edit(Carrera $carrera)
    {
        //
    }

    public function update(Request $request, Carrera $carrera){
    $request->validate([
        'nombre' => 'required|string|max:255',
        'id_area' => 'required|exists:areas,codigo',
    ]);

    $carrera->update([
        'nombre' => $request->nombre,
        'codigo_Area' => $request->id_area
    ]);
    return redirect()->route('administrador.creacion.index')->with('success', 'Carrera actualizada correctamente');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Carrera $carrera){
        try {
            $carrera->delete();
            return redirect()->route('administrador.creacion.index')
                ->with('success', 'Carrera eliminada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'No se pudo eliminar la carrera. Verifica que no tenga especialidades asociadas.');
        }
    }
}
