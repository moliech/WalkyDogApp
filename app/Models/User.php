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


#[Fillable(['nombres', 'apellidos', 'email', 'password', 'telefono', 'direccion'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
}
