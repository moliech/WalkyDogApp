@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="py-6 mb-6">
    <h2 class="text-3xl font-black text-brand-dark tracking-tight">Panel de Control WalkyDog</h2>
    <p class="text-gray-400 font-semibold mt-1">Monitorea las métricas en tiempo real. Haz clic en cualquier tarjeta para ver el listado detallado.</p>
</div>

@if(session('success'))
    <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 p-4 rounded-2xl font-bold text-sm mb-6 flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if($paseosPorCalificar->isNotEmpty())
    <div class="mb-8 space-y-4">
        @foreach($paseosPorCalificar as $pc)
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-100 p-5 rounded-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-amber-500/10 text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.173-.435.76-.435.932 0l1.722 4.47 4.821.43c.48.043.673.633.319.957l-3.666 3.35 1.05 4.792c.105.479-.427.865-.826.592L12 15.657l-4.008 2.383c-.399.273-.931-.113-.826-.592l1.05-4.792-3.666-3.35c-.354-.324-.16-.914.32-.957l4.82-.43 1.723-4.47Z"/>
                        </svg>
                    </div>
                    <div>
                        <h6 class="text-sm font-black text-brand-dark">¿Cómo estuvo tu paseo con {{ $pc->paseador->nombres }}?</h6>
                        <p class="text-xs text-gray-500 mt-0.5">El paseo de <strong>{{ $pc->mascota->nombre }}</strong> ha finalizado. Por favor, califica al paseador.</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('paseos.calificar', $pc->id) }}" class="flex items-center gap-2 bg-white border border-amber-200 px-3 py-1.5 rounded-xl shadow-inner">
                    @csrf
                    <select name="calificacion" required class="bg-transparent border-0 outline-none text-xs font-bold text-brand-dark focus:ring-0">
                        <option value="5">5 Estrellas - Excelente</option>
                        <option value="4">4 Estrellas - Muy Bueno</option>
                        <option value="3">3 Estrellas - Regular</option>
                        <option value="2">2 Estrellas - Malo</option>
                        <option value="1">1 Estrella - Muy Malo</option>
                    </select>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-[11px] px-3.5 py-1.5 rounded-lg transition cursor-pointer">
                        Calificar
                    </button>
                </form>
            </div>
        @endforeach
    </div>
@endif

@if($paseosPendientesPago->isNotEmpty())
    <div class="mb-8 space-y-4">
        @foreach($paseosPendientesPago as $ppp)
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 p-5 rounded-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-500/10 text-blue-600">
                        <!-- Icono de billetera / pago -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-6.188 3.197L1.125 19.5V4.5h21.75V19.5l-2.188-1.053a2.25 2.25 0 0 0-2.074 0l-2.188 1.053a2.25 2.25 0 0 1-2.074 0l-2.188-1.053a2.25 2.25 0 0 0-2.074 0L8.25 19.5l-2.188-1.053a2.25 2.25 0 0 0-2.074 0l-2.188 1.053a2.25 2.25 0 0 1-2.074 0Z"/>
                        </svg>
                    </div>
                    <div>
                        <h6 class="text-sm font-black text-brand-dark">¡Paseo para {{ $ppp->mascota->nombre }} aceptado!</h6>
                        <p class="text-xs text-gray-500 mt-0.5">El paseador <strong>{{ $ppp->paseador->nombres }}</strong> ha aceptado tu solicitud. Ya es hora de realizar el pago.</p>
                    </div>
                </div>
                <div class="shrink-0">
                    <a href="{{ route('pagos.simulacion', $ppp->id) }}" class="inline-block bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-xs px-5 py-2.5 rounded-xl shadow-md shadow-brand-primary/10 hover:shadow-lg transition duration-200 cursor-pointer no-underline">
                        Pagar Paseo →
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1: Paseos Activos -->
    <div id="card-paseos" class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-sm hover:shadow-lg hover:-translate-y-1 transition duration-300 flex items-center justify-between cursor-pointer active-card" onclick="switchTab('paseos')">
        <div>
            <h6 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider">Paseos Activos</h6>
            <h2 class="text-3xl font-black text-brand-dark mt-2">{{ $metricas['paseos_activos'] }}</h2>
        </div>
        <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-brand-primary/10 text-brand-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
            </svg>
        </div>
    </div>

    <!-- Card 2: Mascotas Totales -->
    <div id="card-mascotas" class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-sm hover:shadow-lg hover:-translate-y-1 transition duration-300 flex items-center justify-between cursor-pointer" onclick="switchTab('mascotas')">
        <div>
            <h6 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider">Mascotas Totales</h6>
            <h2 class="text-3xl font-black text-brand-dark mt-2">{{ $metricas['mascotas_totales'] }}</h2>
        </div>
        <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-brand-secondary/15 text-brand-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
            </svg>
        </div>
    </div>

    <!-- Card 3: Paseadores -->
    <div id="card-paseadores" class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-sm hover:shadow-lg hover:-translate-y-1 transition duration-300 flex items-center justify-between cursor-pointer" onclick="switchTab('paseadores')">
        <div>
            <h6 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider">Paseadores</h6>
            <h2 class="text-3xl font-black text-brand-dark mt-2">{{ $metricas['paseadores_disponibles'] }}</h2>
        </div>
        <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-amber-400/10 text-amber-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
        </div>
    </div>

    <!-- Card 4: Alertas SOS -->
    <div id="card-alertas" class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-sm hover:shadow-lg hover:-translate-y-1 transition duration-300 flex items-center justify-between cursor-pointer" onclick="switchTab('alertas')">
        <div>
            <h6 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider">Alertas SOS</h6>
            <h2 class="text-3xl font-black text-brand-dark mt-2">{{ $metricas['alertas_sos'] }}</h2>
        </div>
        <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-brand-accent-red/10 text-brand-accent-red">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>
        </div>
    </div>
</div>

<!-- Sección de Detalles Dinámica -->
<div class="bg-white rounded-3xl border border-slate-100/80 shadow-xl p-6">
    <!-- Tabla 1: Paseos Activos -->
    <div id="tab-paseos" class="tab-content">
        <h4 class="text-lg font-black text-brand-dark mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-brand-primary">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
            </svg>
            <span>Rastreo de Paseos Activos en Curso</span>
        </h4>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Paseo ID</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Mascota</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Paseador</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Hora Inicio</th>
                        @if(auth()->check() && !auth()->user()->isAdmin() && !auth()->user()->perfilPaseador)
                            <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Acción</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($paseosActivos as $pa)
                                                <tr class="border-b border-slate-100/50 hover:bg-slate-50/50 transition">
                            <td class="p-3 font-mono font-bold text-brand-dark">#{{ $pa->id }}</td>
                            <td class="p-3 font-extrabold text-brand-dark">{{ $pa->mascota->nombre }}</td>
                            <td class="p-3">{{ $pa->paseador->nombres }} {{ $pa->paseador->apellidos }}</td>
                            <td class="p-3">
                                @if($pa->estado == 'programado')
                                    <span class="text-[10px] font-extrabold uppercase tracking-widest bg-brand-primary/10 text-brand-primary px-3 py-1 rounded-full">Programado (Sin iniciar)</span>
                                @else
                                    <span class="text-[10px] font-extrabold uppercase tracking-widest bg-emerald-500/10 text-emerald-600 px-3 py-1 rounded-full">En Curso</span>
                                @endif
                            </td>
                            @if(auth()->check() && !auth()->user()->isAdmin() && !auth()->user()->perfilPaseador)
                                <td class="p-3">
                                    @if($pa->estado == 'programado')
                                        <button onclick="showQrModal('{{ $pa->token_qr }}', '{{ $pa->mascota->nombre }}')" class="bg-brand-primary hover:bg-brand-primary-hover text-white text-xs font-bold px-4 py-2 rounded-xl transition cursor-pointer">
                                            Mostrar QR de Inicio
                                        </button>
                                    @else
                                        <a href="{{ route('paseos.monitoreo') }}" class="text-xs font-bold text-brand-primary hover:underline">Monitorear GPS →</a>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ (auth()->check() && !auth()->user()->isAdmin() && !auth()->user()->perfilPaseador) ? 5 : 4 }}" class="p-6 text-center text-gray-400 italic">No hay paseos activos en este momento.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla 2: Mascotas Totales -->
    <div id="tab-mascotas" class="tab-content hidden">
        <h4 class="text-lg font-black text-brand-dark mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-brand-secondary">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
            </svg>
            <span>Registro de Mascotas Propias</span>
        </h4>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">ID</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Nombre</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Raza</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Tamaño</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Dueño</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mascotas as $m)
                        <tr class="border-b border-slate-100/50 hover:bg-slate-50/50 transition">
                            <td class="p-3 font-mono text-gray-500">#{{ $m->id }}</td>
                            <td class="p-3 font-extrabold text-brand-dark">{{ $m->nombre }}</td>
                            <td class="p-3">{{ $m->raza }}</td>
                            <td class="p-3">
                                <span class="text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600">{{ $m->tamano }}</span>
                            </td>
                            <td class="p-3 font-semibold text-brand-dark">{{ $m->propietario->nombres ?? 'Yo' }} {{ $m->propietario->apellidos ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-400 italic">No hay mascotas registradas en el sistema.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla 3: Paseadores -->
    <div id="tab-paseadores" class="tab-content hidden">
        <h4 class="text-lg font-black text-brand-dark mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-amber-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            <span>Paseadores Activos Disponibles</span>
        </h4>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Nombre</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Identificación</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Experiencia</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Calificación</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Teléfono</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paseadores as $p)
                        <tr class="border-b border-slate-100/50 hover:bg-slate-50/50 transition">
                            <td class="p-3 font-extrabold text-brand-dark">{{ $p->user->nombres }} {{ $p->user->apellidos }}</td>
                            <td class="p-3 font-mono text-gray-500">{{ $p->identificacion }}</td>
                            <td class="p-3 font-bold text-brand-dark">{{ $p->experiencia_meses }} meses</td>
                            <td class="p-3 text-brand-primary font-black">
                                <svg class="w-3.5 h-3.5 inline-block text-amber-500 mr-0.5 align-text-bottom" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                {{ number_format($p->calificacion_promedio, 1) }}
                            </td>
                            <td class="p-3">{{ $p->user->telefono ?? 'Sin teléfono' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-400 italic">No hay paseadores activos disponibles en este momento.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla 4: Alertas SOS -->
    <div id="tab-alertas" class="tab-content hidden">
        <h4 class="text-lg font-black text-brand-dark mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-brand-accent-red">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>
            <span>Historial de Incidentes y Novedades</span>
        </h4>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Paseo ID</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Mascota</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Reporte de Novedad</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Hora Registro</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($novedades as $nov)
                        <tr class="border-b border-slate-100/50 hover:bg-slate-50/50 transition">
                            <td class="p-3 font-mono font-bold text-brand-dark">#{{ $nov->paseo_id }}</td>
                            <td class="p-3 font-extrabold text-brand-dark">{{ $nov->paseo->mascota->nombre }}</td>
                            <td class="p-3 font-semibold text-brand-accent-red">{{ $nov->detalle }}</td>
                            <td class="p-3 text-gray-400">{{ \Carbon\Carbon::parse($nov->registrado_at)->format('d/m/Y g:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-400 italic">No se han registrado novedades en el sistema.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .active-card {
        border-color: #FF8C32 !important;
        box-shadow: 0 10px 20px -3px rgba(255, 140, 50, 0.12) !important;
        transform: translateY(-4px);
    }
</style>

<script>
    function switchTab(tabName) {
        // Ocultar todos los contenidos de pestañas
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        
        // Quitar la clase activa de todas las tarjetas
        document.getElementById('card-paseos').classList.remove('active-card');
        document.getElementById('card-mascotas').classList.remove('active-card');
        document.getElementById('card-paseadores').classList.remove('active-card');
        document.getElementById('card-alertas').classList.remove('active-card');
        
        // Mostrar el contenido seleccionado
        document.getElementById('tab-' + tabName).classList.remove('hidden');
        
        // Agregar clase activa a la tarjeta clickeada
        document.getElementById('card-' + tabName).classList.add('active-card');
    }
</script>
<!-- Modal para visualizar el Código QR (Dueño) -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl p-6 bg-white rounded-3xl text-center">
            <div class="modal-header border-0 pb-0 flex justify-between items-center">
                <h5 class="text-lg font-black text-brand-dark" id="qrModalLabel">Código de Validación</h5>
                <button type="button" class="btn-close focus:outline-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body flex flex-col items-center py-6">
                <p class="text-sm text-gray-400 font-semibold mb-4 leading-relaxed">Presenta este código al paseador para que lo escanee e inicie el recorrido de tu mascota.</p>
                <div class="bg-slate-50 p-4 rounded-3xl border border-slate-100">
                    <img id="qrImage" src="" alt="Código QR de Validación" class="w-48 h-48">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showQrModal(token, mascotaNombre) {
        // Configuramos la URL de la API de Códigos QR para renderizar el token dinámico
        const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${token}`;
        document.getElementById('qrImage').src = qrUrl;
        document.getElementById('qrModalLabel').innerText = `Validación de Paseo - ${mascotaNombre}`;
        
        // Mostrar Modal por Bootstrap
        const myModal = new bootstrap.Modal(document.getElementById('qrModal'));
        myModal.show();
    }
</script>
@endsection