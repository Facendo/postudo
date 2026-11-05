<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FiltrablePorCoordinador;
use App\Models\Mencion;
use App\Models\Postgrado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MencionController extends Controller
{
    use FiltrablePorCoordinador;

    /**
     * Registra una nueva mención dentro de un postgrado.
     */
    public function store(Request $request, Postgrado $postgrado)
    {
        // El coordinador de postgrado solo gestiona menciones de SUS postgrados.
        if (! $this->puedeGestionarPostgrado(Auth::user(), $postgrado->id_postgrado)) {
            return redirect()
                ->route('administrador.gestion_postgrado')
                ->with('error', 'Este postgrado no está asignado a tu cuenta.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $postgrado->menciones()->create([
            'nombre' => $request->nombre,
        ]);

        // back(): si se crea desde la lista de postgrados se vuelve a ella;
        // si se crea desde «Detalles del Postgrado» se vuelve al mismo sitio.
        return redirect()
            ->back()
            ->with('success', 'Mención registrada exitosamente.');
    }

    /**
     * Actualiza el nombre de una mención.
     */
    public function update(Request $request, Mencion $mencion)
    {
        // El coordinador de postgrado solo gestiona menciones de SUS postgrados.
        if (! $this->puedeGestionarPostgrado(Auth::user(), $mencion->id_postgrado)) {
            return redirect()
                ->route('administrador.gestion_postgrado')
                ->with('error', 'Esta mención no pertenece a tus postgrados.');
        }

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
     *
     * Si la mención tiene cohortes NO se puede eliminar (decisión del usuario
     * 2026-10-20): primero hay que eliminar o reasignar sus cohortes.
     */
    public function destroy(Mencion $mencion)
    {
        // El coordinador de postgrado solo gestiona menciones de SUS postgrados.
        if (! $this->puedeGestionarPostgrado(Auth::user(), $mencion->id_postgrado)) {
            return redirect()
                ->route('administrador.gestion_postgrado')
                ->with('error', 'Esta mención no pertenece a tus postgrados.');
        }

        $totalCohortes = $mencion->cohortes()->count();

        if ($totalCohortes > 0) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    "No se puede eliminar la mención \"{$mencion->nombre}\": tiene {$totalCohortes} cohorte(s) asignada(s). Elimínalas o cámbiales de mención primero."
                );
        }

        $mencion->delete();

        // back(): conserva la página desde la que se borró (lista de postgrados
        // o «Detalles del Postgrado»).
        return redirect()
            ->back()
            ->with('success', 'Mención eliminada. Los estudiantes que la tenían asignada quedaron sin mención.');
    }
}
