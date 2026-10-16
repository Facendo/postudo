<?php

namespace App\Http\Controllers;

use App\Models\Mencion;
use App\Models\Postgrado;
use Illuminate\Http\Request;

class MencionController extends Controller
{
    /**
     * Registra una nueva mención dentro de un postgrado.
     */
    public function store(Request $request, Postgrado $postgrado)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $postgrado->menciones()->create([
            'nombre' => $request->nombre,
        ]);

        return redirect()
            ->route('administrador.gestion_postgrado.showdetalles', $postgrado->id_postgrado)
            ->with('success', 'Mención registrada exitosamente.');
    }

    /**
     * Actualiza el nombre de una mención.
     */
    public function update(Request $request, Mencion $mencion)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $mencion->update([
            'nombre' => $request->nombre,
        ]);

        return redirect()
            ->route('administrador.gestion_postgrado.showdetalles', $mencion->id_postgrado)
            ->with('success', 'Mención actualizada exitosamente.');
    }

    /**
     * Elimina una mención. Los estudiantes asociados quedan sin mención
     * (la FK 'codigo_mencion' es con onDelete("set null")), nunca se borran.
     */
    public function destroy(Mencion $mencion)
    {
        $idPostgrado = $mencion->id_postgrado;

        $mencion->delete();

        return redirect()
            ->route('administrador.gestion_postgrado.showdetalles', $idPostgrado)
            ->with('success', 'Mención eliminada. Los estudiantes que la tenían asignada quedaron sin mención.');
    }
}
