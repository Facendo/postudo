<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'area';
    protected $primaryKey = 'codigo';
    public $timestamps = false;
    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
    ];
    public function carreras()
    {
        return $this->hasMany(Carrera::class, 'codigo_Area', 'codigo');
    }
}
