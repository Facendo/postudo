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
        Schema::create('seccion', function (Blueprint $table) {
            $table->integer('nro_seccion')->primary();
            $table->integer('codigo_materia');
            $table->string('cedula_profesor');
            $table->date('hora_inicio');
            $table->date('hora_fin');
            $table->string('aula');
            $table->integer('cupo_maximo');
            $table->integer('cupo_actual')->default(0);
            $table->timestamps();

            $table->foreign('codigo_materia')
                ->references('codigo_materia')
                ->on('materia')
                ->onDelete('cascade');
                
            $table->foreign('cedula_profesor')
                ->references('cedula')
                ->on('profesor')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seccion');
    }
};
