<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = ['paseo_id', 'monto', 'estado_pago'];

    public function paseo()
    {
        return $this->belongsTo(Paseo::class, 'paseo_id');
    }
}