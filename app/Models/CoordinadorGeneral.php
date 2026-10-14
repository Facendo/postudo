<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoordinadorGeneral extends Model
{
    use HasFactory;
    protected $table = 'coordinador_general';
    protected $primaryKey = 'cedula';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'correo',
    ];
    public $timestamps = true;
}
