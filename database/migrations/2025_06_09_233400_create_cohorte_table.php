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
        Schema::create('cohorte', function (Blueprint $table) {
            $table->string('codigo_cohorte')->primary();
            $table->string('codigo_postgrado');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->integer('nro_de_cohorte');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cohorte');
    }
};
