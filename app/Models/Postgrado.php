<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postgrado extends Model
{
    use HasFactory;

    protected $table = 'postgrado';

    protected $primaryKey = 'id_postgrado';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    /**
     * Los atributos que se pueden asignar en masa.
     * Se elimina 'codigo_pensum' y 'descripcion' (columna retirada 2026-10-26).
     */
    protected $fillable = [
        'id_postgrado',
        'codigo_area',
        'nombre',
        'duracion',
        'nro_cohortes',
    ];

    /**
     * Área al que pertenece el postgrado (Área -> Postgrado -> Mención).
     */
    public function area()
    {
        return $this->belongsTo(Area::class, 'codigo_area', 'codigo');
    }

    /**
     * Menciones que dependen de este postgrado.
     */
    public function menciones()
    {
        return $this->hasMany(Mencion::class, 'id_postgrado', 'id_postgrado');
    }

    /**
     * Define la relación Uno a Muchos con el modelo Cohorte.
     */
    public function cohortes()
    {
        return $this->hasMany(Cohorte::class, 'codigo_postgrado', 'id_postgrado');
    }

    /**
     * Postgrados con AL MENOS UNA cohorte cuya ventana de preinscripción
     * contiene hoy: son los que muestran botón en la portada y aceptan el
     * formulario público (2026-11-11).
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Postgrado>
     */
    public static function conPreinscripcionAbierta()
    {
        return static::whereHas('cohortes', fn ($query) => $query->preinscripcionAbierta())
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Coordinador de postgrado asignado (pivote con 'unique': UN coordinador
     * por postgrado, como máximo uno aquí).
     */
    public function coordinadores()
    {
        return $this->belongsToMany(
            CoordinadorPostgrado::class,
            'asignacion_coordinador_postgrado',
            'codigo_postgrado',
            'cedula'
        )->withTimestamps();
    }
}
