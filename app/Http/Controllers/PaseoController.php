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
     * Monitoreo en tiempo real (Mapa de Leaflet.js para el Propietario)
     */
    public function monitoreo(Request $request)
    {
        // 1. Obtenemos todos los paseos activos del propietario logueado
        $paseosActivos = Paseo::with(['mascota', 'paseador', 'ubicaciones', 'novedades'])
            ->whereHas('mascota', function ($query) {
                $query->where('propietario_id', auth()->id());
            })
            ->where('estado', 'en_progreso')
            ->get();

        // 2. Determinamos cuál paseo activo mostrar en detalle
        $paseoActivo = null;
        if ($paseosActivos->isNotEmpty()) {
            $selectedId = $request->query('paseo_id');
            $paseoActivo = $selectedId 
                ? $paseosActivos->firstWhere('id', $selectedId) 
                : $paseosActivos->first();

            // Si el ID seleccionado no pertenece al usuario, volvemos al primero
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
        // Listamos los paseos asignados al paseador logueado en estado 'programado' o 'en_progreso'
        $paseosAsignados = Paseo::with(['mascota.propietario', 'pago'])
            ->where('paseador_id', auth()->id())
            ->whereIn('estado', ['programado', 'en_progreso'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('paseos.control', compact('paseosAsignados'));
    }

    /**
     * Registrar la solicitud de un nuevo paseo en la BD
     */
    public function agendar(Request $request)
    {
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

        // 2. Creamos el paseo en estado 'programado'
        $paseo = Paseo::create([
            'paseador_id' => $request->paseador_id,
            'mascota_id' => $request->mascota_id,
            'estado' => 'programado',
            'token_qr' => 'walkydog_qr_' . Str::random(16),
            'hora_inicio' => null,
            'hora_fin' => null,
            'calificacion' => null,
        ]);

        // 3. Generamos el cobro simulado asociado en estado 'pending' (Horas * 12000 COP)
        $monto = $request->duracion * 12000;
        Pago::create([
            'paseo_id' => $paseo->id,
            'monto' => $monto,
            'estado_pago' => 'pending',
        ]);

        return redirect()->route('pagos.simulacion', $paseo->id)
            ->with('success', 'Orden de paseo registrada como PENDING. Procede al pago para confirmar el agendamiento.');
    }

    /**
     * Mostrar la factura simulada antes del pago
     */
    public function simularPago($paseo_id)
    {
        $paseo = Paseo::with(['mascota', 'pago', 'paseador'])->findOrFail($paseo_id);

        // Validamos que el dueño del paseo sea el usuario logueado
        if ($paseo->mascota->propietario_id !== auth()->id()) {
            abort(403);
        }

        return view('pagos.simulacion', compact('paseo'));
    }

    /**
     * Confirmar el pago simulado
     */
    public function confirmarPago(Request $request, $paseo_id)
    {
        $paseo = Paseo::with(['mascota', 'pago'])->findOrFail($paseo_id);

        if ($paseo->mascota->propietario_id !== auth()->id()) {
            abort(403);
        }

        // Aprobamos el pago
        $paseo->pago->update([
            'estado_pago' => 'approved',
        ]);

        return redirect()->route('dashboard')
            ->with('success', '¡Pago simulado aprobado con éxito! Tu paseo ha sido agendado y está en espera de ejecución 🐾');
    }

    /**
     * Operación del Paseador: Iniciar el recorrido
     */
    public function iniciarPaseo($id)
    {
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
        $paseos = Paseo::with(['mascota', 'paseador', 'pago'])
            ->whereHas('mascota', function ($query) {
                $query->where('propietario_id', auth()->id());
            })
            ->whereHas('pago')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pagos.historial', compact('paseos'));
    }
}
