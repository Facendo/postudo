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
            $table->date('hora_inicio');
            $table->date('hora_fin');
            $table->string('aula');
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
        Schema::dropIfExists('seccion');
    }
};
