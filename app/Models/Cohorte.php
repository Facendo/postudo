<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cohorte extends Model
{
    use HasFactory;

    protected $table = 'cohorte';

    protected $primaryKey = 'codigo_cohorte';

    protected $keyType = 'string';

    protected $fillable = [
        'codigo_cohorte',
        'codigo_postgrado',
        'codigo_mencion',
        'fecha_inicio',
        'fecha_fin',
        'preinscripcion_desde',
        'preinscripcion_hasta',
        'inscripcion_desde',
        'inscripcion_hasta',
        'nro_de_cohorte',
    ];

    /**
     * Cohortes con el proceso de preinscripción vigente HOY: la ventana
     * desde/hasta contiene el día actual y ambas fechas están puestas.
     * Es el gate de la portada y del formulario público (2026-11-11).
     */
    public function scopePreinscripcionAbierta($query)
    {
        $hoy = now()->toDateString();

        return $query->whereNotNull('preinscripcion_desde')
            ->whereNotNull('preinscripcion_hasta')
            ->whereDate('preinscripcion_desde', '<=', $hoy)
            ->whereDate('preinscripcion_hasta', '>=', $hoy);
    }

    /**
     * Ventana de preinscripción formateada (dd/mm/aaaa – dd/mm/aaaa) para
     * las tablas; null si la cohorte no tiene fechas de proceso puestas.
     */
    public function ventanaPreinscripcion(): ?string
    {
        if (! $this->preinscripcion_desde || ! $this->preinscripcion_hasta) {
            return null;
        }

        return Carbon::parse($this->preinscripcion_desde)->format('d/m/Y')
            .' – '
            .Carbon::parse($this->preinscripcion_hasta)->format('d/m/Y');
    }

    /**
     * La mención a la que pertenece la cohorte (creación anclada a menciones).
     * Nullable: si se borra la mención, la cohorte queda sin ella (set null).
     */
    public function mencion()
    {
        return $this->belongsTo(Mencion::class, 'codigo_mencion', 'id_mencion');
    }

    /**
     * Postgrado de la cohorte. Espejo derivado de la mención al guardar
     * (se conserva para inscripción e impactos de borrado).
     */
    public function postgrado()
    {
        return $this->belongsTo(Postgrado::class, 'codigo_postgrado', 'id_postgrado');
    }
}
