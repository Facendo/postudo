<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    use HasFactory;
    protected $table = 'profesor';
    protected $primaryKey = 'cedula';
    public $keyType = 'string';

    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'telefono',
        'correo',
        'edad'
    ];
    public function seccion()
    {
        return $this->belongsTo(Seccion::class, 'cedula_profesor', 'cedula');
    }
}
