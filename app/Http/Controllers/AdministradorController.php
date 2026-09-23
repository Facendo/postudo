<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Carrera;
use App\Models\Area;
use App\Models\Especialidades;
use App\Models\Estudiante;
use App\Models\Profesor;
use App\Models\Cohorte;
use App\Models\Materias;



class AdministradorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasaData = $this->getTasaData();
        return view('administrador.index', ['tasaCambio' => $tasaData]);
    }

    /**
     * Guarda la tasa de cambio en storage.
     */
    public function saveTasaCambio(Request $request)
    {
        $request->validate([
            'tasa' => 'required|numeric|min:0',
        ]);

        $data = [
            'valor' => $request->input('tasa'),
            'fecha' => now()->format('d/m/Y'),
            'hora'  => now()->format('H:i'),
        ];

        Storage::put('tasa_cambio.json', json_encode($data));

        return response()->json([
            'success' => true,
            'tasa'    => $data,
        ]);
    }

    /**
     * Lee la tasa de cambio almacenada.
     */
    private function getTasaData(): array
    {
        if (Storage::exists('tasa_cambio.json')) {
            $json = Storage::get('tasa_cambio.json');
            return json_decode($json, true) ?? [];
        }
        return [];
    }
    
    public function registroEstudiante()
    {
        return view('administrador.registroestudiante',compact('carreras', 'especialidades'));
    }

    public function creacionArea()
    {
        $areas= Area::all();
        $especialidades = Especialidades::all();
        $carreras = Carrera::all();
        return view('administrador.creacionace', [
            'area' => $areas,
            'carrera' => $carreras,
            'especialidad' => $especialidades,
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Administrador $administrador)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Administrador $administrador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Administrador $administrador)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Administrador $administrador)
    {
        //
    }
}
