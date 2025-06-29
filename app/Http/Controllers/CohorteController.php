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
        //
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
        $cohorte = new Cohorte();
        $cohorte->codigo_cohorte = $request->codigo_cohorte;
        $cohorte->fecha_inicio = $request->fecha_inicio;
        $cohorte->fecha_fin = $request->fecha_fin;
        $cohorte->nro_de_cohorte = $request->nro_de_cohorte; // Ensure this matches your migration
        $cohorte->save();
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
        //
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
        $cohorte->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cohorte $cohorte)
    {
        //
    }
}
