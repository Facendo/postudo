<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * El número de cohorte se autoincrementa por mención al crearlo
     * (CohorteController::store calcula max+1). La unicidad solo existía en
     * la validación; aquí pasa a garantizarse también en BD para que dos
     * peticiones simultáneas no puedan duplicar el número dentro de una mención.
     */
    public function up(): void
    {
        Schema::table('cohorte', function (Blueprint $table) {
            $table->unique(['codigo_mencion', 'nro_de_cohorte'], 'cohorte_mencion_nro_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cohorte', function (Blueprint $table) {
            $table->dropUnique('cohorte_mencion_nro_unique');
        });
    }
};
