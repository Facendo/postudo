<?php

namespace App\Http\Controllers;

use App\Models\Profesor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfesorController extends Controller
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
        $profesor = new Profesor();
        $profesor->cedula = $request->cedula;
        $profesor->nro_seccion = $request->nro_seccion;
        $profesor->nombre = $request->nombre;
        $profesor->apellido = $request->apellido;
        $profesor->telefono = $request->telefono;
        $profesor->correo = $request->correo;
        $profesor->edad = $request->edad;
        $profesor->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Profesor $profesor)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profesor $profesor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profesor $profesor)
    {
        $profesor->cedula = $request->cedula;
        $profesor->nro_seccion = $request->nro_seccion;
        $profesor->nombre = $request->nombre;
        $profesor->apellido = $request->apellido;
        $profesor->telefono = $request->telefono;
        $profesor->correo = $request->correo;
        $profesor->edad = $request->edad;
        $profesor->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profesor $profesor)
    {
        //
    }
}
