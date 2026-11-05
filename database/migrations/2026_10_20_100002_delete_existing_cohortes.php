<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Borra TODAS las cohortes existentes (decisión del usuario 2026-10-20):
     * son cohortes vacías de prueba, no reales, y él quiere que las cree el
     * coordinador desde cero. La cascada FK de 'cohorte' elimina también
     * materias -> secciones -> listado (inscripciones).
     *
     * 'evaluacion' NO tiene FK con 'materia', así que sus filas con materia
     * se limpian a mano después (queda la materia inexistente).
     */
    public function up(): void
    {
        // 1) Vaciar cohortes: la cascada FK (materia -> seccion -> listado) hace el resto.
        DB::table('cohorte')->delete();

        // 2) Evaluaciones huérfanas: materia ya no existe (codigo_materia NOT NULL
        //    y ausente en 'materia'; las de codigo_materia NULL se conservan).
        DB::table('evaluacion')
            ->whereNotNull('codigo_materia')
            ->whereNotIn('codigo_materia', function ($query) {
                $query->select('codigo_materia')->from('materia');
            })
            ->delete();
    }

    /**
     * Reverse the migrations.
     *
     * Irreversible a propósito: se decidió no hacer respaldo previo, los
     * datos borrados no se pueden restaurar.
     */
    public function down(): void
    {
        //
    }
};
