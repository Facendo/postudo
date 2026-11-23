<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Proceso de inscripción de un aspirante (preinscripción aprobada) a una
 * cohorte, gestionado por el coordinador de postgrado en dos pasos:
 * aprobar/rechazar al aspirante (paso 1, que además crea la fila en
 * 'estudiantes') y elegirle cohorte (paso 2, estado 'inscrita').
 *
 * El estado 'pendiente' no se guarda: es la ausencia de fila.
 */
class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripcion';

    protected $fillable = [
        'cedula',
        'codigo_cohorte',
        'estado',
        'revisado_por',
        'inscrito_por',
    ];

    /**
     * Cohorte en la que quedó inscrito (null mientras no se le asigne).
     */
    public function cohorte()
    {
        return $this->belongsTo(Cohorte::class, 'codigo_cohorte', 'codigo_cohorte');
    }

    /**
     * Quien aprobó o rechazó al aspirante (users).
     */
    public function revisadoPor()
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    /**
     * Quien lo inscribió en la cohorte (users).
     */
    public function inscritoPor()
    {
        return $this->belongsTo(User::class, 'inscrito_por');
    }

    /**
     * ¿Ya tiene cohorte asignada?
     */
    public function estaInscrita(): bool
    {
        return $this->estado === 'inscrita';
    }
}
