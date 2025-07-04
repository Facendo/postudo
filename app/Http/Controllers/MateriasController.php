<?php

namespace App\Http\Controllers;

use App\Models\Materias;
use Illuminate\Http\Request;

class MateriasController extends Controller
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
        $materia= new Materias();
        $materia->Codigo_materia = $request->Codigo_materia;
        $materia->codigo_cohorte = $request->codigo_cohorte;
        $materia->nro_seccion = $request->nro_seccion;
        $materia->Nombre = $request->Nombre;
        $materia->Prelacion = $request->Prelacion;
        $materia->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Materias $materias)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Materias $materias)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Materias $materias)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Materias $materias)
    {
        //
    }
}
