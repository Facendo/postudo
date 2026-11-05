<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mencion extends Model
{
    use HasFactory;

    protected $table = 'mencion';

    protected $primaryKey = 'id_mencion';

    protected $keyType = 'integer';

    public $incrementing = true;

    protected $fillable = [
        'id_mencion',
        'id_postgrado',
        'nombre',
    ];

    /**
     * La mención pertenece a un postgrado (Área -> Postgrado -> Mención).
     */
    public function postgrado()
    {
        return $this->belongsTo(Postgrado::class, 'id_postgrado', 'id_postgrado');
    }

    /**
     * Estudiantes asociados a esta mención.
     */
    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'codigo_mencion', 'id_mencion');
    }

    /**
     * Cohortes ancladas a esta mención (no se pueden crear cohortes sueltas).
     */
    public function cohortes()
    {
        return $this->hasMany(Cohorte::class, 'codigo_mencion', 'id_mencion');
    }
}
