<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaseadorPerfil extends Model
{
    use HasFactory;

    protected $table = 'paseadores_perfiles'; // Forzamos la tabla en español

    protected $fillable = [
        'user_id', 'identificacion', 'experiencia_meses', 
        'calificacion_promedio', 'estado', 'documento_soporte'
    ];

    // Relación: El perfil pertenece a un usuario (User)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}