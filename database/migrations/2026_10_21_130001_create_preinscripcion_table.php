<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Preinscripción de estudiantes: réplica en BD de la planilla .doc de la
     * coordinación (storage/app/private/preinscripcion/plantilla.docx). La
     * cédula es única (una sola preinscripción por persona); codigo_mencion
     * guarda el interés académico con 'set null' (igual que estudiantes y
     * cohortes). Al aprobarla el estudiante queda apto para la planilla de
     * inscripción (validación futura), pero NO se crea en 'estudiantes'.
     */
    public function up(): void
    {
        Schema::create('preinscripcion', function (Blueprint $table) {
            $table->id();

            // Datos personales
            $table->string('cedula')->unique();
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('correo', 150);
            $table->string('telefono', 30);
            $table->unsignedTinyInteger('edad');
            $table->string('sexo', 10);            // M | F
            $table->string('estado_civil', 30);
            $table->string('nacionalidad', 50)->default('Venezolana');
            $table->string('lugar_nacimiento', 150);
            $table->string('etnia', 50)->nullable();
            $table->string('direccion_permanente');
            $table->string('direccion_otra')->nullable();
            $table->string('telefono_otro', 30)->nullable();
            $table->string('correo_otro', 150)->nullable();

            // Formación académica
            $table->string('titulo_pregrado', 150);
            $table->string('institucion_pregrado', 150);
            $table->unsignedSmallInteger('anio_grado');

            // Situación laboral
            $table->string('trabaja', 20);         // dependiente | cuenta_propia | libre_ejercicio | no
            $table->string('ocupacion', 150)->nullable();
            $table->string('cargo', 150)->nullable();
            $table->string('tiempo_cargo', 50)->nullable();
            $table->string('empresa')->nullable();
            $table->string('direccion_empresa')->nullable();
            $table->string('supervisor', 150)->nullable();
            $table->string('telefono_supervisor', 30)->nullable();
            $table->string('correo_supervisor', 150)->nullable();
            $table->string('fuente_financiamiento', 25); // propia | organismo_publico | otra
            $table->text('historial_trabajos')->nullable();
            $table->text('experiencia_laboral')->nullable();

            // Tabla de grados y referencias personales (JSON: la planilla
            // tiene filas/campos repetitivos que no justifican columnas)
            $table->json('grados')->nullable();
            $table->json('referencias');
            $table->json('documentos');

            // Interés académico y voucher del depósito
            $table->unsignedInteger('codigo_mencion')->nullable();
            $table->foreign('codigo_mencion')
                ->references('id_mencion')
                ->on('mencion')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->string('sede', 50);
            $table->string('voucher_nro', 50)->nullable();
            $table->string('voucher_fecha', 30)->nullable();
            $table->string('voucher_banco', 100)->nullable();

            // Estado del trámite
            $table->string('estado', 15)->default('pendiente'); // pendiente | aprobada | rechazada

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preinscripcion');
    }
};
