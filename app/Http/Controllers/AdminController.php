<?php

namespace App\Http\Controllers;

use App\Models\PaseadorPerfil;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Listar todos los perfiles de paseadores para auditoría.
     */
    public function index()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Acceso restringido únicamente para administradores del sistema.');
        }

        $perfilesPendientes = PaseadorPerfil::with('user')->where('estado', 'pendiente')->get();
        $perfilesActivos = PaseadorPerfil::with('user')->where('estado', 'activo')->get();
        $perfilesRechazados = PaseadorPerfil::with('user')->where('estado', 'rechazado')->get();

        return view('admin.paseadores', compact('perfilesPendientes', 'perfilesActivos', 'perfilesRechazados'));
    }

    /**
     * Aprobar la postulación del paseador.
     */
    public function aprobar($id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403);
        }

        $perfil = PaseadorPerfil::findOrFail($id);
        $perfil->update(['estado' => 'activo']);

        return redirect()->route('admin.paseadores')
            ->with('success', 'Paseador aprobado exitosamente. Se ha cambiado su estado a ACTIVO.');
    }

    /**
     * Rechazar la postulación del paseador.
     */
    public function rechazar($id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403);
        }

        $perfil = PaseadorPerfil::findOrFail($id);
        $perfil->update(['estado' => 'rechazado']);

        return redirect()->route('admin.paseadores')
            ->with('success', 'Postulación de paseador rechazada.');
    }

    /**
     * Listar todos los usuarios clientes registrados.
     */
    public function usuarios()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Acceso restringido únicamente para administradores del sistema.');
        }

        // Obtener usuarios que NO son administradores y NO tienen perfil de paseador
        $usuarios = \App\Models\User::where('email', '!=', 'esteban.molina@cotecnova.edu.co')
            ->where('email', 'not like', '%admin%')
            ->whereDoesntHave('perfilPaseador')
            ->withCount('mascotas')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.usuarios', compact('usuarios'));
    }
}
