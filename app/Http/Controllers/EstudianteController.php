<?php

namespace App\Http\Controllers;

use App\Models\Asunto;
use App\Models\Carrera;
use App\Models\Especialidades;
use App\Models\Estudiante;
use App\Models\Pagos;
use App\Models\Postgrado;
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
        $user=Auth::user();
        return view('estudiante.index', compact('user'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $carreras = Carrera::all();
        $especialidades = Especialidades::all();
        return view('administrador.registroestudiante', compact('user', 'carreras', 'especialidades'));
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
        $estudiante->especialidad="No inscrita"; // Default value
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
        return view('administrador.editarestudiante', compact('estudiante'));
    }

    
    /**
     * Update the specified resource in storage.
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

    public function inscripcion()
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('cedula', $user->cedula)->first();
        $carrera = Carrera::where('nombre', $estudiante->carrera)->first();
        $especialidades = Especialidades::where('id_carrera', $carrera->id_carrera)->get();
        $postgrados = Postgrado::where('codigo_especialidad', $estudiante->especialidad)->get();
        $pagos = Pagos::where('cedula', $user->cedula)->first()->get();

        foreach ($pagos as $item) {
            if ($item->cedula == $user->cedula && $item->asunto == "Inscripción") {
                if ($item->estado == "Pendiente") {
                    $verificacion_pago = false;
                } else {
                    $verificacion_pago = true;
                }
            }
        }
        if($verificacion_pago) {
            return view('estudiante.inscripcion', compact('user', 'carrera', 'especialidades', 'postgrados'));
        }
        else{
            return redirect()->route('estudiante.index')->with('error', 'No se ha procesado el pago de inscripción.');
        }
    }
        
    
    public function update(Request $request, Estudiante $estudiante)
    {
        $estudiante->cedula=$request->cedula;
        $estudiante->nombre=$request->nombre;
        $estudiante->apellido=$request->apellido;
        $estudiante->correo=$request->correo;
        $estudiante->carrera=$request->carrera;
        $estudiante->especialidad=$request->especialidad; 
        $estudiante->edad=$request->edad;
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
