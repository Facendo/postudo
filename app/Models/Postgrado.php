<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postgrado extends Model
{
    use HasFactory;

    protected $table = 'postgrado';
    protected $primaryKey = 'Id_postgrado';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    /**
     * Los atributos que se pueden asignar en masa.
     * Se elimina 'codigo_pensum'.
     */
    protected $fillable = [
        'Id_postgrado',
        'codigo_especialidad',
        'nombre',
        'descripcion',
        'duracion',
    ];

    /**
     * Define la relación con el modelo Especialidad (Uno a Muchos Inversa).
     */
    public function especialidad()
    {
        return $this->belongsTo(Especialidades::class, 'codigo_especialidad', 'codigo_especialidad');
    }

    /**
     * Define la relación Muchos a Muchos con el modelo Area.
     */
    public function areas()
    {
        // Se asume una tabla pivote llamada 'area_postgrado'
        // con las columnas 'postgrado_id' y 'area_id'.
        return $this->belongsToMany(Area::class, 'area_postgrado', 'postgrado_id', 'area_id');
    }

    /**
     * Define la relación Uno a Muchos con el modelo Cohorte.
     */
    public function cohortes()
    {
        return $this->hasMany(Cohorte::class, 'Id_postgrado', 'Id_postgrado');
    }
}