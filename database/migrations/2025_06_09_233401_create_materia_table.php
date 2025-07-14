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
        Schema::create('materia', function (Blueprint $table) {
            $table->integer('codigo_materia')->primary();
            $table->string('codigo_cohorte');
            $table->integer('nro_seccion');
            $table->string('Nombre');
            $table->integer('Prelacion');
            $table->timestamps();

            $table->foreign('codigo_cohorte')
                ->references('codigo_cohorte')
                ->on('cohorte')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};
