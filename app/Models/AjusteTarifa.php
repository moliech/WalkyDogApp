<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AjusteTarifa extends Model
{
    protected $table = 'ajustes_tarifas';

    protected $fillable = [
        'calificacion_minima',
        'porcentaje_maximo'
    ];
}
