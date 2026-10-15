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
        Schema::create('egreso', function (Blueprint $table) {
            $table->id();
            $table->decimal('monto', 10, 2); // Monto del egreso en Bs.
            $table->decimal('tasa_momento', 12, 4)->nullable(); // Tasa de cambio vigente al momento de registrar
            $table->date('fecha'); // Fecha en que ocurrió el egreso
            $table->string('motivo', 100); // Compra, Pago, Mantenimiento, etc.
            $table->string('detalle', 255)->nullable(); // Detalle adicional (obligatorio si motivo es "Otro")
            $table->foreignId('registrado_por')->nullable()->constrained('users')->nullOnDelete(); // Admin que registró
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egreso');
    }
};
