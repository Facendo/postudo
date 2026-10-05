<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pagos extends Model
{
    use HasFactory;
    protected $table= 'pago';
    protected $fillable = [
        'id',
        'nombre',
        'cedula',
        'banco_emisor',
        'banco_receptor',
        'referencia',
        'monto',
        'tasa_momento',
        'asunto',
        'fecha_pago',
        'estado',
    ];
    protected $primaryKey = 'id';  
    protected $keyType = 'int';

    public function estudiante(){
        return $this->belongsTo(Estudiante::class,'cedula','cedula');
    }
}
