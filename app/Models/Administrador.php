<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    protected $table = 'administrador';
    protected $primaryKey = 'cedula';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'cedula',
        'nombre',
        'correo',
        'telefono',
    ];
    public $timestamps = true;
}
