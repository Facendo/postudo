<?php

namespace App\Http\Controllers;

use App\Models\CoordinadorPostgrado;
use App\Models\Postgrado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CoordinadorPostgradoController extends Controller
{
    /**
     * Listado de coordinadores de postgrado y su postgrado asignado.
     * (Edición y borrado quedan pendientes: ver MEMORY.)
     */
    public function index()
    {
        $coordinadores = CoordinadorPostgrado::with('postgrados')->get();

        return view('coordinador.gestioncoordinadorespostgrado', compact('coordinadores'));
    }

    /**
     * Formulario de creación (solo coordinador general). Los postgrados ya
     * asignados salen deshabilitados: un postgrado solo tiene UN coordinador.
     */
    public function create()
    {
        $postgrados = Postgrado::with('coordinadores')->orderBy('nombre')->get();

        return view('coordinador.registrocoordinadorpostgrado', compact('postgrados'));
    }

    /**
     * Guarda al coordinador y le asigna sus postgrados. NO crea el usuario:
     * la persona se registra después y el rol se deduce por la cédula.
     */
    public function store(Request $request)
    {
        // Postgrados que ya tienen coordinador: no se pueden volver a asignar.
        $ocupados = DB::table('asignacion_coordinador_postgrado')->pluck('codigo_postgrado');

        $datos = $request->validate([
            'cedula' => ['required', 'string', 'max:255', 'unique:coordinador_postgrado,cedula'],
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'correo' => ['required', 'email', 'unique:coordinador_postgrado,correo'],
            'postgrados' => ['required', 'array', 'min:1'],
            'postgrados.*' => [
                'string',
                'max:255',
                'exists:postgrado,id_postgrado',
                // Un postgrado solo puede tener UN coordinador asignado.
                Rule::notIn($ocupados),
            ],
        ]);

        $coordinador = new CoordinadorPostgrado;
        $coordinador->cedula = $datos['cedula'];
        $coordinador->nombre = $datos['nombre'];
        $coordinador->apellido = $datos['apellido'];
        $coordinador->correo = $datos['correo'];
        $coordinador->save();

        $coordinador->postgrados()->attach($datos['postgrados']);

        return redirect()
            ->route('coordinador.coordinador_postgrado.index')
            ->with('success', 'Coordinador de postgrado registrado. Ya puede crear su cuenta con su correo.');
    }

    /**
     * Panel propio del rol coordinador_postgrado: sus postgrados y cohortes
     * están en las gestiones existentes, filtradas por el controlador.
     */
    public function panel()
    {
        return view('coordinador_postgrado.index');
    }
}
