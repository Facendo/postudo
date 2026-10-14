<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Egreso extends Model
{
    protected $table = 'egreso';

    protected $fillable = [
        'monto',
        'tasa_momento',
        'fecha',
        'motivo',
        'detalle',
        'registrado_por',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'tasa_momento' => 'decimal:4',
        'fecha' => 'date',
    ];

    /**
     * Motivos permitidos para registrar un egreso.
     */
    public const MOTIVOS = [
        'Compra',
        'Pago',
        'Mantenimiento',
        'Servicios',
        'Nómina',
        'Alquiler',
        'Otro',
    ];

    /**
     * Administrador que registró el egreso.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}
