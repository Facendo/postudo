<?php

namespace App\Http\Controllers;

use App\Models\Seccion;
use Illuminate\Http\Request;

class SeccionController extends Controller
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
        $seccion = new Seccion();
        $seccion->nro_seccion = $request->nro_seccion;
        $seccion->hora_inicio = $request->hora_inicio;
        $seccion->hora_fin = $request->hora_fin;
        $seccion->aula = $request->aula;
        $seccion->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Seccion $seccion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seccion $seccion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seccion $seccion)
    {
        $seccion->nro_seccion = $request->nro_seccion;
        $seccion->hora_inicio = $request->hora_inicio;
        $seccion->hora_fin = $request->hora_fin;
        $seccion->aula = $request->aula;
        $seccion->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seccion $seccion)
    {
        $seccion->delete();
    }
}
