<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabla de rol del «coordinador de postgrado», espejo exacto de
     * 'coordinador_general'. Solo guarda datos: la fila de 'users' se crea
     * en el registro, que deduce el rol buscando la cédula aquí.
     */
    public function up(): void
    {
        Schema::create('coordinador_postgrado', function (Blueprint $table) {
            $table->string('cedula')->primary();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('correo')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coordinador_postgrado');
    }
};
