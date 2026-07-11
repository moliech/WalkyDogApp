<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WalkyDog 🐾 - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-brand-bg font-sans antialiased text-brand-dark">

    <nav class="navbar navbar-expand-lg bg-white/85 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 py-3.5">
        <div class="container mx-auto px-4 md:px-6 flex flex-wrap items-center justify-between">
            <a class="text-xl md:text-2xl font-black text-brand-dark tracking-tight flex items-center gap-1.5 no-underline hover:opacity-90 transition" href="{{ route('dashboard') }}">
                <span>WalkyDog</span> <span class="text-brand-primary">🐾</span>
            </a>
            
            <button class="navbar-toggler lg:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 focus:outline-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
            </button>
            
            <div class="collapse navbar-collapse w-full lg:w-auto lg:flex lg:items-center mt-4 lg:mt-0" id="navbarNav">
                <ul class="flex flex-col lg:flex-row items-center lg:ml-auto space-y-3 lg:space-y-0 lg:space-x-1.5 list-none pl-0 mb-0">
                    <li><a class="block text-sm font-bold text-gray-600 hover:text-brand-primary hover:bg-brand-primary/5 px-4 py-2 rounded-xl transition duration-200 no-underline" href="{{ route('dashboard') }}">Dashboard</a></li>
                    
                    @if(auth()->check() && !auth()->user()->perfilPaseador)
                        <li><a class="block text-sm font-bold text-gray-600 hover:text-brand-primary hover:bg-brand-primary/5 px-4 py-2 rounded-xl transition duration-200 no-underline" href="{{ route('mascotas.index') }}">Mis Mascotas</a></li>
                        <li><a class="block text-sm font-bold text-gray-600 hover:text-brand-primary hover:bg-brand-primary/5 px-4 py-2 rounded-xl transition duration-200 no-underline" href="{{ route('paseos.monitoreo') }}">Monitoreo</a></li>
                        <li><a class="block text-sm font-bold text-gray-600 hover:text-brand-primary hover:bg-brand-primary/5 px-4 py-2 rounded-xl transition duration-200 no-underline" href="{{ route('pagos.historial') }}">Historial de Pagos</a></li>
                    @endif

                    @if(auth()->check() && auth()->user()->perfilPaseador)
                        <li><a class="block text-sm font-bold text-gray-600 hover:text-brand-primary hover:bg-brand-primary/5 px-4 py-2 rounded-xl transition duration-200 no-underline" href="{{ route('paseos.control') }}">Paseador</a></li>
                    @endif

                    @if(auth()->check() && (auth()->user()->email === 'esteban.molina@cotecnova.edu.co' || str_contains(auth()->user()->email, 'admin')))
                        <li><a class="block text-sm font-bold text-brand-secondary hover:text-brand-secondary/80 hover:bg-brand-secondary/5 px-4 py-2 rounded-xl transition duration-200 no-underline" href="{{ route('admin.paseadores') }}">🔍 Auditoría</a></li>
                    @endif
                    
                    @if(auth()->check() && !auth()->user()->perfilPaseador)
                        <li class="w-full lg:w-auto mt-2 lg:mt-0">
                            <button class="w-full lg:w-auto bg-brand-primary hover:bg-brand-primary-hover text-white font-bold text-sm px-5 py-2.5 rounded-xl shadow-sm hover:shadow-lg hover:shadow-brand-primary/20 hover:-translate-y-0.5 transition duration-200 cursor-pointer" data-bs-toggle="modal" data-bs-target="#solicitarPaseoModal">
                                🐾 Agendar Paseo
                            </button>
                        </li>
                    @endif
                    <li class="w-full lg:w-auto mt-2 lg:mt-0 lg:border-l lg:border-gray-200 lg:pl-3">
                        <a class="w-full lg:w-auto inline-block text-center border border-gray-200 text-brand-dark hover:border-brand-primary hover:text-brand-primary font-bold text-sm px-5 py-2.5 rounded-xl transition duration-200 no-underline" href="{{ route('perfil.editar') }}">⚙️ Hola, {{ auth()->user()->nombres }}</a>
                    </li>
                                        <li class="w-full lg:w-auto mt-2 lg:mt-0 lg:pl-1">
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="w-full lg:w-auto inline-block text-center border border-brand-accent-red/20 text-brand-accent-red hover:bg-brand-accent-red hover:text-white font-bold text-sm px-5 py-2.5 rounded-xl transition duration-200 cursor-pointer bg-brand-accent-red/5">
                                🚪 Salir
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

    <div class="modal fade" id="solicitarPaseoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-2xl p-2 bg-white">
                <div class="modal-header border-0 pb-0 flex justify-between items-center px-6 pt-5">
                    <h5 class="text-lg font-black text-brand-dark flex items-center gap-2">
                        <span>🦮 Agendar Nuevo Paseo</span>
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
                                <option value="1">1 Hora ($12.000 COP)</option>
                                <option value="2">2 Horas ($24.000 COP)</option>
                                <option value="3">3 Horas ($36.000 COP)</option>
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