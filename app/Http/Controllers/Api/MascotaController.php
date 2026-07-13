<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mascota;

class MascotaController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth('api')->user();

        if ($user->isAdmin()) {
            // El Administrador audita todas las mascotas registradas en el sistema
            $mascotas = Mascota::with('propietario')->get();
        } else {
            // El Cliente ve únicamente sus propias mascotas
            $mascotas = Mascota::where('propietario_id', $user->id)->get();
        }

        return response()->json($mascotas, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth('api')->user();

        // Bloqueo: El Administrador no puede registrar mascotas propias
        if ($user->isAdmin()) {
            return response()->json([
                'error' => 'Acción no permitida',
                'message' => 'Los administradores no pueden registrar mascotas en el sistema.'
            ], 403);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'raza' => 'required|string|max:100',
            'tamano' => 'required|in:Pequeño,Mediano,Grande',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $mascota = Mascota::create([
            'propietario_id' => $user->id,
            'nombre' => $validated['nombre'],
            'raza' => $validated['raza'],
            'tamano' => $validated['tamano'],
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        return response()->json($mascota, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        /** @var \App\Models\User $user */
        $user = auth('api')->user();
        $mascota = Mascota::with('propietario')->findOrFail($id);

        // Seguridad: El usuario debe ser el propietario o administrador
        if (!$user->isAdmin() && $mascota->propietario_id !== $user->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return response()->json($mascota, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        /** @var \App\Models\User $user */
        $user = auth('api')->user();
        $mascota = Mascota::findOrFail($id);

        // Bloqueo: El Admin no puede modificar mascotas, y un cliente solo las propias
        if ($user->isAdmin() || $mascota->propietario_id !== $user->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:100',
            'raza' => 'sometimes|string|max:100',
            'tamano' => 'sometimes|in:Pequeño,Mediano,Grande',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $mascota->update($validated);

        return response()->json($mascota->fresh(), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        /** @var \App\Models\User $user */
        $user = auth('api')->user();
        $mascota = Mascota::findOrFail($id);

        // Bloqueo: El Admin no puede borrar mascotas, y un cliente solo las propias
        if ($user->isAdmin() || $mascota->propietario_id !== $user->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $mascota->delete();

        return response()->json(null, 204);
    }
}
