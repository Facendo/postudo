<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidades extends Model
{
    use HasFactory;
    protected $table = 'especialidades';
    protected $primaryKey = 'codigo_especialidad';
    protected $keyType = 'integer';

    protected $fillable = [
        'codigo_especialidad',
        'nombre',
        'id_carrera',
    ];
    // En Especialidades.php
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id_carrera');
    }
    public function materias()
    {
        return $this->hasMany(Materias::class, 'codigo_especialidad', 'codigo_especialidad');
    }
}
