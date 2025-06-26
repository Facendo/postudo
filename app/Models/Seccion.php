<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    use HasFactory;
    protected $table = 'seccion';
    protected $primaryKey = 'id_seccion';
    public $keyType = 'integer';

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'id_seccion', 'id_seccion');
    }
    public function profesor(){
        return $this->hasOne(Profesor::class, 'id_profesor', 'id_profesor');
    }
}
