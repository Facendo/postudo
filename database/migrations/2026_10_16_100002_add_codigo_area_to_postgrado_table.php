<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Los postgrados pasan a depender directamente de un área: se añade
     * 'codigo_area', se rellena a partir de la cadena
     * postgrado -> especialidad -> carrera -> área y se elimina la columna
     * 'codigo_especialidad'.
     */
    public function up(): void
    {
        Schema::table('postgrado', function (Blueprint $table) {
            $table->string('codigo_area')->nullable()->after('id_postgrado');
        });

        if (Schema::hasTable('especialidades') && Schema::hasTable('carrera')) {
            $areasPorEspecialidad = DB::table('especialidades')
                ->join('carrera', 'carrera.id_carrera', '=', 'especialidades.id_carrera')
                ->pluck('carrera.codigo_area', 'especialidades.codigo_especialidad');

            DB::table('postgrado')
                ->whereNotNull('codigo_especialidad')
                ->get(['id_postgrado', 'codigo_especialidad'])
                ->each(function ($postgrado) use ($areasPorEspecialidad) {
                    $codigoArea = $areasPorEspecialidad[$postgrado->codigo_especialidad] ?? null;

                    if ($codigoArea === null) {
                        return;
                    }

                    DB::table('postgrado')
                        ->where('id_postgrado', $postgrado->id_postgrado)
                        ->update(['codigo_area' => $codigoArea]);
                });
        }

        Schema::table('postgrado', function (Blueprint $table) {
            $table->foreign('codigo_area')
                ->references('codigo')
                ->on('area')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('postgrado', function (Blueprint $table) {
            $table->dropForeign(['codigo_especialidad']);
        });

        Schema::table('postgrado', function (Blueprint $table) {
            $table->dropColumn('codigo_especialidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('postgrado', function (Blueprint $table) {
            $table->integer('codigo_especialidad')->nullable()->after('codigo_area');
        });

        Schema::table('postgrado', function (Blueprint $table) {
            $table->foreign('codigo_especialidad')
                ->references('codigo_especialidad')
                ->on('especialidades')
                ->onDelete('cascade');
        });

        Schema::table('postgrado', function (Blueprint $table) {
            $table->dropForeign(['codigo_area']);
        });

        Schema::table('postgrado', function (Blueprint $table) {
            $table->dropColumn('codigo_area');
        });
    }
};
