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
        Schema::create('pago', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('cedula');
            $table->string('nombre');
            $table->string('banco_emisor');
            $table->string('banco_receptor');
            $table->string('referencia');
            $table->double('monto');
            $table->string('asunto');
            $table->date('fecha_pago');
            $table->string('estado');
            $table->timestamps();

            $table->foreign('cedula')
                        ->references('cedula')
                        ->on('estudiantes')
                        ->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago');
    }
};
