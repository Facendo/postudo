<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pagos extends Model
{
    use HasFactory;
    protected $table= 'pago';

    public function estudiante(){
        return $this->belongsTo(Estudiante::class,'cedula','cedula');
    }
}
