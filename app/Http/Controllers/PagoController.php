<?php

namespace App\Http\Controllers;

use App\Models\Pagos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pagos=Pagos::all();
        return view('estudiante.pago',compact('pagos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $user=Auth::user();
        return view('estudiante.registropago',compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user=Auth::user();
        $pago= new Pagos();
        $pago->nombre=$user->name;
        $pago->cedula=$user->cedula;
        $pago->banco_emisor=$request->pago_emisor;
        $pago->referencia=$request->referencia;


    }

    /**
     * Display the specified resource.
     */
    public function show(Pagos $pagos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pagos $pagos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pagos $pagos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pagos $pagos)
    {
        //
    }
}
