@extends('layouts.app')
@section('title', 'Monitoreo en Tiempo Real')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    #map {
        height: 400px;
        z-index: 10;
    }
</style>
@endpush

@section('content')
<div class="py-6 mb-6">
    <h2 class="text-3xl font-black text-brand-dark tracking-tight">Monitoreo en Tiempo Real 🐾</h2>
    <p class="text-gray-400 font-semibold mt-1">Monitorea geográficamente la jornada de tu mascota</p>
</div>

@if($paseoActivo)
    @if($paseosActivos->count() > 1)
    <div class="flex flex-wrap gap-2 mb-6">
        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider w-full mb-1">Mascotas en Paseo (Selecciona una para monitorear)</span>
        @foreach($paseosActivos as $pa)
            <a href="{{ route('paseos.monitoreo', ['paseo_id' => $pa->id]) }}" 
               class="px-4 py-2.5 rounded-xl text-sm font-extrabold transition no-underline flex items-center gap-2
                      {{ $paseoActivo->id == $pa->id ? 'bg-brand-primary text-white shadow-md shadow-brand-primary/20' : 'bg-white border border-gray-100 text-gray-500 hover:text-brand-primary hover:bg-brand-primary/5' }}">
                🐶 {{ $pa->mascota->nombre }}
            </a>
        @endforeach
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Panel de Detalle -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-fit space-y-6">
            <div>
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-50">
                    <h5 class="text-lg font-black text-brand-dark m-0">Detalle del Paseo</h5>
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <span class="text-xs font-extrabold px-2.5 py-1 rounded-full bg-brand-secondary/15 text-brand-secondary uppercase tracking-wider">
                            {{ $paseoActivo->estado }}
                        </span>
                    </div>
                </div>
                
                <div class="flex justify-between items-center py-3 border-b border-gray-50 text-sm">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Paseador:</span>
                    <span class="font-extrabold text-brand-dark">🚶 {{ $paseoActivo->paseador->nombres }} {{ $paseoActivo->paseador->apellidos }}</span>
                </div>
                
                <div class="flex justify-between items-center py-3 border-b border-gray-50 text-sm">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mascota:</span>
                    <span class="font-extrabold text-brand-dark">🐶 {{ $paseoActivo->mascota->nombre }} ({{ $paseoActivo->mascota->raza }})</span>
                </div>

                <div class="flex justify-between items-center py-3 border-b border-gray-50 text-sm">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Inicio:</span>
                    <span class="font-extrabold text-brand-dark">🕒 {{ $paseoActivo->hora_inicio ? \Carbon\Carbon::parse($paseoActivo->hora_inicio)->format('g:i A') : 'No definido' }}</span>
                </div>
                
                <div class="bg-brand-bg/50 p-4 rounded-xl mt-6">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5 block">📍 Último Reporte GPS</span>
                    @if($paseoActivo->ubicaciones->isNotEmpty())
                        @php $lastUbi = $paseoActivo->ubicaciones->last(); @endphp
                        <div class="flex justify-between text-xs font-mono text-gray-500 mb-1">
                            <span>Latitud:</span> <span>{{ $lastUbi->latitud }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-mono text-gray-500">
                            <span>Longitud:</span> <span>{{ $lastUbi->longitud }}</span>
                        </div>
                    @else
                        <span class="text-xs text-gray-400 italic">Esperando señal GPS...</span>
                    @endif
                </div>
            </div>

            <!-- Novedades del Paseo -->
            <div class="pt-4 border-t border-gray-100">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5 block">🚨 Bitácora de Novedades</span>
                <div class="space-y-3 max-h-40 overflow-y-auto pr-1">
                    @forelse($paseoActivo->novedades as $nov)
                        <div class="bg-amber-400/5 p-3 rounded-xl border border-amber-400/10 text-xs">
                            <p class="font-bold text-brand-dark mb-1 leading-relaxed">{{ $nov->detalle }}</p>
                            <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($nov->registrado_at)->format('g:i A') }}</span>
                        </div>
                    @empty
                        <span class="text-xs text-gray-400 italic">No se han registrado incidentes.</span>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Contenedor del Mapa -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <div id="map" class="m-4 rounded-xl border border-gray-100 shadow-inner"></div>
        </div>
    </div>
@else
    <div class="bg-white p-12 text-center rounded-3xl border border-gray-100 shadow-xl max-w-lg mx-auto mt-12">
        <span class="text-5xl">📭</span>
        <h4 class="text-xl font-black text-brand-dark mt-4">No tienes paseos activos en curso</h4>
        <p class="text-sm text-gray-400 mt-2 leading-relaxed">
            Cuando tu paseador asignado inicie el recorrido escaneando el código QR de tu mascota, podrás ver su ubicación satelital e incidentes en este panel.
        </p>
        <button class="mt-6 bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-sm px-6 py-3 rounded-xl shadow-sm hover:shadow-lg cursor-pointer" data-bs-toggle="modal" data-bs-target="#solicitarPaseoModal">
            🐾 Agendar un Paseo
        </button>
    </div>
@endif
@endsection

@push('scripts')
@if($paseoActivo && $paseoActivo->ubicaciones->isNotEmpty())
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Coordenadas inyectadas desde la BD por Eloquent
        const pathCoords = @json($paseoActivo->ubicaciones->map(fn($u) => [$u->latitud, $u->longitud]));
        
        // Si hay coordenadas, tomamos la última como el centro del mapa
        const lastCoord = pathCoords[pathCoords.length - 1];
        
        // Inicializamos Leaflet en las últimas coordenadas
        const map = L.map('map').setView(lastCoord, 16);

        // Cargamos capa de mapa libre de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Colocamos marcador dinámico de mascota
        const petMarker = L.marker(lastCoord).addTo(map)
            .bindPopup("<b>{{ $paseoActivo->mascota->nombre }}</b> está aquí 🐾")
            .openPopup();

        // Dibujamos la polilínea del recorrido (trayectoria)
        if (pathCoords.length > 1) {
            const polyline = L.polyline(pathCoords, {
                color: '#FF8C32', // Color de marca WalkyDog
                weight: 5,
                opacity: 0.7,
                smoothFactor: 1
            }).addTo(map);
            
            // Ajustamos el mapa para que se vean todos los puntos de la trayectoria
            map.fitBounds(polyline.getBounds());
        }
    });
</script>
@endif
@endpush