<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;
    protected $table = 'Evaluacion';
    protected $primaryKey = 'Id_evaluacion';
    public $keyType = 'interger';

    public function Materias(){
        return $this->belongsTo(Materias::class, 'codigo materia', 'codigo materia');
    }
}
