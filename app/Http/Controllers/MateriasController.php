<?php

namespace App\Http\Controllers;

use App\Models\Cohorte;
use App\Models\Materias;
use Illuminate\Http\Request;
use App\Models\Especialidades;
use App\Models\Seccion;

class MateriasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materias = Materias::all();
        $especialidades = Especialidades::all();
        return view('administrador.gestionmaterias', compact('materias', 'especialidades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
    {
        $cohorte = Cohorte::find($id);
        $materias = Materias::all();
        return view('administrador.registromateria', compact('cohorte', 'materias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $codigo_cohorte = $request->codigo_cohorte;
        $materia = new Materias();
        $materia->codigo_materia = $request->codigo_materia;
        $materia->nombre = $request->nombre;
        $materia->codigo_cohorte = $request->codigo_cohorte;
        $materia->prelacion = $request->prelacion ?? 0; // Valor por defecto si es null
        $materia->save();

        return redirect()->route('administrador.gestioncohorte.showdetalles', $codigo_cohorte)->with('success', 'Materia registrada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function showdetalles(string $id)
    {
        $materia= Materias::find($id);
        $secciones = Seccion::where('codigo_materia', $id)->get();
        return view('administrador.detallesmaterias', compact('materia', 'secciones'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $materia = Materias::where('codigo_materia', $id)->firstOrFail();
        // ...otras variables si necesitas...
        return view('administrador.editarmateria', compact('materia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $materia = Materias::where('codigo_materia', $id)->firstOrFail();
        $codigo_cohorte = $materia->codigo_cohorte;

        // Actualizar los campos de la materia
        $materia->codigo_materia = $request->codigo_materia;
        $materia->nombre = $request->nombre;
        $materia->prelacion = $request->prelacion ?? 0; // Valor por defecto si es null
        $materia->save();

        return redirect()->route('administrador.gestioncohorte.showdetalles', $codigo_cohorte)->with('success', 'Materia actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $materia = Materias::find($id);
        $codigo_cohorte = $materia->codigo_cohorte;
        $materia->delete();
        return redirect()->route('administrador.gestioncohorte.showdetalles', $codigo_cohorte)->with('success', 'Materia eliminada exitosamente.');
    }
}
