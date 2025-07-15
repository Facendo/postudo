<?php

namespace App\Http\Controllers;

use App\Models\Postgrado;
use App\Models\Especialidades;
use App\Models\Area; // Se importa el nuevo modelo Area
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
      return view('administrador.registropostrado');
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
        $postgrado->save();

        $especialidad = Especialidades::find($request->codigo_especialidad);
        if (!$especialidad) {
            return back()->withInput()
                ->with('error', 'La especialidad seleccionada no existe');
        }

        return redirect()->route('administrador.gestion_postgrado')->with('success', 'Postgrado registrado exitosamente.');
    }

    /**
     * Muestra los detalles de un postgrado específico.
     */
    public function show(Postgrado $postgrado)
    {

    }

    /**
     * Muestra el formulario para editar un postgrado específico.
     */
    public function edit(Postgrado $postgrado)
    {
       return view('administrador.editarpostgrado', compact('postgrado'));
    }

    /**
     * Actualiza un postgrado específico en la base de datos.
     */
    public function update(Request $request, Postgrado $postgrado)
    {
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
    public function destroy(Postgrado $postgrado)
    {
        $postgrado->delete();
        return redirect()->route('administrador.gestion_postgrado')->with('success', 'Postgrado eliminado exitosamente.');
    }
}
