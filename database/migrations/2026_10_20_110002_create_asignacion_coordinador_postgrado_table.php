<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Asignación de postgrados a coordinadores de postgrado. El 'unique' en
     * 'codigo_postgrado' garantiza UN coordinador por postgrado (decisión del
     * usuario 2026-10-20); un coordinador sí puede tener varios postgrados.
     * Los FK con 'cascade' limpian la asignación si desaparece el
     * coordinador o el postgrado.
     */
    public function up(): void
    {
        Schema::create('asignacion_coordinador_postgrado', function (Blueprint $table) {
            $table->string('cedula');
            $table->string('codigo_postgrado');
            $table->timestamps();

            $table->primary(['cedula', 'codigo_postgrado']);
            $table->unique('codigo_postgrado');

            $table->foreign('cedula')
                ->references('cedula')
                ->on('coordinador_postgrado')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('codigo_postgrado')
                ->references('id_postgrado')
                ->on('postgrado')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion_coordinador_postgrado');
    }
};
