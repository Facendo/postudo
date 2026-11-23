<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Inscripciones de aspirantes a una cohorte (2026-11-23).
     *
     * El coordinador de postgrado gestiona el proceso en dos pasos desde
     * su apartado de Inscripciones: (1) aprueba/rechaza al aspirante —
     * aprobar además crea la fila en 'estudiantes' (eso vive en el
     * controller, no aquí) — y (2) lo inscribe en una cohorte SOLO de la
     * mención que eligió en su preinscripción.
     *
     * 'cedula' se guarda NORMALIZADA (solo dígitos, igual que
     * 'estudiantes.cedula' y 'users.cedula'), de modo que el estudiante
     * puede consultar su estado con la cédula de su usuario; el listado
     * del coordinador empareja con la preinscripción (que puede llevar
     * formato «V-30.123.456») normalizando en PHP. El estado
     * 'pendiente' NO se almacena: es la ausencia de fila. codigo_cohorte
     * cae con 'set null' para conservar el registro del estudiante aunque
     * se elimine la cohorte (mismo criterio que estudiantes.codigo_mencion).
     */
    public function up(): void
    {
        Schema::create('inscripcion', function (Blueprint $table) {
            $table->id();
            $table->string('cedula')->unique();
            $table->string('codigo_cohorte')->nullable();
            $table->string('estado', 15)->default('aprobada'); // aprobada | rechazada | inscrita
            $table->unsignedBigInteger('revisado_por')->nullable();
            $table->unsignedBigInteger('inscrito_por')->nullable();
            $table->timestamps();

            $table->foreign('codigo_cohorte')
                ->references('codigo_cohorte')
                ->on('cohorte')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('revisado_por')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('inscrito_por')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripcion');
    }
};
