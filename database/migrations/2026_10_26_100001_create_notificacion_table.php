<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Notificaciones in-app: una fila por destinatario (fan-out), de modo
     * que el contador de «no leídas» de la campanita es un simple count().
     *
     * - user_id: destinatario; si se borra el usuario, sus notificaciones
     *     se van en cascada (igual que sus sesiones).
     * - emisor_id: quien la originó (admin/coordinador o el propio
     *   estudiante en las solicitudes); si se borra, queda en NULL y la
     *   notificación se conserva.
     * - enlace: solo se llena si el destinatario PUEDE abrirlo (p. ej. la
     *   página de pagos es solo de administradores).
     * - leida_en: NULL = sin leer.
     */
    public function up(): void
    {
        Schema::create('notificacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('emisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tipo', 30);   // solicitud | estado | comunicado
            $table->string('titulo');
            $table->text('mensaje');
            $table->string('enlace')->nullable();
            $table->timestamp('leida_en')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'leida_en']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacion');
    }
};
