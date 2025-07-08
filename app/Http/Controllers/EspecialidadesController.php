<?php

namespace App\Http\Controllers;

use App\Models\Especialidades;
use Illuminate\Http\Request;

class EspecialidadesController extends Controller
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
        $especialidad=new Especialidades();
        $especialidad->codigo_especialidad = $request->codigo;
        $especialidad->id_carrera= $request->carrera_id;
        $especialidad->nombre = $request->nombre;
        $especialidad->save();
        return redirect()->route('administrador.creacion.index')->with('success', 'Especialidad registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Especialidades $especialidades)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Especialidades $especialidades)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Especialidades $especialidades)
    {
        $especialidades->codigo_especialidad = $request->codigo_especialidad;
        $especialidades->nombre = $request->nombre;
        $especialidades->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Especialidades $especialidades)
    {
        //
    }
}
