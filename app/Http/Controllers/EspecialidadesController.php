<?php

namespace App\Http\Controllers;

use App\Models\Especialidades;
use Illuminate\Http\Request;

class EspecialidadesController extends Controller
{
    public function index()
    {
        //
    }

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

    public function show(Especialidades $especialidades)
    {
        //
    }

    public function edit(Especialidades $especialidades)
    {
        //
    }

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
