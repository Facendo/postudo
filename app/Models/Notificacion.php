<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificacion';

    /**
     * Los atributos que se pueden asignar en masa.
     */
    protected $fillable = [
        'user_id',
        'emisor_id',
        'tipo',
        'titulo',
        'mensaje',
        'enlace',
        'leida_en',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'leida_en' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Destinatario de la notificación.
     */
    public function destinatario()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Quien originó la notificación (NULL si se borró su usuario).
     */
    public function emisor()
    {
        return $this->belongsTo(User::class, 'emisor_id', 'id');
    }

    /**
     * Solo las que aún no se han leído.
     */
    public function scopeNoLeidas($query)
    {
        return $query->whereNull('leida_en');
    }

    /**
     * Marca la notificación como leída (idempotente).
     */
    public function marcarComoLeida(): void
    {
        if ($this->leida_en === null) {
            $this->update(['leida_en' => now()]);
        }
    }
}
