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
            $table->integer('codigo_seccion');
            $table->string('titulo');
            $table->integer('porcentaje');
            $table->string('metodologia');
            $table->integer('nota');
            $table->timestamps();

            $table->foreign('codigo_materia')
                ->references('codigo_materia')
                ->on('materia')
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
