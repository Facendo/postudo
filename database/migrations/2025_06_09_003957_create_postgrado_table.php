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
        Schema::create('postgrado', function (Blueprint $table) {
            $table->string('Id_postgrado')->primary();
            $table->integer('codigo_especialidad');
            $table->string('nombre');
            $table->string('descripcion');
            $table->string('duracion');
          
            $table->foreign('codigo_especialidad')
                ->references('codigo_especialidad')
                ->on('especialidades')
                ->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('postgrado');
    }
};