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
     * Se elimina 'codigo_pensum'.
     */
    protected $fillable = [
        'id_postgrado',
        'codigo_area',
        'nombre',
        'descripcion',
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
}
