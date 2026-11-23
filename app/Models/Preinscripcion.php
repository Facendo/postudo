<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Preinscripción de un aspirante a un postgrado (planilla .doc de la
 * coordinación). Una fila por cédula; al aprobarla el aspirante pasa al
 * apartado de Inscripciones del coordinador de postgrado, que es quien
 * lo da de alta en 'estudiantes' y lo inscribe en una cohorte
 * (2026-11-23). Aprobar o rechazar la preinscripción por sí solo no
 * crea estudiantes.
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
     * Bancos desde los que el aspirante puede pagar el voucher
     * (desplegable obligatorio de /preinscripcion; mismos valores que
     * los selects de banco de registropago del estudiante).
     */
    public const BANCOS = [
        'Banco de Venezuela',
        'Banesco',
        'Mercantil',
        'BBVA Provincial',
        'Banco Nacional de Crédito',
        'Banco Activo',
        'Banco Bicentenario del Pueblo',
        'Banco del Tesoro',
        'Banco Exterior',
        'Banco Fondo Común',
        'Banco Caroní',
        'Banplus',
        'Venezolano de Crédito',
        'Banco Plaza',
    ];

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
