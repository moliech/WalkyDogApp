<?php

namespace App\Http\Controllers;

use App\Models\Paseo;
use App\Models\Pago;
use App\Models\Novedad;
use App\Models\Ubicacion;
use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        // 3. Generamos el cobro simulado asociado en estado 'pending' basado en la tarifa del tamaño
        $tamanoObj = \App\Models\MascotaTamano::where('nombre', $mascota->tamano)->first();
        $tarifaPorHora = $tamanoObj ? $tamanoObj->tarifa_por_hora : 12000;
        $monto = $request->duracion * $tarifaPorHora;
        
        Pago::create([
            'paseo_id' => $paseo->id,
            'monto' => $monto,
            'estado_pago' => 'pending',
        ]);

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

        return redirect()->route('paseos.control')
            ->with('success', 'Novedad registrada e inyectada al propietario en tiempo real.');
    }

    /**
     * Historial de pagos para el Propietario
     */
    public function historialPagos()
    {
         /** @var \App\Models\User $user */
        $user = auth()->user();

        if (auth()->check() && ($user->isAdmin() || auth()->user()->perfilPaseador)) {
            abort(403, 'Acción exclusiva para propietarios de mascotas.');
        }

        $paseos = Paseo::with(['mascota', 'paseador', 'pago'])
            ->whereHas('mascota', function ($query) {
                $query->where('propietario_id', auth()->id());
            })
            ->whereHas('pago')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pagos.historial', compact('paseos'));
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
}
