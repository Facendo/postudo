<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Crea la cohorte anclada a una mención (área -> postgrado -> mención).
     * Nullable + 'set null', igual que estudiantes.codigo_mencion: las
     * cohortes existentes quedan sin mención y no se pierde nada. La columna
     * codigo_postgrado se conserva como espejo y se deriva de la mención al
     * guardar (nunca desde el cliente).
     */
    public function up(): void
    {
        Schema::table('cohorte', function (Blueprint $table) {
            $table->unsignedInteger('codigo_mencion')->nullable()->after('codigo_postgrado');

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
        Schema::table('cohorte', function (Blueprint $table) {
            // dropForeign SIEMPRE antes de dropColumn (regla del proyecto).
            $table->dropForeign(['codigo_mencion']);
            $table->dropColumn('codigo_mencion');
        });
    }
};
