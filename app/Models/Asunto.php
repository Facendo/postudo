<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Estudiante;

class Asunto extends Model
{
    protected $table = 'asunto';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
        'cedula_estudiante',
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'cedula_estudiante', 'cedula');
    }
}
