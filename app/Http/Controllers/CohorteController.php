<?php

namespace App\Http\Controllers;

use App\Models\Cohorte;
use Illuminate\Http\Request;

class CohorteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cohortes = Cohorte::all();
        return view('administrador.gestioncohorte', compact('cohortes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administrador.registrocohorte');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cohorte = new Cohorte();
        $cohorte->codigo_cohorte = $request->codigo_cohorte;
        $cohorte->fecha_inicio = $request->fecha_inicio;
        $cohorte->fecha_fin = $request->fecha_fin;
        $cohorte->nro_de_cohorte = $request->nro_de_cohorte;
        $cohorte->codigo_postgrado = $request->codigo_postgrado; // Ensure this matches your migration
        $cohorte->save();
        return redirect()->route('administrador.gestioncohorte')->with('success', 'Cohorte registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cohorte $cohorte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cohorte $cohorte)
    {
        return view('administrador.editarcohorte', compact('cohorte'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cohorte $cohorte)
    {
        $cohorte->codigo_cohorte = $request->codigo_cohorte;
        $cohorte->fecha_inicio = $request->fecha_inicio;
        $cohorte->fecha_fin = $request->fecha_fin;
        $cohorte->nro_de_cohorte = $request->nro_de_cohorte; // Ensure this matches your migration
        $cohorte->codigo_postgrado = $request->codigo_postgrado; // Ensure this matches your migration
        $cohorte->save();
        return redirect()->route('administrador.gestioncohorte')->with('success', 'Cohorte actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cohorte $cohorte)
    {
        $cohorte->delete();
        return redirect()->route('administrador.gestioncohorte')->with('success', 'Cohorte eliminada correctamente.');
    }
}
