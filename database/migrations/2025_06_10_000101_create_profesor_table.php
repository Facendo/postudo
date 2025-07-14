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
        Schema::create('profesor', function (Blueprint $table) {
            $table->string('cedula')->primary();
            $table->integer('nro_seccion');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('telefono');
            $table->string('correo')->unique();
            $table->string('edad');
            $table->timestamps();


            $table->foreign('nro_seccion')
                ->references('nro_seccion')
                ->on('seccion')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profesor');
    }
};
