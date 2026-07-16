<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WalkyDog - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-brand-bg font-sans antialiased text-brand-dark">

    <nav class="navbar navbar-expand-lg bg-white/85 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 py-3.5">
        <div class="container mx-auto px-4 md:px-6 flex flex-wrap items-center justify-between">
            <a class="text-xl md:text-2xl font-black text-brand-dark tracking-tight flex items-center gap-1.5 no-underline hover:opacity-90 transition" href="{{ route('dashboard') }}">
                <span>WalkyDog</span>
            </a>
            
            <button class="navbar-toggler lg:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 focus:outline-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
            </button>
            
            <div class="collapse navbar-collapse w-full lg:w-auto lg:flex lg:items-center mt-4 lg:mt-0" id="navbarNav">
                <ul class="flex flex-col lg:flex-row items-center lg:ml-auto space-y-3 lg:space-y-0 lg:space-x-1.5 list-none pl-0 mb-0">
                    <li><a class="block text-sm font-bold px-4 py-2 rounded-xl transition duration-200 no-underline {{ request()->routeIs('dashboard') ? 'bg-brand-primary/10 text-brand-primary' : 'text-gray-600 hover:text-brand-primary hover:bg-brand-primary/5' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
                    
                    @if(auth()->check() && !auth()->user()->perfilPaseador && !auth()->user()->isAdmin())
                        <li><a class="block text-sm font-bold px-4 py-2 rounded-xl transition duration-200 no-underline {{ request()->routeIs('mascotas.*') ? 'bg-brand-primary/10 text-brand-primary' : 'text-gray-600 hover:text-brand-primary hover:bg-brand-primary/5' }}" href="{{ route('mascotas.index') }}">Mis Mascotas</a></li>
                        <li><a class="block text-sm font-bold px-4 py-2 rounded-xl transition duration-200 no-underline {{ request()->routeIs('paseos.monitoreo') ? 'bg-brand-primary/10 text-brand-primary' : 'text-gray-600 hover:text-brand-primary hover:bg-brand-primary/5' }}" href="{{ route('paseos.monitoreo') }}">Monitoreo</a></li>
                        <li><a class="block text-sm font-bold px-4 py-2 rounded-xl transition duration-200 no-underline {{ request()->routeIs('pagos.historial') ? 'bg-brand-primary/10 text-brand-primary' : 'text-gray-600 hover:text-brand-primary hover:bg-brand-primary/5' }}" href="{{ route('pagos.historial') }}">Historial de Pagos</a></li>
                    @endif

                    @if(auth()->check() && auth()->user()->perfilPaseador && !auth()->user()->isAdmin())
                        <li><a class="block text-sm font-bold px-4 py-2 rounded-xl transition duration-200 no-underline {{ request()->routeIs('paseos.control') ? 'bg-brand-primary/10 text-brand-primary' : 'text-gray-600 hover:text-brand-primary hover:bg-brand-primary/5' }}" href="{{ route('paseos.control') }}">Paseador</a></li>
                        <li><a class="block text-sm font-bold px-4 py-2 rounded-xl transition duration-200 no-underline {{ request()->routeIs('paseos.monitoreo') ? 'bg-brand-primary/10 text-brand-primary' : 'text-gray-600 hover:text-brand-primary hover:bg-brand-primary/5' }}" href="{{ route('paseos.monitoreo') }}">Monitoreo</a></li>
                    @endif

                    @if(auth()->check() && auth()->user()->isAdmin())
                        <li><a class="block text-sm font-bold px-4 py-2 rounded-xl transition duration-200 no-underline {{ request()->routeIs('paseos.monitoreo') ? 'bg-brand-primary/10 text-brand-primary' : 'text-gray-600 hover:text-brand-primary hover:bg-brand-primary/5' }}" href="{{ route('paseos.monitoreo') }}">Monitoreo Global</a></li>
                        <li>
                            <a class="block text-sm font-bold px-4 py-2 rounded-xl transition duration-200 no-underline {{ request()->routeIs('admin.paseadores') ? 'bg-brand-secondary/15 text-brand-secondary' : 'text-brand-secondary hover:text-brand-secondary/80 hover:bg-brand-secondary/5' }}" href="{{ route('admin.paseadores') }}">
                                <svg class="w-4 h-4 inline-block mr-1 align-text-bottom" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z"/>
                                </svg>
                                Auditoría
                            </a>
                        </li>
                        <li>
                            <a class="block text-sm font-bold px-4 py-2 rounded-xl transition duration-200 no-underline {{ request()->routeIs('admin.tarifas') ? 'bg-brand-secondary/15 text-brand-secondary' : 'text-brand-secondary hover:text-brand-secondary/80 hover:bg-brand-secondary/5' }}" href="{{ route('admin.tarifas') }}">
                                <svg class="w-4 h-4 inline-block mr-1 align-text-bottom" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-1.97-.659-1.171-.879-1.171-2.303 0-3.182 1.172-.879 3.07-.879 4.242 0L15 8.818M12 3v3m0 12v3"/>
                                </svg>
                                Tarifas
                            </a>
                        </li>
                    @endif
                    
                    @if(auth()->check() && !auth()->user()->perfilPaseador && !auth()->user()->isAdmin())
                        <li class="w-full lg:w-auto mt-2 lg:mt-0">
                            <button class="w-full lg:w-auto bg-brand-primary hover:bg-brand-primary-hover text-white font-bold text-sm px-5 py-2.5 rounded-xl shadow-sm hover:shadow-lg hover:shadow-brand-primary/20 hover:-translate-y-0.5 transition duration-200 cursor-pointer flex items-center justify-center gap-1.5" data-bs-toggle="modal" data-bs-target="#solicitarPaseoModal">
                                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                Agendar Paseo
                            </button>
                        </li>
                    @endif
                    <li class="w-full lg:w-auto mt-2 lg:mt-0 lg:border-l lg:border-gray-200 lg:pl-3">
                        <a class="w-full lg:w-auto inline-block text-center border font-bold text-sm px-5 py-2.5 rounded-xl transition duration-200 no-underline flex items-center justify-center gap-1.5 {{ request()->routeIs('perfil.editar') ? 'bg-brand-primary text-white border-brand-primary shadow-md' : 'border-gray-200 text-brand-dark hover:border-brand-primary hover:text-brand-primary' }}" href="{{ route('perfil.editar') }}">
                            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                            </svg>
                            Hola, {{ auth()->user()->nombres }}
                        </a>
                    </li>
                    <li class="w-full lg:w-auto mt-2 lg:mt-0 lg:pl-1">
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="w-full lg:w-auto inline-flex items-center justify-center gap-1.5 border border-brand-accent-red/20 text-brand-accent-red hover:bg-brand-accent-red hover:text-white font-bold text-sm px-5 py-2.5 rounded-xl transition duration-200 cursor-pointer bg-brand-accent-red/5">
                                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
                                </svg>
                                Salir
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 md:px-6 mt-8">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    @auth
        @if(auth()->user()->perfilPaseador)
            @php
                $activePaseoIds = \App\Models\Paseo::where('paseador_id', auth()->id())
                    ->where('estado', 'en_progreso')
                    ->pluck('id')
                    ->toArray();
            @endphp
            @if(!empty($activePaseoIds))
                <!-- Script Global de Seguimiento GPS (Cubre múltiples paseos en cualquier página) -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const activePaseos = @json($activePaseoIds);
                        let globalWatchId = null;
                        let globalLastSentTime = 0;
                        let globalWakeLock = null;

                        async function reqWakeLock() {
                            try {
                                if ('wakeLock' in navigator) {
                                    globalWakeLock = await navigator.wakeLock.request('screen');
                                    console.log('Global Wake Lock activo.');
                                }
                            } catch (err) {
                                console.warn('Global Wake Lock falló:', err.message);
                            }
                        }

                        function startGlobalTracking() {
                            if (!navigator.geolocation) return;
                            reqWakeLock();
                            
                            globalWatchId = navigator.geolocation.watchPosition(
                                (position) => {
                                    const now = Date.now();
                                    if (now - globalLastSentTime < 15000) return;

                                    const lat = position.coords.latitude;
                                    const lng = position.coords.longitude;

                                    console.log("Coordenada GPS global capturada:", lat, lng);

                                    activePaseos.forEach(paseoId => {
                                        fetch(`/api/paseos/${paseoId}/ubicacion`, {
                                            method: "POST",
                                            headers: {
                                                "Content-Type": "application/json",
                                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                                "Accept": "application/json"
                                            },
                                            body: JSON.stringify({ latitud: lat, longitud: lng })
                                        })
                                        .then(res => {
                                            if (res.ok) {
                                                globalLastSentTime = Date.now();
                                                window.dispatchEvent(new CustomEvent('gps-signal-sent', { 
                                                    detail: { paseoId, time: new Date().toLocaleTimeString() } 
                                                }));
                                            } else {
                                                window.dispatchEvent(new CustomEvent('gps-signal-failed', { 
                                                    detail: { paseoId, message: "HTTP " + res.status } 
                                                }));
                                            }
                                        })
                                        .catch(err => {
                                            console.error("Error al enviar coordenada global:", err);
                                            window.dispatchEvent(new CustomEvent('gps-signal-failed', { 
                                                detail: { paseoId, message: "Error de red" } 
                                            }));
                                        });
                                    });
                                },
                                (error) => {
                                    console.error("Error de Geolocalización Global:", error.message);
                                    activePaseos.forEach(paseoId => {
                                        window.dispatchEvent(new CustomEvent('gps-signal-failed', { 
                                            detail: { paseoId, message: "Sin señal GPS" } 
                                        }));
                                    });
                                },
                                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                            );
                        }

                        startGlobalTracking();

                        document.addEventListener('visibilitychange', async () => {
                            if (globalWatchId !== null && document.visibilityState === 'visible') {
                                await reqWakeLock();
                            }
                        });
                    });
                </script>
            @endif
        @endif
    @endauth

    <div class="modal fade" id="solicitarPaseoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-2xl p-2 bg-white">
                <div class="modal-header border-0 pb-0 flex justify-between items-center px-6 pt-5">
                    <h5 class="text-lg font-black text-brand-dark flex items-center gap-2">
                        <span>Agendar Nuevo Paseo</span>
                    </h5>
                    <button type="button" class="btn-close focus:outline-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3">
                    <form method="POST" action="{{ route('paseos.agendar') }}" class="px-2 pb-4 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Selecciona tu mascota</label>
                            <select name="mascota_id" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white">
                                @isset($myPets)
                                    @forelse($myPets as $pet)
                                        <option value="{{ $pet->id }}">{{ $pet->nombre }} ({{ $pet->raza }})</option>
                                    @empty
                                        <option value="">No tienes mascotas registradas</option>
                                    @endforelse
                                @else
                                    <option value="">Inicia sesión para cargar mascotas</option>
                                @endisset
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Paseador de preferencia</label>
                            <select name="paseador_id" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white">
                                @isset($activeWalkers)
                                    @forelse($activeWalkers as $walker)
                                        <option value="{{ $walker->id }}">{{ $walker->nombres }} {{ $walker->apellidos }} (Calificación: {{ $walker->perfilPaseador->calificacion_promedio ?? '5.0' }})</option>
                                    @empty
                                        <option value="">No hay paseadores activos disponibles</option>
                                    @endforelse
                                @else
                                    <option value="">Inicia sesión para cargar paseadores</option>
                                @endisset
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Duración estimada del paseo</label>
                            <select name="duracion" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white">
                                <option value="1">1 Hora</option>
                                <option value="2">2 Horas</option>
                                <option value="3">3 Horas</option>
                            </select>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="w-full bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-sm py-3.5 px-6 rounded-xl shadow-md shadow-brand-primary/10 hover:shadow-lg hover:shadow-brand-primary/20 hover:-translate-y-0.5 transition duration-200 cursor-pointer">
                                Confirmar y proceder al Pago
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>