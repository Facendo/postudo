<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cohorte extends Model
{
    use HasFactory;
    protected $table = 'cohorte';
    protected $primaryKey = 'codigo_cohorte';
    protected $keyType = 'string';

    public function Materias(){
        return $this->hasMany(Materias::class, 'codigo_cohorte', 'codigo_cohorte');
    }
}
