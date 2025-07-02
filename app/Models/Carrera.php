<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;
    protected $table = 'carrera';
    protected $primaryKey = 'id_carrera';
    protected $keyType = 'integer';

    public function Especialidades(){
        return $this->belongsToMany(Especialidades::class, 'codigo_especialidad', 'codigo_especialidad');
    }
}
