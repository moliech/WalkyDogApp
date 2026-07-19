<?php

namespace App\Http\Controllers;

use App\Models\Paseo;
use App\Models\Pago;
use App\Models\Novedad;
use App\Models\Ubicacion;
use App\Models\Mascota;
use App\Models\User;
use App\Notifications\PaseoNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class PaseoController extends Controller
{
    /**
     * Monitoreo en tiempo real (Mapa de Leaflet.js para Propietarios, Paseadores y Administradores)
     */
    public function monitoreo(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Cargar paseos activos según el rol
        if ($user->isAdmin()) {
            // El Administrador ve todos los paseos activos del sistema
            $paseosActivos = Paseo::with(['mascota', 'paseador', 'ubicaciones', 'novedades'])
                ->where('estado', 'en_progreso')
                ->get();
        } elseif ($user->perfilPaseador) {
            // El Paseador ve los paseos activos que él está realizando
            $paseosActivos = Paseo::with(['mascota', 'paseador', 'ubicaciones', 'novedades'])
                ->where('paseador_id', $user->id)
                ->where('estado', 'en_progreso')
                ->get();
        } else {
            // El Propietario ve los paseos activos de sus mascotas
            $paseosActivos = Paseo::with(['mascota', 'paseador', 'ubicaciones', 'novedades'])
                ->whereHas('mascota', function ($query) use ($user) {
                    $query->where('propietario_id', $user->id);
                })
                ->where('estado', 'en_progreso')
                ->get();
        }

        // Determinamos cuál paseo activo mostrar en detalle en el mapa
        $paseoActivo = null;
        if ($paseosActivos->isNotEmpty()) {
            $selectedId = $request->query('paseo_id');
            $paseoActivo = $selectedId 
                ? $paseosActivos->firstWhere('id', $selectedId) 
                : $paseosActivos->first();

            // Si el ID seleccionado no pertenece al listado autorizado, cargamos el primero
            if (!$paseoActivo) {
                $paseoActivo = $paseosActivos->first();
            }
        }

        return view('paseos.monitoreo', compact('paseosActivos', 'paseoActivo'));
    }

    /**
     * Agenda del Paseador (Donde gestiona sus servicios asignados)
     */
    public function control()
    {
        if (auth()->check() && !auth()->user()->perfilPaseador) {
            abort(403, 'Acción exclusiva para paseadores.');
        }

        // Listamos los paseos asignados al paseador logueado
        $paseosAsignados = Paseo::with(['mascota.propietario', 'pago'])
            ->where('paseador_id', auth()->id())
            ->whereIn('estado', ['pendiente', 'esperando_pago', 'programado', 'en_progreso'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('paseos.control', compact('paseosAsignados'));
    }

    /**
     * Registrar la solicitud de un nuevo paseo en la BD
     */
    public function agendar(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();


        if (auth()->check() && ($user->isAdmin() || auth()->user()->perfilPaseador)) {
            abort(403, 'Acción exclusiva para propietarios de mascotas.');
        }

        $request->validate([
            'mascota_id' => 'required|exists:mascotas,id',
            'paseador_id' => 'required|exists:users,id',
            'duracion' => 'required|integer|in:1,2,3',
        ]);

        // 1. Validamos que la mascota pertenezca al usuario logueado
        $mascota = Mascota::findOrFail($request->mascota_id);
        if ($mascota->propietario_id !== auth()->id()) {
            abort(403, 'No tienes autorización para agendar un paseo para esta mascota.');
        }

        // 2. Creamos el paseo en estado 'pendiente' (Espera de aceptación)
        $paseo = Paseo::create([
            'paseador_id' => $request->paseador_id,
            'mascota_id' => $request->mascota_id,
            'estado' => 'pendiente',
            'token_qr' => 'walkydog_qr_' . Str::random(16),
            'hora_inicio' => null,
            'hora_fin' => null,
            'calificacion' => null,
        ]);

        // 3. Generamos el cobro simulado asociado en estado 'pending' basado en la tarifa del tamaño y el recargo del paseador
        $tamanoObj = \App\Models\MascotaTamano::where('nombre', $mascota->tamano)->first();
        $tarifaPorHora = $tamanoObj ? $tamanoObj->tarifa_por_hora : 12000;

        // Calcular recargo del paseador destacado si aplica
        $recargoPaseador = 0;
        $paseadorUser = User::find($request->paseador_id);
        if ($paseadorUser && $paseadorUser->perfilPaseador) {
            $perfil = $paseadorUser->perfilPaseador;
            $ajustes = \App\Models\AjusteTarifa::first();
            $minCalificacion = $ajustes ? $ajustes->calificacion_minima : 4.5;
            $maxPorcentaje = $ajustes ? $ajustes->porcentaje_maximo : 20;

            if ($perfil->calificacion_promedio >= $minCalificacion && $perfil->porcentaje_recargo > 0) {
                // Capped al máximo permitido por el admin por seguridad
                $porcentaje = min($perfil->porcentaje_recargo, $maxPorcentaje);
                $recargoPaseador = ($tarifaPorHora * $porcentaje) / 100;
            }
        }

        $monto = $request->duracion * ($tarifaPorHora + $recargoPaseador);
        
        Pago::create([
            'paseo_id' => $paseo->id,
            'monto' => $monto,
            'estado_pago' => 'pending',
        ]);

        // Notificar al paseador en pantalla
        $paseadorUser = User::find($request->paseador_id);
        if ($paseadorUser) {
            $paseadorUser->notify(new PaseoNotification(
                $paseo,
                "Tienes una nueva solicitud de paseo para {$mascota->nombre}.",
                'solicitado',
                route('paseos.control', [], false)
            ));
        }

        return redirect()->route('dashboard')
            ->with('success', '¡Solicitud de paseo registrada! En cuanto el paseador confirme su disponibilidad, podrás proceder al pago.');
    }

    /**
     * Mostrar la factura simulada antes del pago
     */
    public function simularPago($paseo_id)
    {
         /** @var \App\Models\User $user */
        $user = auth()->user();

        if (auth()->check() && ($user->isAdmin() || auth()->user()->perfilPaseador)) {
            abort(403, 'Acción exclusiva para propietarios de mascotas.');
        }

        $paseo = Paseo::with(['mascota', 'pago', 'paseador'])->findOrFail($paseo_id);

        // Validamos que el dueño del paseo sea el usuario logueado
        if ($paseo->mascota->propietario_id !== auth()->id()) {
            abort(403);
        }

        if ($paseo->estado !== 'esperando_pago') {
            abort(403, 'El paseo no está en espera de pago.');
        }

        return view('pagos.simulacion', compact('paseo'));
    }

    /**
     * Confirmar el pago simulado
     */
    public function confirmarPago(Request $request, $paseo_id)
    {
         /** @var \App\Models\User $user */
        $user = auth()->user();

        if (auth()->check() && ($user->isAdmin() || auth()->user()->perfilPaseador)) {
            abort(403, 'Acción exclusiva para propietarios de mascotas.');
        }

        $paseo = Paseo::with(['mascota', 'pago'])->findOrFail($paseo_id);

        if ($paseo->mascota->propietario_id !== auth()->id()) {
            abort(403);
        }

        if ($paseo->estado !== 'esperando_pago') {
            abort(403, 'El paseo no está en espera de pago.');
        }

        // Aprobamos el pago y cambiamos el estado del paseo a 'programado'
        $paseo->pago->update([
            'estado_pago' => 'approved',
        ]);
        
        $paseo->update([
            'estado' => 'programado',
        ]);

        // Notificar al paseador en pantalla
        $paseadorUser = User::find($paseo->paseador_id);
        if ($paseadorUser) {
            $paseadorUser->notify(new PaseoNotification(
                $paseo,
                "El paseo solicitado para {$paseo->mascota->nombre} ha sido pagado y está programado.",
                'pagado',
                route('paseos.control', [], false)
            ));
        }

        return redirect()->route('dashboard')
            ->with('success', '¡Pago simulado aprobado con éxito! Tu paseo ha sido confirmado y está listo para ser ejecutado.');
    }

    /**
     * Operación del Paseador: Iniciar el recorrido
     */
    public function iniciarPaseo($id)
    {
        if (auth()->check() && !auth()->user()->perfilPaseador) {
            abort(403, 'Acción exclusiva para paseadores.');
        }

        $paseo = Paseo::findOrFail($id);

        // Validamos que el paseador asignado sea el logueado
        if ($paseo->paseador_id !== auth()->id()) {
            abort(403);
        }

        // Cambiamos el estado a 'en_progreso' y colocamos la hora de inicio
        $paseo->update([
            'estado' => 'en_progreso',
            'hora_inicio' => now(),
        ]);

        // Creamos la primera coordenada inicial para simular geolocalización (en Cartago, Valle)
        Ubicacion::create([
            'paseo_id' => $paseo->id,
            'latitud' => 4.7508,
            'longitud' => -75.9122,
        ]);

        // Despachamos el evento de alerta de correo electrónico
        $paseo->load(['mascota.propietario', 'paseador']);
        event(new \App\Events\PaseoIniciado($paseo));

        // Notificar al propietario en pantalla
        $propietarioUser = $paseo->mascota->propietario;
        if ($propietarioUser) {
            $propietarioUser->notify(new PaseoNotification(
                $paseo,
                "¡Tu mascota {$paseo->mascota->nombre} ha iniciado su paseo! Sigue el monitoreo en vivo.",
                'iniciado',
                route('paseos.monitoreo', [], false)
            ));
        }

        return redirect()->route('paseos.control')
            ->with('success', '¡Paseo iniciado con éxito! Tu geolocalización se transmitirá en segundo plano.');
    }

    /**
     * Operación del Paseador: Finalizar el recorrido
     */
    public function finalizarPaseo($id)
    {
        if (auth()->check() && !auth()->user()->perfilPaseador) {
            abort(403, 'Acción exclusiva para paseadores.');
        }

        $paseo = Paseo::findOrFail($id);

        if ($paseo->paseador_id !== auth()->id()) {
            abort(403);
        }

        // Cambiamos el estado a 'finalizado' y colocamos la hora de fin
        $paseo->update([
            'estado' => 'finalizado',
            'hora_fin' => now(),
        ]);

        // Notificar al propietario en pantalla
        $paseo->load(['mascota.propietario', 'paseador']);
        $propietarioUser = $paseo->mascota->propietario;
        if ($propietarioUser) {
            $propietarioUser->notify(new PaseoNotification(
                $paseo,
                "El paseo de {$paseo->mascota->nombre} ha finalizado. Por favor, califica al paseador.",
                'finalizado',
                route('dashboard', [], false)
            ));
        }

        return redirect()->route('paseos.control')
            ->with('success', 'Paseo finalizado correctamente. Gracias por cuidar de nuestra mascota 🐾');
    }

    /**
     * Operación del Paseador: Registrar incidente
     */
    public function registrarNovedad(Request $request, $id)
    {
        if (auth()->check() && !auth()->user()->perfilPaseador) {
            abort(403, 'Acción exclusiva para paseadores.');
        }

        $paseo = Paseo::findOrFail($id);

        if ($paseo->paseador_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'detalle' => 'required|string|max:500',
        ]);

        Novedad::create([
            'paseo_id' => $paseo->id,
            'detalle' => $request->detalle,
        ]);

        // Notificar al propietario en pantalla
        $paseo->load('mascota.propietario');
        $propietarioUser = $paseo->mascota->propietario;
        if ($propietarioUser) {
            $propietarioUser->notify(new PaseoNotification(
                $paseo,
                "Se ha registrado una novedad en el paseo de {$paseo->mascota->nombre}: {$request->detalle}",
                'novedad',
                route('paseos.monitoreo', [], false)
            ));
        }

        return redirect()->route('paseos.control')
            ->with('success', 'Novedad registrada e inyectada al propietario en tiempo real.');
    }

    /**
     * Historial de pagos para el Propietario
     */
    public function historialPagos(Request $request)
    {
         /** @var \App\Models\User $user */
        $user = auth()->user();

        if (auth()->check() && ($user->isAdmin() || auth()->user()->perfilPaseador)) {
            abort(403, 'Acción exclusiva para propietarios de mascotas.');
        }

        $query = Paseo::with(['mascota', 'paseador', 'pago'])
            ->whereHas('mascota', function ($q) {
                $q->where('propietario_id', auth()->id());
            })
            ->whereHas('pago');

        // Aplicamos los scopes locales de filtrado
        $query->buscarMascota($request->get('buscar'))
              ->filtrarEstado($request->get('estado'))
              ->rangoFechas($request->get('fecha_inicio'), $request->get('fecha_fin'));

        $paseos = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        return view('pagos.historial', compact('paseos'));
    }

    /**
     * Exportar historial de pagos / paseos a PDF
     */
    public function exportarPdf(Request $request)
    {
         /** @var \App\Models\User $user */
        $user = auth()->user();

        if (auth()->check() && ($user->isAdmin() || auth()->user()->perfilPaseador)) {
            abort(403, 'Acción exclusiva para propietarios de mascotas.');
        }

        $query = Paseo::with(['mascota', 'paseador', 'pago'])
            ->whereHas('mascota', function ($q) {
                $q->where('propietario_id', auth()->id());
            })
            ->whereHas('pago');

        // Replicamos la misma lógica de filtros
        $query->buscarMascota($request->get('buscar'))
              ->filtrarEstado($request->get('estado'))
              ->rangoFechas($request->get('fecha_inicio'), $request->get('fecha_fin'));

        $paseos = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('pdf.historial-paseos', compact('paseos'))
                  ->setPaper('letter', 'portrait');

        return $pdf->download('reporte-paseos-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Operación del Paseador: Aceptar la solicitud de paseo
     */
    public function aceptarPaseo($id)
    {
        if (auth()->check() && !auth()->user()->perfilPaseador) {
            abort(403, 'Acción exclusiva para paseadores.');
        }

        $paseo = Paseo::findOrFail($id);

        if ($paseo->paseador_id !== auth()->id()) {
            abort(403);
        }

        if ($paseo->estado !== 'pendiente') {
            abort(400, 'El paseo no está en estado pendiente.');
        }

        $paseo->update([
            'estado' => 'esperando_pago',
        ]);

        // Notificar al propietario en pantalla
        $paseo->load(['mascota.propietario', 'paseador']);
        $propietarioUser = $paseo->mascota->propietario;
        if ($propietarioUser) {
            $propietarioUser->notify(new PaseoNotification(
                $paseo,
                "Tu solicitud de paseo para {$paseo->mascota->nombre} ha sido aceptada por {$paseo->paseador->nombres}. Ya puedes realizar el pago.",
                'aceptado',
                route('pagos.simulacion', ['paseo_id' => $paseo->id], false)
            ));
        }

        return redirect()->route('paseos.control')
            ->with('success', 'Has aceptado la solicitud de paseo. Queda en espera de que el cliente realice el pago.');
    }

    /**
     * Operación del Paseador: Rechazar la solicitud de paseo
     */
    public function rechazarPaseo($id)
    {
        if (auth()->check() && !auth()->user()->perfilPaseador) {
            abort(403, 'Acción exclusiva para paseadores.');
        }

        $paseo = Paseo::findOrFail($id);

        if ($paseo->paseador_id !== auth()->id()) {
            abort(403);
        }

        if ($paseo->estado !== 'pendiente') {
            abort(400, 'El paseo no está en estado pendiente.');
        }

        $paseo->update([
            'estado' => 'cancelado',
        ]);

        return redirect()->route('paseos.control')
            ->with('success', 'Has rechazado la solicitud de paseo.');
    }

    /**
     * Operación del Propietario: Calificar un paseo finalizado
     */
    public function calificar(Request $request, $id)
    {
         /** @var \App\Models\User $user */
        $user = auth()->user();

        if (auth()->check() && ($user->isAdmin() || auth()->user()->perfilPaseador)) {
            abort(403, 'Acción exclusiva para propietarios de mascotas.');
        }

        $request->validate([
            'calificacion' => 'required|integer|min:1|max:5',
        ]);

        $paseo = Paseo::with('mascota')->findOrFail($id);

        if ($paseo->mascota->propietario_id !== auth()->id()) {
            abort(403, 'No tienes autorización para calificar este paseo.');
        }

        if ($paseo->estado !== 'finalizado') {
            abort(400, 'Solo puedes calificar paseos finalizados.');
        }

        $paseo->update([
            'calificacion' => $request->calificacion,
        ]);

        // Recalcular la calificación promedio del paseador
        $perfilPaseador = \App\Models\PaseadorPerfil::where('user_id', $paseo->paseador_id)->first();
        if ($perfilPaseador) {
            $promedio = Paseo::where('paseador_id', $paseo->paseador_id)
                ->where('estado', 'finalizado')
                ->whereNotNull('calificacion')
                ->avg('calificacion');

            $perfilPaseador->update([
                'calificacion_promedio' => round($promedio, 2)
            ]);
        }

        return redirect()->route('dashboard')
            ->with('success', '¡Gracias por calificar el paseo! Tu valoración ayuda a la comunidad.');
    }

    /**
     * API POST: Recibe el escaneo del código QR y valida el inicio del paseo.
     */
    public function validarQr(Request $request, $id)
    {
        $request->validate([
            'token_qr' => 'required|string',
        ]);

        $paseo = Paseo::findOrFail($id);

        // Validar que el token escaneado coincida con el generado en la BD
        if ($paseo->token_qr !== $request->token_qr) {
            return response()->json([
                'success' => false,
                'message' => 'Código QR inválido o expirado. Vuelve a intentarlo.'
            ], 400);
        }

        if ($paseo->estado !== 'programado') {
            return response()->json([
                'success' => false,
                'message' => 'Este paseo ya ha sido iniciado o finalizado.'
            ], 400);
        }

        // Cambiar estado a en_progreso y registrar hora de inicio
        $paseo->update([
            'estado' => 'en_progreso',
            'hora_inicio' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => '¡Código QR validado! El paseo ha iniciado correctamente y el rastreo GPS está activo.',
            'paseo' => $paseo
        ], 200);
    }

    public function getStatus($id)
    {
        $paseo = Paseo::findOrFail($id);
        
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        // Seguridad: Solo el dueño de la mascota, paseador o admin
        if ($user->id !== $paseo->paseador_id && $user->id !== $paseo->mascota->propietario_id && !$user->isAdmin()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return response()->json([
            'estado' => $paseo->estado
        ]);
    }

    public function exportarCatalogoQr()
    {
        $pdf = Pdf::loadView('pdf.catalogo-qr')
                  ->setPaper('letter', 'portrait');
                  
        return $pdf->download('catalogo-estilos-qr-walkydog.pdf');
    }
}
