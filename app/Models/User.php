<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;


#[Fillable(['nombres', 'apellidos', 'email', 'password', 'telefono', 'direccion', 'username', 'rol'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Determina si el usuario tiene el rol Administrador.
     */
    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    /**
     * Determina si el usuario tiene el rol Paseador.
     */
    public function isPaseador(): bool
    {
        return $this->rol === 'paseador';
    }

    /**
     * Determina si el usuario tiene el rol Propietario.
     */
    public function isPropietario(): bool
    {
        return $this->rol === 'propietario';
    }

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

    // Relación: Un Propietario tiene muchas mascotas
    public function mascotas()
    {
        return $this->hasMany(Mascota::class, 'propietario_id');
    }

    // Relación: Un Paseador tiene un único perfil de auditoría
    public function perfilPaseador()
    {
        return $this->hasOne(PaseadorPerfil::class, 'user_id');
    }

    // Relación: Un Paseador tiene muchos paseos asignados
    public function paseos()
    {
        return $this->hasMany(Paseo::class, 'paseador_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
