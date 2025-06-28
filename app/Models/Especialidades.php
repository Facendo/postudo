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

    public function Carreras(){
        return $this->hasMany(Carrera::class, 'codigo_especialidad', 'codigo_especialidad');
    }
}
