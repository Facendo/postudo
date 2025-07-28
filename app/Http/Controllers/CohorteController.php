<?php

namespace App\Http\Controllers;

use App\Models\Cohorte;
use App\Models\Postgrado;
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
    public function create(string $id)
    {
        $postgrado = Postgrado::find($id); // Asegúrate de que el postgrado existe
        return view('administrador.registrocohorte', compact('postgrado'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $postgrado = Postgrado::findOrFail($request->codigo_postgrado); // Validar que el postgrado existe
        $cohorte = new Cohorte();
        $cohorte->codigo_cohorte = $request->codigo_cohorte;
        $cohorte->fecha_inicio = $request->fecha_inicio;
        $cohorte->fecha_fin = $request->fecha_fin;
        $cohorte->nro_de_cohorte = $request->nro_de_cohorte;
        $cohorte->codigo_postgrado = $request->codigo_postgrado; // Ensure this matches your migration
        $cohorte->save();
        return redirect()->route('administrador.gestion_postgrado.showdetalles', $postgrado->id_postgrado)->with('success', 'Cohorte registrada correctamente.', compact('postgrado'));
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
    public function edit(string $id)
    {
        $cohorte = Cohorte::find($id); // Asegúrate de que la cohorte existe
        return view('administrador.editarcohorte', compact('cohorte'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cohorte = Cohorte::find($id);
        $cohorte->codigo_cohorte = $request->codigo_cohorte;
        $cohorte->fecha_inicio = $request->fecha_inicio;
        $cohorte->fecha_fin = $request->fecha_fin;
        $cohorte->nro_de_cohorte = $request->nro_de_cohorte; // Ensure this matches your migration
        $cohorte->codigo_postgrado = $request->codigo_postgrado; // Ensure this matches your migration
        $cohorte->save();
        return redirect()->route('administrador.gestion_postgrado.showdetalles', $cohorte->codigo_postgrado)->with('success', 'Cohorte actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cohorte = Cohorte::find($id);
        $cohorte->delete();
        return redirect()->route('administrador.gestioncohorte')->with('success', 'Cohorte eliminada correctamente.');
    }
}
