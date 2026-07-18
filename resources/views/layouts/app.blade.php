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

                    <!-- Campana de Notificaciones -->
                    <li class="relative w-full lg:w-auto mt-2 lg:mt-0 flex items-center justify-center lg:px-2">
                        <button id="notif-btn" class="relative p-2 rounded-xl transition focus:outline-none flex items-center justify-center cursor-pointer {{ (isset($unreadNotifications) && $unreadNotifications->count() > 0) ? 'text-red-500 hover:bg-red-50' : 'text-gray-400 hover:text-brand-primary hover:bg-brand-primary/5' }}" onclick="toggleNotifDropdown()">
                            <!-- Icono Campana -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
                            </svg>
                            
                            @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                                <span id="notif-badge" class="absolute rounded-full shadow-sm" style="top: 4px; right: 4px; width: 10px; height: 10px; background-color: #ef4444; border: 2px solid #ffffff;"></span>
                            @endif
                        </button>

                        <!-- Menú Desplegable (Dropdown) -->
                        <div id="notif-dropdown" class="absolute right-0 top-full mt-6 bg-white rounded-2xl border border-gray-100 shadow-xl py-2 z-50 hidden text-left" style="width: 320px; min-width: 320px;">
                            <div id="notif-header" class="px-4 py-2 border-b border-gray-50 flex items-center justify-between">
                                <span class="text-xs font-black text-brand-dark uppercase tracking-wider">Notificaciones</span>
                                @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                                    <form method="POST" action="{{ route('notificaciones.marcar-leidas') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[10px] text-brand-primary hover:underline font-bold bg-transparent border-0 cursor-pointer">Marcar todas como leídas</button>
                                    </form>
                                @endif
                            </div>
                            <div id="notif-list-container" class="max-h-64 overflow-y-auto">
                                @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                                    @foreach($unreadNotifications as $n)
                                        <a href="{{ route('notificaciones.ir', $n->id) }}" class="flex items-start gap-2.5 px-4 py-3 hover:bg-slate-50 transition border-b border-gray-50/50 no-underline">
                                            <!-- Icono según tipo de notificación -->
                                            <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 @if($n->data['tipo'] == 'novedad') bg-red-100 text-red-600 @else bg-brand-primary/10 text-brand-primary @endif">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    @if($n->data['tipo'] == 'solicitado')
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                                    @elseif($n->data['tipo'] == 'aceptado')
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                    @elseif($n->data['tipo'] == 'pagado')
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3"/>
                                                    @elseif($n->data['tipo'] == 'novedad')
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
                                                    @endif
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs text-gray-600 font-semibold leading-normal">{{ $n->data['mensaje'] }}</p>
                                                <span class="text-[9px] text-gray-400 block mt-1 font-bold text-left">{{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="px-4 py-6 text-center text-gray-400 italic text-xs">
                                        No tienes notificaciones pendientes.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </li>

                    <li class="w-full lg:w-auto mt-2 lg:mt-0 lg:border-l lg:border-gray-200 lg:pl-3">
                        <a class="w-full lg:w-auto inline-block text-center border font-bold text-sm px-5 py-2.5 rounded-xl transition duration-200 no-underline flex items-center justify-center gap-1.5 {{ request()->routeIs('perfil.editar') ? 'bg-brand-primary text-white border-brand-primary shadow-md' : 'border-gray-200 text-brand-dark hover:border-brand-primary hover:text-brand-primary' }}" href="{{ route('perfil.editar') }}">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-5 h-5 rounded-full object-cover inline-block shrink-0 border border-current shadow-sm" alt="Avatar">
                            @else
                                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                </svg>
                            @endif
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
                        const activePaseos = JSON.parse('@json($activePaseoIds)');
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
    <script>
        function toggleNotifDropdown() {
            const dropdown = document.getElementById('notif-dropdown');
            dropdown.classList.toggle('hidden');
        }

        document.addEventListener('click', function(event) {
            const button = document.getElementById('notif-btn');
            const dropdown = document.getElementById('notif-dropdown');
            if (button && dropdown && !button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>