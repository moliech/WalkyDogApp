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

    /**
     * Scope para buscar por nombre de la mascota
     */
    public function scopeBuscarMascota($query, $nombre)
    {
        if (empty($nombre)) return $query;
        return $query->whereHas('mascota', function ($q) use ($nombre) {
            $q->where('nombre', 'LIKE', "%{$nombre}%");
        });
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeFiltrarEstado($query, $estado)
    {
        if (empty($estado)) return $query;
        return $query->where('estado', $estado);
    }

    /**
     * Scope para filtrar por rango de fechas de creación
     */
    public function scopeRangoFechas($query, $fechaInicio, $fechaFin)
    {
        if (!empty($fechaInicio)) {
            $query->whereDate('created_at', '>=', $fechaInicio);
        }
        if (!empty($fechaFin)) {
            $query->whereDate('created_at', '<=', $fechaFin);
        }
        return $query;
    }
}