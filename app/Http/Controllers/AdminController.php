<?php

namespace App\Http\Controllers;

use App\Models\PaseadorPerfil;
use App\Models\AjusteTarifa;
use App\Models\Paseo;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $perfilesActivos = PaseadorPerfil::whereHas('user', function($q) { $q->where('rol', 'paseador'); })->with('user')->where('estado', 'activo')->get();
        $perfilesRechazados = PaseadorPerfil::whereHas('user', function($q) { $q->where('rol', 'paseador'); })->with('user')->where('estado', 'rechazado')->get();

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

        // Actualizar el rol del usuario a paseador para habilitar el perfil operativo
        $perfil->user->update(['rol' => 'paseador']);

        // Notificar al usuario aprobado
        $perfil->user->notify(new \App\Notifications\SystemNotification(
            "¡Felicidades! Tu postulación para ser paseador ha sido APROBADA. Ya puedes empezar a realizar paseos.",
            "postulacion_aprobada",
            route('perfil.editar')
        ));

        return redirect()->route('admin.paseadores')
            ->with('success', 'Paseador aprobado exitosamente. Se ha habilitado su perfil operativo.');
    }

    /**
     * Rechazar la postulación del paseador.
     */
    public function rechazar(\Illuminate\Http\Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'observacion_rechazo' => 'required|string|max:1000',
        ]);

        $perfil = PaseadorPerfil::findOrFail($id);
        
        // Al rechazar o desactivar, convertimos la cuenta a usuario básico (propietario)
        $perfil->user->update(['rol' => 'propietario']);
        
        $perfil->update([
            'estado' => 'rechazado',
            'observacion_rechazo' => $request->observacion_rechazo,
        ]);

        // Notificar al usuario rechazado con el motivo
        $perfil->user->notify(new \App\Notifications\SystemNotification(
            "Tu postulación/perfil de paseador ha sido desactivado/rechazado. Motivo: " . $request->observacion_rechazo,
            "postulacion_rechazada",
            route('perfil.editar')
        ));

        return redirect()->route('admin.paseadores')
            ->with('success', 'Paseador rechazado/desactivado correctamente y revertido a cliente básico.');
    }

    /**
     * Listar todos los usuarios clientes registrados.
     */
    public function usuarios()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Acceso restringido únicamente para administradores del sistema.');
        }

        // Obtener dueños/propietarios paginados
        $usuarios = \App\Models\User::where('rol', 'propietario')
            ->withCount('mascotas')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.usuarios', compact('usuarios'));
    }

    public function actualizarRol(Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Acceso restringido.');
        }

        $request->validate([
            'rol' => 'required|in:admin,paseador,propietario'
        ]);

        $user = \App\Models\User::findOrFail($id);
        
        // Evitar que el administrador principal se auto-cambie el rol si es el único
        if ($user->id === auth()->id() && $request->rol !== 'admin') {
            return back()->withErrors(['error' => 'No puedes remover tu propio rol de administrador.']);
        }

        $user->rol = $request->rol;
        $user->save();

        // Si cambia a paseador y no tiene perfil, se lo creamos en estado activo
        if ($request->rol === 'paseador' && !$user->perfilPaseador) {
            \App\Models\PaseadorPerfil::create([
                'user_id' => $user->id,
                'identificacion' => 'REGISTRADO-' . $user->id,
                'experiencia_meses' => 0,
                'calificacion_promedio' => 0.00,
                'estado' => 'activo',
                'porcentaje_recargo' => 0,
            ]);
        }

        // Si el usuario tenía rol simulado activo en sesión, limpiar para refrescar
        if (session('simulated_role') && $user->id === auth()->id()) {
            session()->forget('simulated_role');
        }

        return back()->with('success', '¡Rol del usuario ' . $user->nombres . ' ' . $user->apellidos . ' actualizado con éxito!');
    }

    /**
     * Listar todos los administradores registrados.
     */
    public function administradores()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Acceso restringido únicamente para administradores del sistema.');
        }

        $administradores = \App\Models\User::where('rol', 'admin')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.administradores', compact('administradores'));
    }

    /**
     * Listar y editar tarifas de paseos por tamaño de mascotas.
     */
    public function tarifas()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Acceso restringido únicamente para administradores del sistema.');
        }

        $tarifas = \App\Models\MascotaTamano::all();
        $ajustes = AjusteTarifa::firstOrCreate([], [
            'calificacion_minima' => 4.5,
            'porcentaje_maximo' => 20
        ]);

        return view('admin.tarifas', compact('tarifas', 'ajustes'));
    }

    /**
     * Actualizar tarifas en la base de datos.
     */
    public function actualizarTarifas(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($request->has('calificacion_minima')) {
            $request->merge([
                'calificacion_minima' => str_replace(',', '.', $request->calificacion_minima)
            ]);
        }

        $request->validate([
            'tarifas' => 'required|array',
            'tarifas.*.id' => 'required|exists:mascota_tamanos,id',
            'tarifas.*.tarifa_por_hora' => 'required|integer|min:0',
            'calificacion_minima' => 'required|numeric|min:0|max:5',
            'porcentaje_maximo' => 'required|integer|min:0|max:100',
        ]);

        foreach ($request->tarifas as $tData) {
            $tamano = \App\Models\MascotaTamano::findOrFail($tData['id']);
            $tamano->update(['tarifa_por_hora' => $tData['tarifa_por_hora']]);
        }

        $ajustes = AjusteTarifa::firstOrCreate([]);
        $ajustes->update([
            'calificacion_minima' => $request->calificacion_minima,
            'porcentaje_maximo' => $request->porcentaje_maximo,
        ]);

        return redirect()->route('admin.tarifas')
            ->with('success', 'Las tarifas y ajustes de recargos han sido actualizadas correctamente.');
    }

    /**
     * Historial de pagos global para auditoría del Administrador.
     */
    public function historialPagosGlobal(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Acceso restringido únicamente para administradores del sistema.');
        }

        $query = Paseo::with(['mascota.propietario', 'paseador', 'pago'])->whereHas('pago');

        // Filtros
        if ($request->filled('paseador_id')) {
            $query->where('paseador_id', $request->paseador_id);
        }
        if ($request->filled('propietario_id')) {
            $query->whereHas('mascota', function ($q) use ($request) {
                $q->where('propietario_id', $request->propietario_id);
            });
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        $paseos = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        // Listados para selectores de filtros
        $paseadores = User::has('perfilPaseador')->orderBy('nombres')->get();
        $propietarios = User::whereDoesntHave('perfilPaseador')
            ->where('email', '!=', 'esteban.molina@cotecnova.edu.co')
            ->where('email', 'not like', '%admin%')
            ->orderBy('nombres')
            ->get();

        return view('admin.pagos-historial', compact('paseos', 'paseadores', 'propietarios'));
    }

    /**
     * Exportar el listado global de pagos a PDF.
     */
    public function exportarPdfGlobal(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Acceso restringido únicamente para administradores del sistema.');
        }

        $query = Paseo::with(['mascota.propietario', 'paseador', 'pago'])->whereHas('pago');

        // Replicar filtros
        if ($request->filled('paseador_id')) {
            $query->where('paseador_id', $request->paseador_id);
        }
        if ($request->filled('propietario_id')) {
            $query->whereHas('mascota', function ($q) use ($request) {
                $q->where('propietario_id', $request->propietario_id);
            });
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        $paseos = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('pdf.historial-pagos-global', compact('paseos'))
                  ->setPaper('letter', 'portrait');

        return $pdf->download('reporte-pagos-global-' . now()->format('Y-m-d') . '.pdf');
    }
}
