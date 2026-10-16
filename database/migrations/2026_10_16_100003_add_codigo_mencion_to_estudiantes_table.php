<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Asociación de los estudiantes con una mención. Es nullable y con
     * 'onDelete("set null"') para que eliminar una mención (o su postgrado)
     * nunca borre estudiantes: simplemente quedan "sin postgrado asignado".
     * Las filas existentes no se modifican (quedan en NULL).
     */
    public function up(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->unsignedInteger('codigo_mencion')->nullable()->after('especialidad');

            $table->foreign('codigo_mencion')
                ->references('id_mencion')
                ->on('mencion')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->dropForeign(['codigo_mencion']);
        });

        Schema::table('estudiantes', function (Blueprint $table) {
            $table->dropColumn('codigo_mencion');
        });
    }
};
