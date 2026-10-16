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
     * Crea la tabla 'mencion' (las menciones cuelgan de un postgrado) y
     * rellena una mención por cada postgrado existente con el nombre de su
     * especialidad actual, antes de que se elimine la tabla 'especialidades'.
     */
    public function up(): void
    {
        Schema::create('mencion', function (Blueprint $table) {
            $table->increments('id_mencion');
            $table->string('id_postgrado');
            $table->string('nombre');
            $table->timestamps();

            $table->foreign('id_postgrado')
                ->references('id_postgrado')
                ->on('postgrado')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        if (! Schema::hasTable('especialidades') || ! Schema::hasTable('postgrado')) {
            return;
        }

        // Nombre de cada especialidad actual, indexado por su código.
        $nombres = DB::table('especialidades')->pluck('nombre', 'codigo_especialidad');

        DB::table('postgrado')
            ->whereNotNull('codigo_especialidad')
            ->get(['id_postgrado', 'codigo_especialidad'])
            ->each(function ($postgrado) use ($nombres) {
                $nombre = $nombres[$postgrado->codigo_especialidad] ?? null;

                if ($nombre === null) {
                    return;
                }

                DB::table('mencion')->insert([
                    'id_postgrado' => $postgrado->id_postgrado,
                    'nombre' => $nombre,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mencion');
    }
};
