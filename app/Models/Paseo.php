<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paseo extends Model
{
    use HasFactory;

    protected $fillable = [
        'paseador_id', 'mascota_id', 'estado', 'token_qr', 
        'hora_inicio', 'hora_fin', 'calificacion'
    ];

    // Relación: El paseo tiene un paseador (User)
    public function paseador()
    {
        return $this->belongsTo(User::class, 'paseador_id');
    }

    // Relación: El paseo es para una mascota
    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }

    // Relación: Un paseo genera muchos puntos de geolocalización
    public function ubicaciones()
    {
        return $this->hasMany(Ubicacion::class, 'paseo_id');
    }

    // Relación: Un paseo tiene un único pago
    public function pago()
    {
        return $this->hasOne(Pago::class, 'paseo_id');
    }

    // Relación: Un paseo puede reportar muchas novedades/incidentes
    public function novedades()
    {
        return $this->hasMany(Novedad::class, 'paseo_id');
    }
}