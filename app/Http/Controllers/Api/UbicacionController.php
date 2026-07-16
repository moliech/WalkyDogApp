<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paseo;
use App\Models\Ubicacion;
use Illuminate\Http\Request;

class UbicacionController extends Controller
{
    /**
     * POST: Almacena una nueva coordenada GPS transmitida por el celular del paseador.
     */
    public function store(Request $request, $paseo_id)
    {
        $paseo = Paseo::findOrFail($paseo_id);
        $user = auth()->user();

        // Seguridad: Solo el paseador asignado al paseo puede transmitir coordenadas
        if (!$user || $paseo->paseador_id !== $user->id) {
            return response()->json(['error' => 'No autorizado para transmitir geolocalización en este paseo.'], 403);
        }

        // El paseo debe estar estrictamente en curso
        if ($paseo->estado !== 'en_progreso') {
            return response()->json(['error' => 'El paseo no está activo en este momento.'], 400);
        }

        $validated = $request->validate([
            'latitud' => ['required', 'numeric', 'between:-90,90'],
            'longitud' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $ubicacion = Ubicacion::create([
            'paseo_id' => $paseo->id,
            'latitud' => $validated['latitud'],
            'longitud' => $validated['longitud'],
            'registrado_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Coordenada guardada exitosamente.',
            'data' => $ubicacion
        ], 201);
    }

    /**
     * GET: Retorna todas las coordenadas del paseo para dibujar la ruta en el mapa del dueño.
     */
    public function index($paseo_id)
    {
        $paseo = Paseo::with('mascota')->findOrFail($paseo_id);
        $user = auth()->user();

        // Seguridad: Solo el dueño de la mascota, el paseador asignado o el administrador pueden consultar la ruta
        if (!$user || (!$user->isAdmin() && $user->id !== $paseo->paseador_id && $user->id !== $paseo->mascota->propietario_id)) {
            return response()->json(['error' => 'No autorizado para consultar esta ruta.'], 403);
        }

        // Obtener el historial ordenado por fecha de captura
        $coordenadas = Ubicacion::where('paseo_id', $paseo->id)
            ->orderBy('registrado_at', 'asc')
            ->get(['id', 'latitud', 'longitud', 'registrado_at']);

        return response()->json([
            'paseo_id' => $paseo->id,
            'estado' => $paseo->estado,
            'coordenadas' => $coordenadas
        ], 200);
    }
}