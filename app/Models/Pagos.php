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
        'monto',
        'fecha_pago',
        'metodo_pago',
        'estado',
        'cedula',
    ];
    protected $primaryKey = 'id';  
    protected $keyType = 'int';

    public function estudiante(){
        return $this->belongsTo(Estudiante::class,'cedula','cedula');
    }
}
