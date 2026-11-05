<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Quita la columna 'descripcion' de 'postgrado': el texto ya no se pide al
 * crear el postgrado ni se muestra en el listado (decisión del 2026-10-26),
 * así que la columna quedó sin uso en toda la aplicación.
 * La columna no tiene FK, por lo que basta con dropColumn.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('postgrado', function (Blueprint $table) {
            $table->dropColumn('descripcion');
        });
    }

    /**
     * Reverse the migrations. Se devuelve como nullable para que reversar no
     * exija rellenar el valor en las filas existentes.
     */
    public function down(): void
    {
        Schema::table('postgrado', function (Blueprint $table) {
            $table->string('descripcion')->nullable()->after('nombre');
        });
    }
};
