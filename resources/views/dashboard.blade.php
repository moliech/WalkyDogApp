@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="py-6 mb-6">
    <h2 class="text-3xl font-black text-brand-dark tracking-tight">Panel de Control WalkyDog 🐾</h2>
    <p class="text-gray-400 font-semibold mt-1">Monitorea las métricas en tiempo real. Haz clic en cualquier tarjeta para ver el listado detallado.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1: Paseos Activos -->
    <div id="card-paseos" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition duration-300 flex items-center justify-between border-t-4 border-t-brand-primary cursor-pointer active-card" onclick="switchTab('paseos')">
        <div>
            <h6 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider">Paseos Activos</h6>
            <h2 class="text-3xl font-black text-brand-dark mt-2">{{ $metricas['paseos_activos'] }}</h2>
        </div>
        <div class="w-12 h-12 flex items-center justify-center rounded-2xl text-xl bg-brand-primary/10 text-brand-primary">
            🦮
        </div>
    </div>

    <!-- Card 2: Mascotas Totales -->
    <div id="card-mascotas" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition duration-300 flex items-center justify-between border-t-4 border-t-brand-secondary cursor-pointer" onclick="switchTab('mascotas')">
        <div>
            <h6 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider">Mascotas Totales</h6>
            <h2 class="text-3xl font-black text-brand-dark mt-2">{{ $metricas['mascotas_totales'] }}</h2>
        </div>
        <div class="w-12 h-12 flex items-center justify-center rounded-2xl text-xl bg-brand-secondary/15 text-brand-secondary">
            🐶
        </div>
    </div>

    <!-- Card 3: Paseadores -->
    <div id="card-paseadores" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition duration-300 flex items-center justify-between border-t-4 border-t-amber-400 cursor-pointer" onclick="switchTab('paseadores')">
        <div>
            <h6 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider">Paseadores</h6>
            <h2 class="text-3xl font-black text-brand-dark mt-2">{{ $metricas['paseadores_disponibles'] }}</h2>
        </div>
        <div class="w-12 h-12 flex items-center justify-center rounded-2xl text-xl bg-amber-400/10 text-amber-500">
            🚶
        </div>
    </div>

    <!-- Card 4: Alertas SOS -->
    <div id="card-alertas" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition duration-300 flex items-center justify-between border-t-4 border-t-brand-accent-red cursor-pointer" onclick="switchTab('alertas')">
        <div>
            <h6 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider">Alertas SOS</h6>
            <h2 class="text-3xl font-black text-brand-dark mt-2">{{ $metricas['alertas_sos'] }}</h2>
        </div>
        <div class="w-12 h-12 flex items-center justify-center rounded-2xl text-xl bg-brand-accent-red/10 text-brand-accent-red">
            🚨
        </div>
    </div>
</div>

<!-- Sección de Detalles Dinámica -->
<div class="bg-white rounded-3xl border border-gray-100 shadow-xl p-6">
    <!-- Tabla 1: Paseos Activos -->
    <div id="tab-paseos" class="tab-content">
        <h4 class="text-lg font-black text-brand-dark mb-4">🦮 Paseos Activos en Curso</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100">
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Paseo ID</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Mascota</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Paseador</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Hora Inicio</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paseosActivos as $pa)
                        <tr class="border-b border-gray-50 hover:bg-slate-50/50">
                            <td class="p-3 font-mono font-bold text-brand-dark">#{{ $pa->id }}</td>
                            <td class="p-3 font-extrabold text-brand-dark">🐶 {{ $pa->mascota->nombre }}</td>
                            <td class="p-3">🚶 {{ $pa->paseador->nombres }} {{ $pa->paseador->apellidos }}</td>
                            <td class="p-3 font-semibold text-gray-500">{{ \Carbon\Carbon::parse($pa->hora_inicio)->format('g:i A') }}</td>
                            <td class="p-3">
                                <a href="{{ route('paseos.monitoreo') }}" class="text-xs font-bold text-brand-primary hover:underline">Monitorear GPS →</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-400 italic">No hay paseos activos en este momento.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla 2: Mascotas Totales -->
    <div id="tab-mascotas" class="tab-content hidden">
        <h4 class="text-lg font-black text-brand-dark mb-4">🐶 Registro General de Mascotas</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100">
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">ID</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Nombre</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Raza</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Tamaño</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Dueño</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mascotas as $m)
                        <tr class="border-b border-gray-50 hover:bg-slate-50/50">
                            <td class="p-3 font-mono text-gray-500">#{{ $m->id }}</td>
                            <td class="p-3 font-extrabold text-brand-dark">{{ $m->nombre }}</td>
                            <td class="p-3">{{ $m->raza }}</td>
                            <td class="p-3">
                                <span class="text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600">{{ $m->tamano }}</span>
                            </td>
                            <td class="p-3 font-semibold text-brand-dark">{{ $m->propietario->nombres }} {{ $m->propietario->apellidos }}</td>
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
        <h4 class="text-lg font-black text-brand-dark mb-4">🚶 Paseadores Activos en el Sistema</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100">
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Nombre</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Identificación</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Experiencia</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Calificación</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Teléfono</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paseadores as $p)
                        <tr class="border-b border-gray-50 hover:bg-slate-50/50">
                            <td class="p-3 font-extrabold text-brand-dark">{{ $p->user->nombres }} {{ $p->user->apellidos }}</td>
                            <td class="p-3 font-mono text-gray-500">{{ $p->identificacion }}</td>
                            <td class="p-3 font-bold text-brand-dark">{{ $p->experiencia_meses }} meses</td>
                            <td class="p-3 text-brand-primary font-black">⭐ {{ number_format($p->calificacion_promedio, 1) }}</td>
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
        <h4 class="text-lg font-black text-brand-dark mb-4">🚨 Historial de Alertas / Novedades</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100">
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Paseo ID</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Mascota</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Reporte de Novedad</th>
                        <th class="p-3 font-bold text-gray-400 uppercase tracking-wider text-xs">Hora Registro</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($novedades as $nov)
                        <tr class="border-b border-gray-50 hover:bg-slate-50/50">
                            <td class="p-3 font-mono font-bold text-brand-dark">#{{ $nov->paseo_id }}</td>
                            <td class="p-3 font-extrabold text-brand-dark">🐶 {{ $nov->paseo->mascota->nombre }}</td>
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
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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
@endsection