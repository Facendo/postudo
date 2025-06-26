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
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user=Auth::user();
        $estudiantes= new Estudiante();
        $estudiantes->cedula=$request->cedula;
        $estudiantes->nombre=$request->name;
        $estudiantes->apellido=$request->apellido;
        $estudiantes->correo=$request->correo;
        $estudiantes->carrera=$request->carrera;
        $estudiantes->especialidad=$request->especialidad;
        $estudiantes->edad=$request->edad;
      
        
        $estudiantes->save();
        return view('administrador.gestionestudiantes', compact('user'));
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
