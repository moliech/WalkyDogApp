<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mascota extends Model
{
    use HasFactory;

    protected $fillable = ['propietario_id', 'nombre', 'raza', 'tamano', 'observaciones'];

    // Relación: La mascota pertenece a un propietario (User)
    public function propietario()
    {
        return $this->belongsTo(User::class, 'propietario_id');
    }

    // Relación: La mascota puede tener muchos paseos registrados
    public function paseos()
    {
        return $this->hasMany(Paseo::class, 'mascota_id');
    }

    // Scope para buscar por nombre o raza (Filtro)
    public function scopeBuscar($query, $termino)
    {
        return $query->where('nombre', 'LIKE', "%{$termino}%")
                     ->orWhere('raza', 'LIKE', "%{$termino}%");
    }

    // Scope para filtrar por tamaño (Filtro)
    public function scopePorTamano($query, $tamano)
    {
        return $query->where('tamano', $tamano);
    }

    /**
     * Determina si la mascota tiene un paseo activo o pendiente en curso.
     */
    public function tienePaseoActivo()
    {
        if ($this->relationLoaded('paseos')) {
            return $this->paseos
                ->whereIn('estado', ['pendiente', 'esperando_pago', 'programado', 'en_progreso'])
                ->isNotEmpty();
        }

        return $this->paseos()
            ->whereIn('estado', ['pendiente', 'esperando_pago', 'programado', 'en_progreso'])
            ->exists();
    }
}