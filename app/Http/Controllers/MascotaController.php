<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MascotaController extends Controller
{
    public function index()
    {
        $mascotas = [
            ['id' => 1, 'nombre' => 'Toby', 'raza' => 'Golden Retriever', 'tamano' => 'Grande'],
            ['id' => 2, 'nombre' => 'Luna', 'raza' => 'Pug', 'tamano' => 'Pequeño'],
            ['id' => 3, 'nombre' => 'Rambo', 'raza' => 'Pastor Alemán', 'tamano' => 'Grande'],
        ];

        return view('mascotas.index', compact('mascotas'));
    }
}
