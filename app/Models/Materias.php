<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mockery\Exception\NoMatchingExpectationException;

class Materias extends Model
{
    use HasFactory;
    protected $table = 'materia';
    protected $primaryKey = 'codigo_materia';
    public $keyType = 'integer';

    protected $fillable = [
        'codigo_materia',
        'nombre',
        'nombre_carrera',
        'nombre_especialidad',
        'nro_seccion',
        'prelacion'
    ];

    public function Seccion(){
        return $this->hasMany(Seccion::class, 'codigo_materia', 'codigo_materia');
    }

    public function Evaluacion(){
        return $this->hasMany(Evaluacion::class, 'codigo_materia', 'codigo_materia');
    }
    public function Cohortes(){
        return $this->belongsTo(Cohorte::class, 'codigo_cohorte', 'codigo_cohorte');
    }

    public function Especialidades(){
        return $this->belongsTo(Especialidades::class, 'nombre_especialidad', 'nombre_especialidad');
    }
    public function Carrera(){
        return $this->belongsTo(Carrera::class, 'nombre_carrera', 'nombre_carrera');
    }
}
