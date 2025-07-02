<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use Illuminate\Http\Request;

class EvaluacionController extends Controller
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
        $evaluacion = new Evaluacion();
        $evaluacion->id_evaluacion = $request->id_evaluacion;
        $evaluacion->Codigo_materia = $request->Codigo_materia;
        $evaluacion->titulo = $request->titulo;
        $evaluacion->porcentaje = $request->porcentaje;
        $evaluacion->metodologia = $request->metodologia;
        $evaluacion->nota = $request->nota;
        $evaluacion->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Evaluacion $evaluacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evaluacion $evaluacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evaluacion $evaluacion)
    {
        $evaluacion->id_evaluacion = $request->id_evaluacion;
        $evaluacion->Codigo_materia = $request->Codigo_materia;
        $evaluacion->titulo = $request->titulo;
        $evaluacion->porcentaje = $request->porcentaje;
        $evaluacion->metodologia = $request->metodologia;
        $evaluacion->nota = $request->nota;
        $evaluacion->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluacion $evaluacion)
    {
        //
    }
}
