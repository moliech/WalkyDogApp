<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MascotaController extends Controller
{
    /**
     * Listar las mascotas del usuario logueado con filtros.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Mascota::class);

        // 1. Iniciamos la consulta cargando la relación con el propietario
        $query = Mascota::with('propietario');

        // 2. Si el usuario está autenticado, solo mostramos sus propias mascotas
        if (auth()->check()) {
            $query->where('propietario_id', auth()->id());
        }

        // 3. Aplicamos los Scopes que creamos en el modelo Mascota
        if ($request->filled('buscar')) {
            $query->buscar($request->buscar);
        }

        if ($request->filled('tamano')) {
            $query->porTamano($request->tamano);
        }

        // 4. Obtenemos los resultados ordenados
        $mascotas = $query->orderBy('created_at', 'desc')->get();
        $tamanos = \App\Models\MascotaTamano::all();

        return view('mascotas.index', compact('mascotas', 'tamanos'));
    }

    /**
     * Mostrar el formulario para registrar una nueva mascota.
     */
    public function create()
    {
        Gate::authorize('create', Mascota::class);

        $tamanos = \App\Models\MascotaTamano::all();
        return view('mascotas.create', compact('tamanos'));
    }

    /**
     * Guardar la nueva mascota.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Mascota::class);

        $tamanosList = \App\Models\MascotaTamano::pluck('nombre')->toArray();
        $request->validate([
            'nombre' => 'required|string|max:100',
            'raza' => 'required|string|max:100',
            'tamano' => 'required|in:' . implode(',', $tamanosList),
            'observaciones' => 'nullable|string|max:500',
        ]);

        // Creamos la mascota asociándola dinámicamente al ID del usuario autenticado
        Mascota::create([
            'propietario_id' => auth()->id() ?? 1, // Fallback por si no hay sesión activa
            'nombre' => $request->nombre,
            'raza' => $request->raza,
            'tamano' => $request->tamano,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('mascotas.index')->with('success', '¡Mascota registrada exitosamente! 🐾');
    }

    /**
     * Mostrar el formulario de edición de una mascota.
     */
    public function edit(Mascota $mascota)
    {
        Gate::authorize('update', $mascota);

        $tamanos = \App\Models\MascotaTamano::all();
        return view('mascotas.edit', compact('mascota', 'tamanos'));
    }

    /**
     * Actualizar los datos de la mascota.
     */
    public function update(Request $request, Mascota $mascota)
    {
        Gate::authorize('update', $mascota);

        $tamanosList = \App\Models\MascotaTamano::pluck('nombre')->toArray();
        $request->validate([
            'nombre' => 'required|string|max:100',
            'raza' => 'required|string|max:100',
            'tamano' => 'required|in:' . implode(',', $tamanosList),
            'observaciones' => 'nullable|string|max:500',
        ]);

        $mascota->update($request->all());

        return redirect()->route('mascotas.index')->with('success', 'Mascota actualizada correctamente.');
    }

    /**
     * Eliminar la mascota.
     */
    public function destroy(Mascota $mascota)
    {
        Gate::authorize('delete', $mascota);

        $mascota->delete();

        return redirect()->route('mascotas.index')->with('success', 'Mascota eliminada correctamente.');
    }
}