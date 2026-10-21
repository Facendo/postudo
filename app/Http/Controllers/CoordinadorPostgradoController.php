<?php

namespace App\Http\Controllers;

use App\Models\CoordinadorPostgrado;
use App\Models\Postgrado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CoordinadorPostgradoController extends Controller
{
    /**
     * Listado de coordinadores de postgrado y su postgrado asignado.
     * (La edición queda pendiente: ver MEMORY.)
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
     * Elimina un coordinador de postgrado (solo coordinador general).
     *
     * Sus postgrados quedan «Sin coordinador»: la asignación se limpia sola
     * por la FK con cascade de asignacion_coordinador_postgrado, nunca a mano.
     * Si la persona ya creó cuenta y su rol es exactamente 'coordinador_postgrado',
     * también se borra su usuario y sus sesiones activas: renunció, sale del
     * sistema y deberá registrarse de nuevo si algún día vuelve a ser nombrado.
     */
    public function destroy(CoordinadorPostgrado $coordinador)
    {
        $postgradosLiberados = $coordinador->postgrados()->count();

        // La fila del pivote cae en cascada al borrar la fila del coordinador.
        $coordinador->delete();

        // La cuenta de usuario solo se elimina si su rol era EXCLUSIVAMENTE
        // coordinador de postgrado (si su cédula también está en otra tabla de
        // rol, su usuario se conserva con ese otro rol y no se toca nada).
        $usuario = User::where('cedula', $coordinador->cedula)->first();

        if ($usuario !== null && $usuario->rol === 'coordinador_postgrado') {
            // SESSION_DRIVER=database: cerrar también sus sesiones abiertas.
            DB::table('sessions')->where('user_id', $usuario->id)->delete();
            $usuario->delete();
        }

        $mensaje = $postgradosLiberados > 0
            ? "Coordinador eliminado. {$postgradosLiberados} postgrado(s) quedaron sin coordinador."
            : 'Coordinador de postgrado eliminado.';

        return redirect()
            ->route('coordinador.coordinador_postgrado.index')
            ->with('success', $mensaje);
    }

    /**
     * Panel propio del rol coordinador_postgrado: sus postgrados y cohortes
     * están en las gestiones existentes, filtradas por el controlador.
     * Si no tiene postgrados (p. ej. se le borraron todos), el panel avisa.
     */
    public function panel()
    {
        // '?->' defensivo: la fila puede faltar si el coordinador fue eliminado.
        $postgradosAsignados = Auth::user()->coordinadorPostgrado?->postgrados()->count() ?? 0;

        return view('coordinador_postgrado.index', compact('postgradosAsignados'));
    }
}
