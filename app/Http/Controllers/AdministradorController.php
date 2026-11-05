<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use App\Models\Area;
use App\Services\TasaCambioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdministradorController extends Controller
{
    public function __construct(private TasaCambioService $tasaService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fallback perezoso: si la tasa no es la del día, intenta sincronizarla con el BCV.
        $this->tasaService->asegurarTasaAlDia();

        $tasaData = $this->tasaService->obtenerTasaActual();

        return view('administrador.index', ['tasaCambio' => $tasaData]);
    }

    /**
     * Guarda la tasa de cambio capturada a mano (respaldo de emergencia).
     */
    public function saveTasaCambio(Request $request)
    {
        $request->validate([
            'tasa' => 'required|numeric|min:0',
        ]);

        $data = $this->tasaService->guardarManual((float) $request->input('tasa'));

        return response()->json([
            'success' => true,
            'tasa' => $data,
        ]);
    }

    /**
     * Fuerza la sincronización de la tasa con el BCV (botón del panel).
     */
    public function sincronizarTasaBcv()
    {
        try {
            $data = $this->tasaService->actualizarDesdeBcv();
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo sincronizar con el BCV: '.$e->getMessage(),
            ], 502);
        }

        return response()->json([
            'success' => true,
            'tasa' => $data,
        ]);
    }

    public function creacionArea()
    {
        // El árbol académico quedó en Área -> Postgrado -> Mención, así que
        // esta página solo gestiona áreas (con sus postgrados como detalle).
        return view('administrador.gestionareas', [
            'area' => Area::with('postgrados.menciones')->orderBy('nombre')->get(),
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
