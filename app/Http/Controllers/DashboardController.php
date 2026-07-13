<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Models\Paseo;
use App\Models\PaseadorPerfil;
use App\Models\Novedad;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Verificamos si es administrador
        $esAdmin = $user->isAdmin();

        if ($esAdmin) {
            // El Administrador ve las métricas y listados globales
            $metricas = [
                'paseos_activos' => Paseo::where('estado', 'en_progreso')->count(),
                'mascotas_totales' => Mascota::count(),
                'paseadores_disponibles' => PaseadorPerfil::where('estado', 'activo')->count(),
                'alertas_sos' => Novedad::count()
            ];

            $paseosActivos = Paseo::with(['mascota', 'paseador'])->where('estado', 'en_progreso')->get();
            $mascotas = Mascota::with('propietario')->get();
            $paseadores = PaseadorPerfil::with('user')->where('estado', 'activo')->get();
            $novedades = Novedad::with('paseo.mascota')->orderBy('registrado_at', 'desc')->take(10)->get();
        } else {
            // Si es un usuario de negocio, determinamos su rol
            $esPaseador = $user->perfilPaseador ? true : false;

            if ($esPaseador) {
                // El Paseador ve datos referentes a sus servicios
                $metricas = [
                    'paseos_activos' => Paseo::where('paseador_id', $user->id)->where('estado', 'en_progreso')->count(),
                    'mascotas_totales' => Mascota::whereHas('paseos', function($q) use ($user) {
                        $q->where('paseador_id', $user->id);
                    })->distinct()->count(),
                    'paseadores_disponibles' => PaseadorPerfil::where('estado', 'activo')->count(),
                    'alertas_sos' => Novedad::whereHas('paseo', function($q) use ($user) {
                        $q->where('paseador_id', $user->id);
                    })->count()
                ];

                $paseosActivos = Paseo::with(['mascota', 'paseador'])->where('paseador_id', $user->id)->where('estado', 'en_progreso')->get();
                $mascotas = Mascota::whereHas('paseos', function($q) use ($user) {
                    $q->where('paseador_id', $user->id);
                })->with('propietario')->distinct()->get();
                $paseadores = PaseadorPerfil::with('user')->where('estado', 'activo')->get();
                $novedades = Novedad::whereHas('paseo', function($q) use ($user) {
                    $q->where('paseador_id', $user->id);
                })->with('paseo.mascota')->orderBy('registrado_at', 'desc')->get();
            } else {
                // El Propietario ve ÚNICAMENTE sus propias mascotas, paseos activos y alertas
                $metricas = [
                    'paseos_activos' => Paseo::where('estado', 'en_progreso')
                        ->whereHas('mascota', function ($q) use ($user) {
                            $q->where('propietario_id', $user->id);
                        })->count(),
                    'mascotas_totales' => Mascota::where('propietario_id', $user->id)->count(),
                    'paseadores_disponibles' => PaseadorPerfil::where('estado', 'activo')->count(),
                    'alertas_sos' => Novedad::whereHas('paseo.mascota', function ($q) use ($user) {
                        $q->where('propietario_id', $user->id);
                    })->count()
                ];

                $paseosActivos = Paseo::with(['mascota', 'paseador'])
                    ->where('estado', 'en_progreso')
                    ->whereHas('mascota', function ($q) use ($user) {
                        $q->where('propietario_id', $user->id);
                    })->get();
                
                // Filtro estricto: Solo las mascotas del dueño activo
                $mascotas = Mascota::where('propietario_id', $user->id)->get();
                
                $paseadores = PaseadorPerfil::with('user')->where('estado', 'activo')->get();
                
                $novedades = Novedad::whereHas('paseo.mascota', function ($q) use ($user) {
                    $q->where('propietario_id', $user->id);
                })->with('paseo.mascota')->orderBy('registrado_at', 'desc')->get();
            }
        }

        return view('dashboard', compact('metricas', 'paseosActivos', 'mascotas', 'paseadores', 'novedades'));
    }

    public function editarPerfil()
    {
        // Obtenemos los datos del usuario logueado en la sesión
        $user = auth()->user();
        
        $usuario = [
            'nombre' => $user->nombres . ' ' . $user->apellidos,
            'email' => $user->email,
            'telefono' => $user->telefono ?? 'No registrado',
            'direccion' => $user->direccion,
            'es_paseador' => $user->perfilPaseador ? true : false,
            'identificacion' => $user->perfilPaseador->identificacion ?? '',
            'experiencia_meses' => $user->perfilPaseador->experiencia_meses ?? '',
            'estado_paseador' => $user->perfilPaseador->estado ?? ''
        ];

        return view('perfil.editar', compact('usuario'));
    }
}