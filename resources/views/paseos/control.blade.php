@extends('layouts.app')
@push('styles')
<!-- Librería para lectura de códigos QR por cámara -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
@endpush
@section('title', 'Panel del Paseador')

@section('content')
<div class="max-w-2xl mx-auto py-4">
    <div class="mb-6 text-center">
        <!-- Icono SVG de Smartphone en el título -->
        <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-brand-primary/10 text-brand-primary mx-auto mb-2">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/>
            </svg>
        </div>
        <h4 class="text-2xl font-black text-brand-dark">Panel del Paseador</h4>
        <p class="text-sm text-gray-400 font-semibold mt-1">Gestiona tus paseos asignados y reporta novedades en tiempo real</p>
    </div>

    <!-- Selector de Paseos Asignados si hay varios -->
    @if($paseosAsignados->count() > 1)
        <div class="flex flex-wrap gap-2 mb-6 justify-center">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider w-full text-center mb-1">Paseos en Agenda (Selecciona uno para gestionar)</span>
            @foreach($paseosAsignados as $pa)
                <button onclick="switchWalkerTab('{{ $pa->id }}')" 
                        id="btn-walker-tab-{{ $pa->id }}"
                        class="btn-walker-tab px-4 py-2.5 rounded-xl text-sm font-extrabold transition duration-200 flex items-center gap-1.5 cursor-pointer
                               {{ $loop->first ? 'bg-brand-primary text-white shadow-md shadow-brand-primary/20' : 'bg-white border border-gray-100 text-gray-500 hover:text-brand-primary hover:bg-brand-primary/5' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z"/>
                    </svg>
                    Orden #{{ $pa->id }} ({{ $pa->mascota->nombre }})
                </button>
            @endforeach
        </div>
    @endif

    <div class="space-y-6">
        @forelse($paseosAsignados as $paseo)
            <div id="walker-card-{{ $paseo->id }}" class="walker-card-content {{ $loop->first ? '' : 'hidden' }}">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100 mb-4">
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Servicio Asignado</span>
                            <h5 class="text-lg font-black text-brand-dark mb-0">Orden #{{ $paseo->id }}</h5>
                        </div>
                        <div>
                            @if($paseo->estado == 'pendiente')
                                <span class="text-[10px] font-extrabold uppercase tracking-widest bg-amber-500/10 text-amber-600 px-3 py-1 rounded-full inline-block animate-pulse">Pendiente Aceptación</span>
                            @elseif($paseo->estado == 'esperando_pago')
                                <span class="text-[10px] font-extrabold uppercase tracking-widest bg-indigo-500/10 text-indigo-600 px-3 py-1 rounded-full inline-block">Esperando Pago</span>
                            @elseif($paseo->estado == 'programado')
                                <span class="text-[10px] font-extrabold uppercase tracking-widest bg-brand-primary/10 text-brand-primary px-3 py-1 rounded-full inline-block">Programado</span>
                            @else
                                <span class="text-[10px] font-extrabold uppercase tracking-widest bg-emerald-500/10 text-emerald-600 px-3 py-1 rounded-full inline-block animate-pulse">En Curso</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 text-sm">
                        <div class="flex justify-between sm:justify-start sm:gap-6 items-center">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mascota:</span>
                            <span class="font-extrabold text-brand-dark flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-brand-primary inline-block align-text-bottom" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                </svg>
                                {{ $paseo->mascota->nombre }} ({{ $paseo->mascota->raza }})
                            </span>
                        </div>
                        <div class="flex justify-between sm:justify-start sm:gap-6 items-center">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Propietario:</span>
                            <span class="font-extrabold text-brand-dark">{{ $paseo->mascota->propietario->nombres }} {{ $paseo->mascota->propietario->apellidos }}</span>
                        </div>
                        <div class="flex justify-between sm:justify-start sm:gap-6 items-center">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Contacto:</span>
                            <span class="font-extrabold text-brand-dark flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-brand-primary inline-block align-text-bottom" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.824-1.28-5.116-3.573-6.396-6.396l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                                </svg>
                                {{ $paseo->mascota->propietario->telefono ?? 'Sin teléfono' }}
                            </span>
                        </div>
                        <div class="flex justify-between sm:justify-start sm:gap-6 items-center">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Dirección:</span>
                            <span class="font-extrabold text-brand-dark flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-brand-primary inline-block align-text-bottom" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1 1 15 0Z"/>
                                </svg>
                                {{ $paseo->mascota->propietario->direccion }}
                            </span>
                        </div>
                    </div>

                    @if($paseo->estado == 'en_progreso')
                        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 mb-5 flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <span class="relative flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                </span>
                                <span class="font-extrabold text-emerald-800">Transmisión GPS Activa</span>
                            </div>
                            <span id="gps-status-{{ $paseo->id }}" class="text-xs text-emerald-600 font-mono">Buscando señal...</span>
                        </div>
                    @endif

                    <!-- Botones de Acción -->
                    <div class="flex flex-col sm:flex-row gap-3 w-full">
                        @if($paseo->estado == 'pendiente')
                            <form method="POST" action="{{ route('paseos.aceptar', $paseo->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-sm py-3.5 px-6 rounded-2xl shadow-md hover:shadow-lg transition duration-200 cursor-pointer flex items-center justify-center gap-1.5">
                                    Aceptar Solicitud
                                </button>
                            </form>
                            <form method="POST" action="{{ route('paseos.rechazar', $paseo->id) }}" class="flex-1" onsubmit="return confirm('¿Estás seguro de rechazar esta solicitud de paseo?')">
                                @csrf
                                <button type="submit" class="w-full bg-brand-accent-red/10 hover:bg-brand-accent-red hover:text-white border border-brand-accent-red/25 text-brand-accent-red font-extrabold text-sm py-3.5 px-6 rounded-2xl transition duration-200 cursor-pointer flex items-center justify-center gap-1.5">
                                    Rechazar Solicitud
                                </button>
                            </form>
                        @elseif($paseo->estado == 'esperando_pago')
                            <div class="flex-1 p-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-center text-xs font-bold text-gray-400">
                                Aceptado. Esperando que el propietario realice el pago.
                            </div>
                        @elseif($paseo->estado == 'programado')
                            <button type="button" onclick="openQrScanner('{{ $paseo->id }}')" class="w-full bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-sm py-3.5 px-6 rounded-2xl shadow-md hover:shadow-lg transition duration-200 cursor-pointer flex items-center justify-center gap-1.5">
                                <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/>
                                </svg>
                                Escanear QR e Iniciar Paseo
                            </button>
                        @else
                            <form method="POST" action="{{ route('paseos.finalizar', $paseo->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-brand-accent-red hover:bg-red-600 text-white font-extrabold text-sm py-3.5 px-6 rounded-2xl shadow-md hover:shadow-lg transition duration-200 cursor-pointer flex items-center justify-center gap-1.5">
                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 7.5A2.25 2.25 0 0 1 7.5 5.25h9a2.25 2.25 0 0 1 2.25 2.25v9a2.25 2.25 0 0 1-2.25 2.25h-9a2.25 2.25 0 0 1-2.25-2.25v-9Z"/>
                                    </svg>
                                    Finalizar Recorrido
                                </button>
                            </form>
                            <button class="flex-1 bg-white hover:bg-gray-50 border border-gray-200 text-brand-dark font-extrabold text-sm py-3.5 px-6 rounded-2xl transition duration-200 cursor-pointer flex items-center justify-center gap-1.5" data-bs-toggle="modal" data-bs-target="#novedadModal{{ $paseo->id }}">
                                <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                                </svg>
                                Reportar Novedad
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Modal de Novedad para este Paseo -->
                @if($paseo->estado == 'en_progreso')
                    <div class="modal fade" id="novedadModal{{ $paseo->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-2xl p-2 bg-white">
                                <div class="modal-header border-0 pb-0 flex justify-between items-center px-6 pt-5">
                                    <h5 class="text-lg font-black text-brand-dark flex items-center gap-2">
                                        <svg class="w-5 h-5 text-brand-accent-red" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                                        </svg>
                                        <span>Reportar Novedad (Paseo #{{ $paseo->id }})</span>
                                    </h5>
                                    <button type="button" class="btn-close focus:outline-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body pt-3">
                                    <form method="POST" action="{{ route('novedades.registrar', $paseo->id) }}" class="px-2 pb-4 space-y-4">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Detalle del incidente o novedad</label>
                                            <textarea name="detalle" rows="4" placeholder="Ej: Se detuvo a tomar agua, el perro se encuentra cansado, etc..." required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white"></textarea>
                                        </div>
                                        <div class="pt-2">
                                            <button type="submit" class="w-full bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-sm py-3.5 px-6 rounded-xl shadow-md transition duration-200 cursor-pointer">
                                                Enviar Reporte
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white p-12 text-center rounded-3xl border border-slate-100 shadow-xl">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                </svg>
                <h5 class="text-lg font-bold text-brand-dark">No tienes paseos programados en este momento</h5>
                <p class="text-sm text-gray-400 mt-1">Los paseos asignados por los propietarios aparecerán aquí cuando estén aprobados.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal para el Escáner de la Cámara (Paseador) -->
<div class="modal fade" id="scannerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl p-4 bg-white rounded-3xl text-center">
            <div class="modal-header border-0 pb-0 flex justify-between items-center">
                <h5 class="text-lg font-black text-brand-dark">Escanear QR de Mascota</h5>
                <button type="button" class="btn-close focus:outline-none" data-bs-dismiss="modal" aria-label="Close" onclick="stopScanner()"></button>
            </div>
            <div class="modal-body py-4">
                <p class="text-xs text-gray-400 font-semibold mb-4 leading-relaxed">Alinea el código QR del dueño de la mascota dentro del recuadro para iniciar el recorrido.</p>
                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-slate-50 relative">
                    <div id="reader" class="w-full"></div>
                </div>
                <div id="scanner-error" class="hidden text-xs text-red-500 font-bold mt-2"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function switchWalkerTab(paseoId) {
        // Ocultar todos los bloques de tarjetas
        document.querySelectorAll('.walker-card-content').forEach(el => el.classList.add('hidden'));
        
        // Quitar clases activas de todos los botones del selector
        document.querySelectorAll('.btn-walker-tab').forEach(btn => {
            btn.classList.remove('bg-brand-primary', 'text-white', 'shadow-md', 'shadow-brand-primary/20');
            btn.classList.add('bg-white', 'border', 'border-gray-100', 'text-gray-500', 'hover:text-brand-primary', 'hover:bg-brand-primary/5');
        });
        
        // Mostrar la tarjeta del paseo seleccionado
        document.getElementById('walker-card-' + paseoId).classList.remove('hidden');
        
        // Agregar clase activa al botón clickeado
        const activeBtn = document.getElementById('btn-walker-tab-' + paseoId);
        if (activeBtn) {
            activeBtn.classList.remove('bg-white', 'border', 'border-gray-100', 'text-gray-500', 'hover:text-brand-primary', 'hover:bg-brand-primary/5');
            activeBtn.classList.add('bg-brand-primary', 'text-white', 'shadow-md', 'shadow-brand-primary/20');
        }
    }

    let html5QrcodeScanner = null;
    let activePaseoId = null;

    function openQrScanner(paseoId) {
        activePaseoId = paseoId;
        const myModal = new bootstrap.Modal(document.getElementById('scannerModal'));
        myModal.show();

        // Limpiar errores previos
        document.getElementById('scanner-error').classList.add('hidden');

        // Inicializamos la cámara de html5-qrcode
        html5QrcodeScanner = new Html5Qrcode("reader");
        
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            console.log(`Token detectado: ${decodedText}`);
            
            // Detener el escáner al tener lectura exitosa
            stopScanner();
            myModal.hide();

            // Enviar token al backend vía API
            fetch(`/api/paseos/${activePaseoId}/validar-qr`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    token_qr: decodedText
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    
                    // Recargar la pantalla para activar el temporizador del paseo y encender el GPS
                    window.location.reload();
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(err => {
                console.error("Error al validar QR:", err);
                alert("Ocurrió un error en la conexión con el servidor.");
            });
        };

        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        // Arrancar cámara frontal/trasera (preferiblemente trasera)
        html5QrcodeScanner.start(
            { facingMode: "environment" },
            config,
            qrCodeSuccessCallback
        ).catch(err => {
            console.error("Error de inicialización de cámara:", err);
            document.getElementById('scanner-error').innerText = "No se pudo acceder a la cámara. Asegúrate de otorgar permisos.";
            document.getElementById('scanner-error').classList.remove('hidden');
        });
    }

    function stopScanner() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop().then(() => {
                console.log("Cámara apagada.");
            }).catch(err => {
                console.warn("Cámara no pudo apagarse correctamente:", err);
            });
        }
    }


    // Escuchar eventos globales de geolocalización para actualizar el estatus en la pantalla del paseador
    window.addEventListener('gps-signal-sent', function(e) {
        const statusEl = document.getElementById("gps-status-" + e.detail.paseoId);
        if (statusEl) {
            statusEl.innerText = "Última señal: " + e.detail.time;
            statusEl.classList.remove('text-red-600', 'font-extrabold');
            statusEl.classList.add('text-emerald-600');
        }
    });

    window.addEventListener('gps-signal-failed', function(e) {
        const statusEl = document.getElementById("gps-status-" + e.detail.paseoId);
        if (statusEl) {
            statusEl.innerText = "Fallo: " + e.detail.message;
            statusEl.classList.remove('text-emerald-600');
            statusEl.classList.add('text-red-600', 'font-extrabold');
        }
    });
</script>

@endsection