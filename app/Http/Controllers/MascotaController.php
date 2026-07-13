<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Illuminate\Http\Request;

class MascotaController extends Controller
{
    /**
     * Listar las mascotas del usuario logueado con filtros.
     */
    public function index(Request $request)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            abort(403, 'Los administradores no gestionan mascotas propias.');
        }

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

        return view('mascotas.index', compact('mascotas'));
    }

    /**
     * Mostrar el formulario para registrar una nueva mascota.
     */
    public function create()
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            abort(403, 'Los administradores no gestionan mascotas propias.');
        }

        return view('mascotas.create');
    }

    /**
     * Guardar la nueva mascota.
     */
    public function store(Request $request)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            abort(403, 'Los administradores no gestionan mascotas propias.');
        }

        $request->validate([
            'nombre' => 'required|string|max:100',
            'raza' => 'required|string|max:100',
            'tamano' => 'required|in:Pequeño,Mediano,Grande',
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
        if (auth()->check() && auth()->user()->isAdmin()) {
            abort(403, 'Los administradores no gestionan mascotas propias.');
        }

        // Control de acceso: el propietario debe ser el mismo logueado
        if (auth()->check() && $mascota->propietario_id !== auth()->id()) {
            abort(403, 'No tienes autorización para editar esta mascota.');
        }

        return view('mascotas.edit', compact('mascota'));
    }

    /**
     * Actualizar los datos de la mascota.
     */
    public function update(Request $request, Mascota $mascota)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            abort(403, 'Los administradores no gestionan mascotas propias.');
        }
        if (auth()->check() && $mascota->propietario_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'nombre' => 'required|string|max:100',
            'raza' => 'required|string|max:100',
            'tamano' => 'required|in:Pequeño,Mediano,Grande',
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
        if (auth()->check() && auth()->user()->isAdmin()) {
            abort(403, 'Los administradores no gestionan mascotas propias.');
        }
        if (auth()->check() && $mascota->propietario_id !== auth()->id()) {
            abort(403);
        }

        $mascota->delete();

        return redirect()->route('mascotas.index')->with('success', 'Mascota eliminada correctamente.');
    }
}