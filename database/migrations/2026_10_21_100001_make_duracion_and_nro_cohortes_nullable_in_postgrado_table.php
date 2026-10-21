<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 'duracion' y 'nro_cohortes' dejan de pedirse en el alta/edición de un
     * postgrado (la duración ya no interesa y el número de cohortes se cuenta
     * de las cohortes reales creadas), así que pasan a nullable para no
     * violar NOT NULL al guardar un postgrado nuevo. Los valores antiguos se
     * conservan en las filas existentes.
     */
    public function up(): void
    {
        Schema::table('postgrado', function (Blueprint $table) {
            $table->integer('nro_cohortes')->nullable()->change();
            $table->string('duracion')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('postgrado', function (Blueprint $table) {
            $table->integer('nro_cohortes')->nullable(false)->change();
            $table->string('duracion')->nullable(false)->change();
        });
    }
};
