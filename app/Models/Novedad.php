<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Novedad extends Model
{
    protected $table = 'novedades'; // Forzamos la tabla en español
    public $timestamps = false;

    protected $fillable = ['paseo_id', 'detalle', 'registrado_at'];

    public function paseo()
    {
        return $this->belongsTo(Paseo::class, 'paseo_id');
    }
}