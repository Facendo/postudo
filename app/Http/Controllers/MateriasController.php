<?php

namespace App\Http\Controllers;

use App\Models\Materias;
use Illuminate\Http\Request;
use App\Models\Especialidades;

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
    public function create()
    {
        $materias = Materias::all();
        $especialidades = Especialidades::all();
        return view('administrador.registromateria', compact('especialidades', 'materias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $materia = new Materias();
        $materia->codigo_materia = $request->codigo_materia;
        $materia->nombre = $request->nombre;
        $materia->id_especialidad = $request->codigo_especialidad;
        $materia->prelacion = $request->prelacion ?? 0; // Valor por defecto si es null
        $materia->save();

        return redirect()->route('administrador.gestionmaterias')->with('success', 'Materia registrada exitosamente.');
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
    public function edit($codigo_materia)
    {
        $materia = Materias::where('codigo_materia', $codigo_materia)->firstOrFail();
        // ...otras variables si necesitas...
        return view('administrador.editarmateria', compact('materia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Materias $materia)
    {
        
        $request->validate([
            'codigo_materia' => 'required|integer',
            'nombre' => 'required|string',
            'carrera' => 'required|string',
            'codigo_especialidad' => 'required|string',
            'nro_seccion' => 'required|integer',
            'prelacion' => 'nullable|integer', // Hacerlo nullable si es opcional
        ]);

        // Actualiza el registro existente
        $materia->update([
            'codigo_materia' => $request->codigo_materia,
            'nombre' => $request->nombre,
            'nombre_carrera' => $request->carrera,
            'nombre_especialidad' => $request->codigo_especialidad,
            'nro_seccion' => $request->nro_seccion,
            'prelacion' => $request->prelacion ?? null, // Usar null si no se proporciona
        ]);

        return redirect()->route('administrador.gestionmaterias')
            ->with('success', 'Materia actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Materias $materia)
    {
        $materia->delete();
        return redirect()->route('administrador.gestionmaterias')->with('success', 'Materia eliminada exitosamente.');
    }
}
