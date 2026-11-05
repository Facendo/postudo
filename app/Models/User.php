<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'cedula',
        'foto_perfil',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Fila del rol de coordinador de postgrado (por cédula). Solo existe si
     * la cédula fue creada por el coordinador general; alimenta el filtrado
     * de «sus» postgrados en los controladores.
     */
    public function coordinadorPostgrado()
    {
        return $this->belongsTo(CoordinadorPostgrado::class, 'cedula', 'cedula');
    }

    /**
     * Notificaciones in-app recibidas (tabla `notificacion`).
     */
    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'user_id', 'id');
    }
}
