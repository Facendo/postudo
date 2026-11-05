<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Elimina las tablas 'carrera' y 'especialidades': el árbol académico
     * queda en Área -> Postgrado -> Mención. Los postgrados ya fueron
     * reasignados a su área en la migración anterior.
     */
    public function up(): void
    {
        if (Schema::hasTable('especialidades')) {
            Schema::table('especialidades', function (Blueprint $table) {
                $table->dropForeign(['id_carrera']);
            });

            Schema::dropIfExists('especialidades');
        }

        if (Schema::hasTable('carrera')) {
            Schema::table('carrera', function (Blueprint $table) {
                $table->dropForeign(['codigo_area']);
            });

            Schema::dropIfExists('carrera');
        }
    }

    /**
     * Reverse the migrations.
     *
     * Solo recrea la estructura: los datos eliminados no se recuperan.
     */
    public function down(): void
    {
        if (! Schema::hasTable('carrera')) {
            Schema::create('carrera', function (Blueprint $table) {
                $table->integer('id_carrera')->primary();
                $table->string('codigo_area');
                $table->string('nombre');
                $table->timestamps();

                $table->foreign('codigo_area')
                    ->references('codigo')
                    ->on('area')
                    ->onDelete('cascade');
            });
        }

        if (! Schema::hasTable('especialidades')) {
            Schema::create('especialidades', function (Blueprint $table) {
                $table->integer('codigo_especialidad')->primary();
                $table->integer('id_carrera');
                $table->string('nombre');
                $table->timestamps();

                $table->foreign('id_carrera')
                    ->references('id_carrera')
                    ->on('carrera')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            });
        }
    }
};
