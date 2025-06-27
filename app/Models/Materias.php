<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materias extends Model
{
    use HasFactory;
    protected $table = 'materias';
    protected $primaryKey = 'codigo materia';
    public $keyType = 'integer';

    public function Seccion(){
        return $this->hasMany(Seccion::class, 'codigo materia', 'codigo materia');
    }

    public function Evaluacion(){
        return $this->hasMany(Evaluacion::class, 'codigo materia', 'codigo materia');
    }
}
