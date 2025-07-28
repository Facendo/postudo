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
            $table->integer('id_especialidad');
            $table->string('nombre');
            $table->integer('prelacion')->nullable();
            $table->timestamps();


            $table->foreign('id_especialidad')
                ->references('codigo_especialidad')
                ->on('especialidades')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
