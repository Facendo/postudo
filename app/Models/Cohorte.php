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

    protected $fillable = [
        'codigo_cohorte',
        'codigo_postgrado',
        'codigo_mencion',
        'fecha_inicio',
        'fecha_fin',
        'nro_de_cohorte',
    ];

    /**
     * La mención a la que pertenece la cohorte (creación anclada a menciones).
     * Nullable: si se borra la mención, la cohorte queda sin ella (set null).
     */
    public function mencion()
    {
        return $this->belongsTo(Mencion::class, 'codigo_mencion', 'id_mencion');
    }

    /**
     * Postgrado de la cohorte. Espejo derivado de la mención al guardar
     * (se conserva para inscripción e impactos de borrado).
     */
    public function postgrado()
    {
        return $this->belongsTo(Postgrado::class, 'codigo_postgrado', 'id_postgrado');
    }
}
