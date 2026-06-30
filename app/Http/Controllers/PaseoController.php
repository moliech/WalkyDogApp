<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaseoController extends Controller
{
    public function monitoreo()
    {
        $paseoActivo = [
            'id' => 101,
            'paseador' => 'Carlos Mendoza',
            'mascota' => 'Toby',
            'estado' => 'En Progreso',
            'latitud' => 4.7508, 
            'longitud' => -75.9122
        ];

        return view('paseos.monitoreo', compact('paseoActivo'));
    }

    public function control()
    {
        $paseoAsignado = [
            'id' => 101,
            'mascota' => 'Toby',
            'propietario' => 'Esteban Molina',
            'token_qr' => 'walkydog_qr_secure_token_12345'
        ];

        return view('paseos.control', compact('paseoAsignado'));
    }

    public function simularPago($id)
    {
        $pagoSimulado = [
            'paseo_id' => $id,
            'mascota' => 'Toby',
            'horas' => 2,
            'tarifa_hora' => 12000,
            'total' => 24000
        ];

        return view('pagos.simulacion', compact('pagoSimulado'));
    }
}
