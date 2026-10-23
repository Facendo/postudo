<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Preinscripción de un aspirante a un postgrado (planilla .doc de la
 * coordinación). Una fila por cédula; al aprobarla habilita al estudiante
 * para la planilla de inscripción (validación futura), pero NO lo da de
 * alta en 'estudiantes'.
 */
class Preinscripcion extends Model
{
    use HasFactory;

    protected $table = 'preinscripcion';

    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'correo',
        'telefono',
        'edad',
        'sexo',
        'estado_civil',
        'nacionalidad',
        'lugar_nacimiento',
        'etnia',
        'direccion_permanente',
        'direccion_otra',
        'telefono_otro',
        'correo_otro',
        'titulo_pregrado',
        'institucion_pregrado',
        'anio_grado',
        'trabaja',
        'ocupacion',
        'cargo',
        'tiempo_cargo',
        'empresa',
        'direccion_empresa',
        'supervisor',
        'telefono_supervisor',
        'correo_supervisor',
        'fuente_financiamiento',
        'historial_trabajos',
        'experiencia_laboral',
        'grados',
        'referencias',
        'documentos',
        'codigo_mencion',
        'sede',
        'voucher_nro',
        'voucher_fecha',
        'voucher_banco',
        'estado',
    ];

    protected $casts = [
        'grados' => 'array',
        'referencias' => 'array',
        'documentos' => 'array',
    ];

    /** Sedes que aparecen en la planilla (matriz mención x sede). */
    public const SEDES = ['Barcelona', 'Cantaura'];

    /**
     * Mención de interés del aspirante (nullable con 'set null').
     */
    public function mencion()
    {
        return $this->belongsTo(Mencion::class, 'codigo_mencion', 'id_mencion');
    }

    /**
     * ¿Está aprobada? (habilita al estudiante para la inscripción).
     */
    public function estaAprobada(): bool
    {
        return $this->estado === 'aprobada';
    }
}
