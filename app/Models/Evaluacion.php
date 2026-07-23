<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;
    protected $table = 'evaluacion';
    protected $primaryKey = 'id_evaluacion';
    public $incrementing = true; // Esto lo hace autoincremental
    public $keyType = 'int';

    protected $fillable = [
        'codigo_materia',
        'codigo_seccion',
        'titulo',
        'porcentaje',
        'metodologia',
        'fecha', // Asegúrate de que este campo exista en tu modelo
        'nota' // Asegúrate de que este campo sea nullable si es necesario
    ];

    public function Materias(){
        return $this->belongsTo(Materias::class, 'codigo_materia', 'codigo_materia');
    }
}
