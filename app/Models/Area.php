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

    /**
     * Postgrados que dependen de esta área (Área -> Postgrado -> Mención).
     */
    public function postgrados()
    {
        return $this->hasMany(Postgrado::class, 'codigo_area', 'codigo');
    }
}
