<?php

namespace App\Http\Controllers;

use App\Models\Asunto;
use Illuminate\Http\Request;

class AsuntoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asuntos = Asunto::all();
        return view('asuntos.index', compact('asuntos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('asuntos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $asunto = new Asunto();
        $asunto->nombre = $request->input('nombre');
        $asunto->descripcion = $request->input('descripcion');
        $asunto->cedula_estudiante = $request->input('cedula_estudiante');
        $asunto->activo = $request->input('activo', true);
        $asunto->save();

        return redirect()->route('asuntos.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asunto $asunto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $asunto = Asunto::find($id);
        return view('asuntos.edit', compact('asunto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $asunto = Asunto::find($id);
        $asunto->nombre = $request->input('nombre');
        $asunto->descripcion = $request->input('descripcion');
        $asunto->cedula_estudiante = $request->input('cedula_estudiante');
        $asunto->activo = $request->input('activo', true);
        $asunto->save();

        return redirect()->route('asuntos.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $asunto = Asunto::find($id);
        $asunto->delete();

        return redirect()->route('asuntos.index');
    }
}
