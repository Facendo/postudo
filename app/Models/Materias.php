<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materias extends Model
{
    use HasFactory;
    protected $table = 'materias';
    protected $primaryKey = 'codigo_materia';
    public $keyType = 'integer';

    public function Seccion(){
        return $this->hasMany(Seccion::class, 'codigo_materia', 'codigo_materia');
    }

    public function Evaluacion(){
        return $this->hasMany(Evaluacion::class, 'codigo_materia', 'codigo_materia');
    }

    public function Cohortes(){
        return $this->belongsTo(Cohorte::class, 'codigo_cohorte', 'codigo_cohorte');
    }
}
