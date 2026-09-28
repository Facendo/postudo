<?php

namespace App\Http\Controllers;

use App\Models\CoordinadorGeneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Estudiante;
use App\Models\Profesor;
use App\Models\Especialidades;
use App\Models\Postgrado;
use App\Models\Cohorte;

class CoordinadorGeneralController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('coordinador.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administrador.registrocoordinador');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cedula' => 'required|string|unique:coordinador_general,cedula',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|email|unique:coordinador_general,correo',
        ]);

        $coordinador = new CoordinadorGeneral();
        $coordinador->cedula = $request->cedula;
        $coordinador->nombre = $request->nombre;
        $coordinador->apellido = $request->apellido;
        $coordinador->correo = $request->correo;
        $coordinador->save();

        return redirect()->route('administrador.index')->with('success', 'Coordinador General registrado exitosamente. Ya puede crear su cuenta con su correo.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CoordinadorGeneral $coordinador)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CoordinadorGeneral $coordinador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CoordinadorGeneral $coordinador)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CoordinadorGeneral $coordinador)
    {
        //
    }
}
