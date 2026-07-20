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
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Validación defensiva para evitar caídas fatales si expira la sesión
        if (!$user) {
            return redirect()->route('login');
        }
        
        
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
            $esPaseador = $user->isPaseador();

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
                    ->whereIn('estado', ['en_progreso', 'programado'])
                    ->whereHas('mascota', function ($q) use ($user) {
                        $q->where('propietario_id', $user->id);
                    })->get();
                
                // Filtro estricto: Solo las mascotas del dueño activo
                $mascotas = Mascota::where('propietario_id', $user->id)->get();
                
                $paseadores = PaseadorPerfil::with('user')->where('estado', 'activo')->get();
                
                $novedades = Novedad::whereHas('paseo.mascota', function ($q) use ($user) {
                    $q->where('propietario_id', $user->id);
                })->with('paseo.mascota')->orderBy('registrado_at', 'desc')->get();

                $paseosPorCalificar = Paseo::with(['mascota', 'paseador'])
                    ->where('estado', 'finalizado')
                    ->whereNull('calificacion')
                    ->whereHas('mascota', function ($q) use ($user) {
                        $q->where('propietario_id', $user->id);
                    })->get();

                $paseosPendientesPago = Paseo::with(['mascota', 'paseador'])
                    ->where('estado', 'esperando_pago')
                    ->whereHas('mascota', function ($q) use ($user) {
                        $q->where('propietario_id', $user->id);
                    })->get();
            }
        }

        // Definir colección vacía si no es propietario
        if (!isset($paseosPorCalificar)) {
            $paseosPorCalificar = collect();
        }
        if (!isset($paseosPendientesPago)) {
            $paseosPendientesPago = collect();
        }

        return view('dashboard', compact('metricas', 'paseosActivos', 'mascotas', 'paseadores', 'novedades', 'paseosPorCalificar', 'paseosPendientesPago'));
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
            'es_paseador' => $user->isPaseador(),
            'identificacion' => $user->perfilPaseador->identificacion ?? '',
            'experiencia_meses' => $user->perfilPaseador->experiencia_meses ?? '',
            'estado_paseador' => $user->perfilPaseador->estado ?? '',
            'calificacion_promedio' => $user->perfilPaseador->calificacion_promedio ?? 5.00,
            'porcentaje_recargo' => $user->perfilPaseador->porcentaje_recargo ?? 0,
        ];

        return view('perfil.editar', compact('usuario'));
    }

    public function switchRole(Request $request)
    {
        $request->validate([
            'simulated_role' => 'required|in:admin,paseador,propietario'
        ]);

        $role = $request->simulated_role;
        
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($role === $user->rol) {
            session()->forget('simulated_role');
        } else {
            // Si cambia a paseador y no tiene perfil, se lo creamos en estado activo para pruebas
            if ($role === 'paseador' && !$user->perfilPaseador) {
                \App\Models\PaseadorPerfil::create([
                    'user_id' => $user->id,
                    'identificacion' => 'SIMULADOR-' . $user->id,
                    'experiencia_meses' => 12,
                    'calificacion_promedio' => 5.0,
                    'estado' => 'activo',
                    'porcentaje_recargo' => 10,
                ]);
            }
            session(['simulated_role' => $role]);
        }

        return redirect()->route('dashboard')->with('success', '¡Cambiado a vista de: ' . ucfirst($role) . '!');
    }
}