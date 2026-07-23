<?php

namespace App\Http\Controllers;

use App\Models\Profesor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Estudiante;
use App\Models\Evaluacion;

class ProfesorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profesores = Profesor::all();
        return view('administrador.gestionprofesor', compact('profesores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administrador.registroprofesor');
    }

    public function perfil()
    {
        $profesor = auth()->user();
        return view('profesor.perfil_profesor', compact('profesor'));
    }

    public function listadoestudiantes()
    {
        $estudiantes = Estudiante::all(); // O tu lógica de filtrado
        return view('profesor.listadoestudiantes', compact('estudiantes'));
    }

    public function gestionevaluacion()
    {
        $evaluaciones = Evaluacion::all(); // O tu lógica de filtrado
        return view('profesor.gestionevaluacion', compact('evaluaciones'));
    }

    public function gestionnotas()
    {
        $profesor = Auth::user();
        $secciones = $profesor->seccion; // Asumiendo que el modelo Profesor tiene una relación con Seccion
        return view('profesor.gestionnotas', compact('secciones'));
    }

    public function consultarhorario()
    {
        $profesor = Auth::user();
        $horario = $profesor->horario; // Asumiendo que el modelo Profesor tiene una relación con Horario
        return view('profesor.consultarhorario', compact('horario'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $profesor = new Profesor();
        $profesor->cedula = $request->cedula;
        $profesor->nombre = $request->nombre;
        $profesor->apellido = $request->apellido;
        $profesor->telefono = $request->telefono;
        $profesor->correo = $request->correo;
        $profesor->edad = $request->edad;
        $profesor->save();
        return redirect()->route('administrador.gestion_profesor')->with('success', 'Profesor registrado exitosamente.');
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
        return view('administrador.editarprofesor', compact('profesor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profesor $profesor)
    {
        $profesor->cedula = $request->cedula;
        $profesor->nombre = $request->nombre;
        $profesor->apellido = $request->apellido;
        $profesor->telefono = $request->telefono;
        $profesor->correo = $request->correo;
        $profesor->edad = $request->edad;
        $profesor->save();
        return redirect()->route('administrador.gestion_profesor')->with('success', 'Profesor actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profesor $profesor)
    {
        $profesor->delete();
        return redirect()->route('administrador.gestion_profesor')->with('success', 'Profesor eliminado exitosamente.');
    }
}
