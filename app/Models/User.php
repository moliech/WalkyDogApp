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


#[Fillable(['nombres', 'apellidos', 'email', 'password', 'telefono', 'direccion', 'username', 'rol', 'avatar', 'otp_code', 'otp_expires_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Determina si el usuario tiene el rol Administrador.
     */
    public function isAdmin()
    {
        if (request()->hasSession() && session()->has('simulated_role')) {
            return session('simulated_role') === 'admin';
        }
        return $this->rol === 'admin';
    }

     /**
     * Determina si el usuario tiene el rol Paseador.
     */
    public function isPaseador()
    {
        if (request()->hasSession() && session()->has('simulated_role')) {
            return session('simulated_role') === 'paseador';
        }
        return $this->rol === 'paseador' && $this->perfilPaseador && $this->perfilPaseador->estado === 'activo';
    }

     /**
     * Determina si el usuario tiene el rol Propietario.
     */
    public function isPropietario()
    {
        if (request()->hasSession() && session()->has('simulated_role')) {
            return session('simulated_role') === 'propietario';
        }
        return $this->rol === 'propietario';
    }

    public function getActiveRole()
    {
        if (request()->hasSession() && session()->has('simulated_role')) {
            return session('simulated_role');
        }
        return $this->rol;
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

    public function generateOtp(): string
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->otp_code = $otp;
        $this->otp_expires_at = now()->addMinutes(5);
        $this->save();
        return $otp;
    }

    public function verifyOtp(string $otp): bool
    {
        // OTP "quemado" estático para demostración rápida en clase
        if ($otp === '123456') {
            return true;
        }

        if (!$this->otp_code || !$this->otp_expires_at) {
            return false;
        }
        if (now()->gt($this->otp_expires_at)) {
            return false;
        }
        return hash_equals($this->otp_code, $otp);
    }
}
