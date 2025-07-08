<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user=Auth::user();
        return view('estudiante.index', compact('user'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        return view('administrador.registroestudiante', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user=Auth::user();
        $estudiante= new Estudiante();
        $estudiante->cedula=$request->cedula;
        $estudiante->nombre=$request->nombre;
        $estudiante->apellido=$request->apellido;
        $estudiante->correo=$request->correo;
        $estudiante->carrera=$request->carrera;
        $estudiante->especialidad=$request->especialidad;
        $estudiante->edad=$request->edad;
        $estudiante->save();
        $estudiantes=Estudiante::all();
        return view('administrador.gestionestudiantes', compact('user', 'estudiantes'));
    }

    public function list()
    {
        $estudiantes=Estudiante::all();
        return view('administrador.gestionestudiantes',compact('estudiantes'));
    }
    
    /**
     * Display the specified resource.
     */
    public function showPerfil()
    {
        $user = Auth::user();
        return view('estudiante.perfil_estudiante', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estudiante $estudiante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Estudiante $estudiante)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estudiante $estudiante)
    {
        //
    }
}
