<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;
    protected $table= 'estudiante';
    protected $primaryKey= 'cedula';
    public $keyType= 'string';
    
    public function pago(){
        return $this->hasMany(Pagos::class,'cedula','cedula');
    }
}
