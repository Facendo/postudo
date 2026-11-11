<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Solicitud de apertura de cohortes (2026-11-11).
 *
 * El coordinador de postgrado pide cupos para su postgrado y el coordinador
 * general aprueba o rechaza. Solo las aprobadas generan cupo y son
 * acumulables por postgrado. Un solo ciclo de vida: pendiente -> aprobada o
 * rechazada (terminal); no se reabre ni se edita la cantidad.
 */
class SolicitudAperturaCohorte extends Model
{
    public const ESTADO_PENDIENTE = 'pendiente';

    public const ESTADO_APROBADA = 'aprobada';

    public const ESTADO_RECHAZADA = 'rechazada';

    protected $table = 'solicitud_apertura_cohorte';

    /**
     * Los atributos que se pueden asignar en masa.
     */
    protected $fillable = [
        'codigo_postgrado',
        'cupos',
        'consumido',
        'estado',
        'justificacion',
        'solicitado_por',
        'revisado_por',
        'respondido_en',
        'motivo_rechazo',
    ];

    /**
     * Postgrado al que se solicitan cupos.
     */
    public function postgrado()
    {
        return $this->belongsTo(Postgrado::class, 'codigo_postgrado', 'id_postgrado');
    }

    /**
     * Coordinador de postgrado que pidió la apertura.
     */
    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitado_por');
    }

    /**
     * Coordinador general que respondió (aprobar/rechazar).
     */
    public function revisor()
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    /**
     * Cupos que quedan disponibles en UN postgrado: suma de las solicitudes
     * APROBADAS menos lo ya consumido. Las pendientes/rechazadas no cuentan.
     */
    public static function cuposDisponibles(string $codigoPostgrado): int
    {
        return static::cuposDisponiblesEn([$codigoPostgrado]);
    }

    /**
     * Igual que cuposDisponibles() pero para varios postgrados a la vez
     * (lo usa el listado de cohortes del coordinador con sus N postgrados).
     *
     * @param  array<int, string>  $codigosPostgrado
     */
    public static function cuposDisponiblesEn(array $codigosPostgrado): int
    {
        if ($codigosPostgrado === []) {
            return 0;
        }

        return (int) static::query()
            ->whereIn('codigo_postgrado', $codigosPostgrado)
            ->where('estado', self::ESTADO_APROBADA)
            ->selectRaw('COALESCE(SUM(cupos - consumido), 0) as restante')
            ->value('restante');
    }

    /**
     * Consume UN cupo del postgrado dentro de una transacción con bloqueo
     * (lockForUpdate) para que dos creaciones simultáneas nunca consuman el
     * mismo cupo. Consume FIFO: la aprobación más antigua con cupos libres.
     *
     * Devuelve false si no hay ningún cupo disponible (no se consume nada).
     */
    public static function consumirCupo(string $codigoPostgrado): bool
    {
        return DB::transaction(function () use ($codigoPostgrado) {
            $aperturas = static::query()
                ->where('codigo_postgrado', $codigoPostgrado)
                ->where('estado', self::ESTADO_APROBADA)
                ->whereColumn('consumido', '<', 'cupos')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($aperturas->isEmpty()) {
                return false; // sin aperturas o todas agotadas
            }

            $aperturas->first()->increment('consumido');

            return true;
        });
    }
}
