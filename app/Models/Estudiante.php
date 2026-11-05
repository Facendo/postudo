<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes';

    protected $primaryKey = 'cedula';

    public $keyType = 'string';

    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'carrera',
        'especialidad',
        'codigo_mencion',
        'correo',
        'edad',
    ];

    public function pago()
    {
        return $this->hasMany(Pagos::class, 'cedula', 'cedula');
    }

    /**
     * Mención a la que está asociado el estudiante.
     * Nullable: los estudiantes antiguos pueden no tener mención asignada.
     */
    public function mencion()
    {
        return $this->belongsTo(Mencion::class, 'codigo_mencion', 'id_mencion');
    }
}
