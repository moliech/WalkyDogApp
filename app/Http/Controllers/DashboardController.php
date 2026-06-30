<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $metricas = [
            'paseos_activos' => 4,
            'mascotas_totales' => 18,
            'paseadores_disponibles' => 6,
            'alertas_sos' => 0
        ];

        return view('dashboard', compact('metricas'));
    }

    public function editarPerfil()
    {
        // Datos falsos del usuario autenticado actual (Simulado)
        $usuario = [
            'nombre' => 'Jhon Esteban Molina',
            'email' => 'esteban.molina@cotecnova.edu.co',
            'telefono' => '3123456789',
            'direccion' => 'Calle 10 # 4-50, Cartago, Valle'
        ];

        return view('perfil.editar', compact('usuario'));
    }
}
