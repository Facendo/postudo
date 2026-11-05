<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoordinadorPostgrado extends Model
{
    protected $table = 'coordinador_postgrado';

    protected $primaryKey = 'cedula';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Los atributos que se pueden asignar en masa.
     */
    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'correo',
    ];

    /**
     * Postgrados asignados a este coordinador (pivote con 'unique' en
     * codigo_postgrado: UN coordinador por postgrado, pero un coordinador
     * puede tener varios). Borrar el coordinador o el postgrado limpia
     * la asignación en cascada.
     */
    public function postgrados()
    {
        return $this->belongsToMany(
            Postgrado::class,
            'asignacion_coordinador_postgrado',
            'cedula',
            'codigo_postgrado'
        )->withTimestamps();
    }
}
