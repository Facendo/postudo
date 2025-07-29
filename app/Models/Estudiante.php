<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;
    protected $table= 'estudiantes';
    protected $primaryKey= 'cedula';
    public $keyType= 'string';

    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'carrera',
        'especialidad',
        'correo',
        'edad'
    ];
    public function pago(){
        return $this->hasMany(Pagos::class,'cedula','cedula');
    }
    public function seccion(){
        return $this->belongsTo(Seccion::class,'id_seccion','id_seccion');
    }
}
