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
        // Validación básica de administrador: para pruebas, autorizamos a esteban.molina
        // o a cualquier correo que contenga "admin".
        if (!auth()->check() || (auth()->user()->email !== 'esteban.molina@cotecnova.edu.co' && !str_contains(auth()->user()->email, 'admin'))) {
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
        if (!auth()->check() || (auth()->user()->email !== 'esteban.molina@cotecnova.edu.co' && !str_contains(auth()->user()->email, 'admin'))) {
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
        if (!auth()->check() || (auth()->user()->email !== 'esteban.molina@cotecnova.edu.co' && !str_contains(auth()->user()->email, 'admin'))) {
            abort(403);
        }

        $perfil = PaseadorPerfil::findOrFail($id);
        $perfil->update(['estado' => 'rechazado']);

        return redirect()->route('admin.paseadores')
            ->with('success', 'Postulación de paseador rechazada.');
    }
}
