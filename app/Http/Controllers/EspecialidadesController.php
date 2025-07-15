<?php

namespace App\Http\Controllers;

use App\Models\Especialidades;
use Illuminate\Http\Request;

class EspecialidadesController extends Controller
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
        $especialidad=new Especialidades();
        $especialidad->codigo_especialidad = $request->codigo;
        $especialidad->id_carrera= $request->carrera_id;
        $especialidad->nombre = $request->nombre;
        $especialidad->save();
        return redirect()->route('administrador.creacion.index')->with('success', 'Especialidad registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Especialidades $especialidades)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Especialidades $especialidades)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Especialidades $especialidad){
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:255',
            'carrera_id' => 'required|exists:carreras,id_carrera',
        ]);

        $especialidad->update([
            'nombre' => $request->nombre,
            'codigo_especialidad' => $request->codigo,
            'id_carrera' => $request->carrera_id
        ]);
        return back()->with('success', 'Especialidad actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Especialidades $especialidad){
        try {
            $especialidad->delete();
            return redirect()->back()
                ->with('success', 'Especialidad eliminada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'No se pudo eliminar la especialidad.');
        }
    }
}
