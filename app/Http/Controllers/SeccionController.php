<?php

namespace App\Http\Controllers;

use App\Models\Materias;
use App\Models\Profesor;
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
    public function create(string $id)
    {
        $profesores = Profesor::all();
        $materia = Materias::find($id);
        return view('administrador.registroseccion', compact('materia', 'profesores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $seccion = new Seccion();
        $seccion->nro_seccion = $request->nro_seccion;
        $seccion->codigo_materia = $request->codigo_materia;
        $seccion->hora_inicio = $request->hora_inicio;
        $seccion->hora_fin = $request->hora_fin;
        $seccion->cedula_docente = $request->cedula_docente;
        $seccion->aula = $request->aula;
        $seccion->cupo_maximo = $request->cupo_maximo;
        $seccion->cupo_actual = $request->cupo_actual ?? 0;
        $seccion->save();
        return redirect()->route('administrador.gestionmaterias.showdetalles', $request->codigo_materia)
            ->with('success', 'Sección creada exitosamente.');
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
    public function edit(string $id)
    {
        $seccion = Seccion::find($id);
        return view('administrador.editarseccion', compact('seccion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $seccion = Seccion::find($id);
        $seccion->nro_seccion = $request->nro_seccion;
        $seccion->codigo_materia = $request->codigo_materia;
        $seccion->hora_inicio = $request->hora_inicio;
        $seccion->hora_fin = $request->hora_fin;
        $seccion->cedula_docente = $request->cedula_docente;
        $seccion->aula = $request->aula;
        $seccion->cupo_maximo = $request->cupo_maximo;
        $seccion->cupo_actual = $request->cupo_actual ?? 0;
        $seccion->save();
        return redirect()->route('administrador.gestionmaterias.showdetalles', $request->codigo_materia)
            ->with('success', 'Sección actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $seccion = Seccion::find($id);
        $seccion->delete();
        return redirect()->route('administrador.gestionmaterias.showdetalles', $seccion->codigo_materia)
            ->with('success', 'Sección eliminada exitosamente.');
    }
}
