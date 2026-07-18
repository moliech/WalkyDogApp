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
