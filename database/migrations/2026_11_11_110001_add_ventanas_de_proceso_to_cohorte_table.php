<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ventanas de proceso en las cohortes (2026-11-11).
     *
     * Al crear la cohorte se establece el tiempo del proceso de
     * preinscripciones (y de inscripciones, registrado por ahora sin usarse:
     * la inscripción del estudiante queda como está hasta una fase 2).
     *
     * - preinscripcion_desde/hasta: mientras hoy esté dentro de esta
     *   ventana, el postgrado muestra el botón de preinscripción en la
     *   portada y acepta envíos del formulario público.
     * - NULL = sin habilitar: las cohortes existentes quedan apagadas hasta
     *   que se editen y se les pongan fechas (decisión del usuario).
     */
    public function up(): void
    {
        Schema::table('cohorte', function (Blueprint $table) {
            $table->date('preinscripcion_desde')->nullable()->after('fecha_fin');
            $table->date('preinscripcion_hasta')->nullable()->after('preinscripcion_desde');
            $table->date('inscripcion_desde')->nullable()->after('preinscripcion_hasta');
            $table->date('inscripcion_hasta')->nullable()->after('inscripcion_desde');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cohorte', function (Blueprint $table) {
            $table->dropColumn([
                'preinscripcion_desde',
                'preinscripcion_hasta',
                'inscripcion_desde',
                'inscripcion_hasta',
            ]);
        });
    }
};
