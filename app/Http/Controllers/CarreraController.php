<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Carrera;
use Illuminate\Http\Request;

class CarreraController extends Controller
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Carrera $carrera)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Carrera $carrera)
    {
        $carrera->codigo_especialidad = $request->codigo_especialidad;
        $carrera->nombre = $request->nombre;
        $carrera->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Carrera $carrera)
    {
        //
    }
}
