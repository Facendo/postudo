<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Elimina por completo el bloque académico que se retiró del sistema
 * (2026-10-21): materias, secciones, profesores, evaluaciones y listados.
 * El árbol académico queda en Área -> Postgrado -> Mención -> Cohorte.
 *
 * Orden respetando las foráneas: se baja primero lo que depende de otro
 * (listado -> seccion -> materia) y luego las tablas independientes.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('listado');
        Schema::dropIfExists('seccion');
        Schema::dropIfExists('materia');
        Schema::dropIfExists('evaluacion');
        Schema::dropIfExists('profesor');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Irreversible: se eliminan tablas y datos del bloque materias/secciones.
    }
};
