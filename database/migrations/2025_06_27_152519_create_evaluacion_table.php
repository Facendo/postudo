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
            $table->integer('id_evaluacion')->primary();
            $table->integer('Codigo_materia');
            $table->string('titulo');
            $table->integer('porcentaje');
            $table->string('metodologia');
            $table->integer('nota');
            $table->timestamps();

            $table->foreign('Codigo_materia')
                ->references('Codigo_materia')
                ->on('materias')
                ->onDelete('cascade');
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
