<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Apertura de cohortes por solicitud (2026-11-11).
     *
     * Flujo: el coordinador de postgrado SOLICITA cupos para su postgrado y
     * el coordinador general los aprueba o rechaza. Solo las solicitudes
     * APROBADAS generan cupo; son acumulables por postgrado
     * (restante = SUM(cupos - consumido)) y el coordinador de postgrado solo
     * puede crear cohortes dentro de ese cupo (check en CohorteController).
     *
     * - codigo_postgrado: al borrar el postgrado, sus solicitudes caen en
     *     cascada (igual que sus menciones).
     * - consumido: se incrementa al crear una cohorte; solo cuenta si la
     *     solicitud está aprobada (se valida en la consulta, no aquí).
     * - solicitado_por / revisado_por: cuentas `users`; si se borran, las
     *     filas se conservan con NULL (quién pidió/quién respondió).
     * - motivo_rechazo: la app exige motivo al rechazar (validación de código).
     */
    public function up(): void
    {
        Schema::create('solicitud_apertura_cohorte', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_postgrado');
            $table->unsignedInteger('cupos');
            $table->unsignedInteger('consumido')->default(0);
            $table->string('estado', 20)->default('pendiente'); // pendiente | aprobada | rechazada
            $table->text('justificacion')->nullable();
            $table->foreignId('solicitado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('revisado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('respondido_en')->nullable();
            $table->text('motivo_rechazo')->nullable();
            $table->timestamps();

            $table->foreign('codigo_postgrado')
                ->references('id_postgrado')
                ->on('postgrado')
                ->onDelete('cascade');

            $table->index(['codigo_postgrado', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_apertura_cohorte');
    }
};
