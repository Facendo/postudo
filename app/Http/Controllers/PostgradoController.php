<?php

namespace App\Http\Controllers;

use App\Models\Postgrado;
use App\Models\Especialidades;
use App\Models\Area; // Se importa el nuevo modelo Area
use App\Models\Cohorte;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostgradoController extends Controller
{
    /**
     * Muestra una lista de los postgrados.
     */
    public function index()
    {
        // Carga los postgrados con sus relaciones para optimizar consultas
        $postgrados = Postgrado::all();
        return view('administrador.gestionpostgrado', compact('postgrados'));
    }

    /**
     * Muestra el formulario para crear un nuevo postgrado.
     */
    public function create()
    {
        $especialidades = Especialidades::all(); // Obtiene todas las especialidades
        return view('administrador.registropostgrado', compact('especialidades'));
    }

    /**
     * Almacena un nuevo postgrado en la base de datos.
     */
    public function store(Request $request)
    {
        // Crea una nueva instancia del modelo Postgrado
        $postgrado = new Postgrado();

        // Asigna los atributos directamente desde el request
        $postgrado->Id_postgrado = $request->Id_postgrado;
        $postgrado->codigo_especialidad = $request->codigo_especialidad;
        $postgrado->nombre = $request->nombre;
        $postgrado->descripcion = $request->descripcion;
        $postgrado->duracion = $request->duracion;
        $postgrado->nro_cohortes = $request->nro_de_cohortes;
        $postgrado->save();

        return redirect()->route('administrador.gestion_postgrado')->with('success', 'Postgrado registrado exitosamente.');
    }

    /**
     * Muestra los detalles de un postgrado específico.
     */
    public function showdetalles(string $id)
    {
        $cohortes = Cohorte::where('codigo_postgrado', $id)->get(); // Obtiene las cohortes asociadas al postgrado
        $postgrado = Postgrado::find($id);
        return view('administrador.detallespostgrado', compact('postgrado', 'cohortes'));
    }

    /**
     * Muestra el formulario para editar un postgrado específico.
     */
    public function edit(string $id)
    {
        $postgrado = Postgrado::find($id);

        // Obtiene la especialidad para el formulario de edición
        $especialidad_ = Especialidades::find($postgrado->codigo_especialidad);
        $especialidades = Especialidades::all(); // Obtiene todas las especialidades para el select
       return view('administrador.editarpostgrado', compact('postgrado', 'especialidad_', 'especialidades'));
    }

    /**
     * Actualiza un postgrado específico en la base de datos.
     */
    public function update(Request $request, string $id)
    {
        $postgrado = Postgrado::find($id);
        $postgrado->Id_postgrado = $request->Id_postgrado;
        $postgrado->codigo_especialidad = $request->codigo_especialidad;
        $postgrado->nombre = $request->nombre;
        $postgrado->descripcion = $request->descripcion;
        $postgrado->duracion = $request->duracion;
        $postgrado->save();

        $especialidad = Especialidades::find($request->codigo_especialidad);
        if (!$especialidad) {
            return back()->withInput()
                ->with('error', 'La especialidad seleccionada no existe');
        }

        return redirect()->route('administrador.gestion_postgrado')->with('success', 'Postgrado actualizado exitosamente.');
    }

    /**
     * Elimina un postgrado específico de la base de datos.
     */
    public function destroy(string $id)
    {
        $postgrado = Postgrado::find($id);
        $postgrado->delete();
        return redirect()->route('administrador.gestion_postgrado')->with('success', 'Postgrado eliminado exitosamente.');
    }
}
