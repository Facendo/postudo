<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluacion', function (Blueprint $table) {
            $table->id('id_evaluacion')->primary();
            $table->integer('codigo_materia')->nullable();
            $table->integer('codigo_seccion');
            $table->string('titulo');
            $table->integer('porcentaje');
            $table->string('metodologia');
            $table->date('fecha'); // Asegúrate de que este campo exista en tu modelo
            $table->integer('nota')->nullable(); // Asegúrate de que este campo sea nullable si es necesario
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluacion');
    }
};
