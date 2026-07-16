<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MascotaTamano extends Model
{
    use HasFactory;

    protected $table = 'mascota_tamanos';

    protected $fillable = [
        'nombre',
        'tarifa_por_hora',
    ];
}
