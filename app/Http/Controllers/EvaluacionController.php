<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $evaluaciones = []; // O tu consulta real: Evaluacion::all() o filtrada
        return view('profesor.gestionevaluacion', compact('evaluaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Show the form for creating a new evaluation
        return view('profesor.registroevaluacion');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $evaluacion = new Evaluacion();
        $evaluacion->codigo_materia = $request->codigo_materia;
        $evaluacion->codigo_seccion = $request->codigo_seccion;
        $evaluacion->titulo = $request->titulo;
        $evaluacion->porcentaje = $request->porcentaje;
        $evaluacion->metodologia = $request->metodologia;
        $evaluacion->fecha = $request->fecha; // Asegúrate de que este campo exista en tu modelo
        $evaluacion->nota = $request->nota;
        $evaluacion->save();
        return redirect()->route('profesor.gestionevaluacion.index')->with('success', 'Evaluación registrada exitosamente.');
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
        return redirect()->route('profesor.gestionevaluacion.index')->with('success', 'Evaluación actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluacion $evaluacion)
    {
        $evaluacion->delete();
        return redirect()->route('profesor.gestionevaluacion.index')->with('success', 'Evaluación eliminada exitosamente.');
    }
}
