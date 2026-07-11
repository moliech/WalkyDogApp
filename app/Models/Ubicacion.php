<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $table = 'ubicaciones'; // Forzamos la tabla en español
    public $timestamps = false; // Esta tabla no lleva columnas timestamps

    protected $fillable = ['paseo_id', 'latitud', 'longitud', 'registrado_at'];

    public function paseo()
    {
        return $this->belongsTo(Paseo::class, 'paseo_id');
    }
}