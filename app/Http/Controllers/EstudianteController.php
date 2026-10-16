<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Cohorte;
use App\Models\Estudiante;
use App\Models\Materias;
use App\Models\Mencion;
use App\Models\Pagos;
use App\Models\Postgrado;
use App\Models\Seccion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        return view('estudiante.index', compact('user'));

    }

    /**
     * Formulario de registro: cascade Área -> Postgrado -> Mención.
     */
    public function create()
    {
        $user = Auth::user();
        $areas = Area::orderBy('nombre')->get();
        $postgrados = Postgrado::orderBy('nombre')->get();
        $menciones = Mencion::with('postgrado')->get();

        return view('administrador.registroestudiante', compact('user', 'areas', 'postgrados', 'menciones'));
    }

    public function mostrarhorarioacademico()
    {
        $user = Auth::user();
        $horario = $user->horario; // Asumiendo que el modelo Estudiante tiene una relación con Horario

        return view('estudiante.horarioacademico', compact('horario'));
    }

    public function mostrarhistorialacademico()
    {
        $user = Auth::user();
        $historial = $user->historial; // Asumiendo que el modelo Estudiante tiene una relación con Historial

        return view('estudiante.historialacademico', compact('historial'));
    }

    /**
     * Guarda un estudiante nuevo asociado a una mención.
     * Los textos 'carrera' y 'especialidad' quedan como espejo del postgrado
     * y la mención elegidos (se conservan para los listados).
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'cedula' => 'required|string|max:255|unique:estudiantes,cedula',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'edad' => 'required|integer|min:15|max:99',
            'codigo_mencion' => 'required|integer|exists:mencion,id_mencion',
        ]);

        $mencion = Mencion::with('postgrado')->findOrFail($request->codigo_mencion);

        $estudiante = new Estudiante;
        $estudiante->cedula = $request->cedula;
        $estudiante->nombre = $request->nombre;
        $estudiante->apellido = $request->apellido;
        $estudiante->correo = $request->correo;
        $estudiante->codigo_mencion = $mencion->id_mencion;
        $estudiante->carrera = $mencion->postgrado->nombre;
        $estudiante->especialidad = $mencion->nombre;
        $estudiante->edad = $request->edad;
        $estudiante->save();

        $estudiantes = Estudiante::all();

        return view('administrador.gestionestudiantes', compact('user', 'estudiantes'));
    }

    public function list()
    {
        $estudiantes = Estudiante::all();

        return view('administrador.gestionestudiantes', compact('estudiantes'));
    }

    /**
     * Listado de estudiantes filtrado por postgrado (a través de su mención).
     * Los estudiantes sin mención asignada aparecen aparte, en
     * "Sin postgrado asignado": no se modifica a ningún estudiante existente.
     */
    public function listByPostgrado(Request $request)
    {
        $postgrados = Postgrado::orderBy('nombre')->get();
        $selectedPostgrado = $request->query('postgrado');

        $estudiantes = collect();
        $selectedPostgradoNombre = null;

        if ($selectedPostgrado) {
            $idsMenciones = Mencion::where('id_postgrado', $selectedPostgrado)->pluck('id_mencion');
            $estudiantes = Estudiante::with('mencion')->whereIn('codigo_mencion', $idsMenciones)->get();
            $selectedPostgradoNombre = Postgrado::find($selectedPostgrado)?->nombre ?? $selectedPostgrado;
        }

        $sinPostgrado = Estudiante::whereNull('codigo_mencion')->orderBy('apellido')->get();

        return view('administrador.estudiantesporpostgrado', compact(
            'postgrados',
            'selectedPostgrado',
            'selectedPostgradoNombre',
            'estudiantes',
            'sinPostgrado'
        ));
    }

    public function editPerfil()
    {
        $user = Auth::user();

        return view('estudiante.edicion_perfil', compact('user'));
    }

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
        $areas = Area::orderBy('nombre')->get();
        $postgrados = Postgrado::orderBy('nombre')->get();
        $menciones = Mencion::with('postgrado')->get();

        return view('administrador.editarestudiante', compact('estudiante', 'areas', 'postgrados', 'menciones'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update_Perfil_estudiante(Request $request)
    {
        $use = Auth::user();
        $user = User::find($use->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->cedula = $request->cedula;
        $user->save();

        return redirect()->route('estudiante.perfil')->with('success', 'Perfil actualizado exitosamente.');
    }

    /**
     * Oferta académica para inscribirse.
     *
     * Si el estudiante tiene mención asignada solo ve el postgrado de esa
     * mención; si no tiene (estudiantes antiguos) ve todos los postgrados.
     */
    public function inscripcion()
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('cedula', $user->cedula)->first();

        $mencion = $estudiante && $estudiante->codigo_mencion
            ? Mencion::find($estudiante->codigo_mencion)
            : null;

        $postgrados = $mencion
            ? Postgrado::where('id_postgrado', $mencion->id_postgrado)->get()
            : Postgrado::orderBy('nombre')->get();

        $cohortes = Cohorte::whereIn('codigo_postgrado', $postgrados->pluck('id_postgrado'))->get();
        $materias = Materias::whereIn('codigo_cohorte', $cohortes->pluck('codigo_cohorte'))->get();
        $secciones = Seccion::whereIn('codigo_materia', $materias->pluck('codigo_materia'))->get();

        // Se requiere un pago de inscripción que no esté pendiente.
        $pagos = Pagos::where('cedula', $user->cedula)->get();
        $verificacion_pago = $pagos->contains(
            fn ($pago) => $pago->asunto === 'Inscripción' && $pago->estado !== 'Pendiente'
        );

        if (! $verificacion_pago) {
            return redirect()->route('estudiante.index')->with('error', 'No se ha procesado el pago de inscripción.');
        }

        return view('estudiante.inscripcion', compact('user', 'postgrados', 'cohortes', 'materias', 'secciones'));
    }

    public function update(Request $request, Estudiante $estudiante)
    {
        $request->validate([
            'cedula' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'edad' => 'required|integer|min:15|max:99',
            'codigo_mencion' => 'nullable|integer|exists:mencion,id_mencion',
        ]);

        $estudiante->cedula = $request->cedula;
        $estudiante->nombre = $request->nombre;
        $estudiante->apellido = $request->apellido;
        $estudiante->correo = $request->correo;
        $estudiante->edad = $request->edad;

        // Solo se cambia la asociación (y los textos espejo) si se elige una
        // mención: así los estudiantes antiguos conservan sus datos.
        if ($request->filled('codigo_mencion')) {
            $mencion = Mencion::with('postgrado')->findOrFail($request->codigo_mencion);
            $estudiante->codigo_mencion = $mencion->id_mencion;
            $estudiante->carrera = $mencion->postgrado->nombre;
            $estudiante->especialidad = $mencion->nombre;
        }

        $estudiante->save();

        return redirect()->route('administrador.gestionestudiantes')->with('success', 'Estudiante actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estudiante $estudiante)
    {
        $estudiante->delete();

        return redirect()->route('administrador.gestionestudiantes')->with('success', 'Estudiante eliminado exitosamente.');
    }
}
