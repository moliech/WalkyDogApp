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
    <h2 class="text-3xl font-black text-brand-dark tracking-tight">Monitoreo en Tiempo Real</h2>
    <p class="text-gray-400 font-semibold mt-1">Monitorea geográficamente la jornada de tu mascota</p>
</div>

@if($paseoActivo)
    @if(auth()->check() && auth()->user()->isAdmin())
    <div class="mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-100 flex flex-col sm:flex-row items-center gap-4 justify-between">
        <div class="flex items-center gap-2">
            <span class="text-sm font-bold text-brand-dark">Filtrar por Paseador:</span>
            <select id="filter-paseador" class="rounded-xl border border-gray-200 px-3 py-2 text-xs focus:border-brand-primary outline-none bg-white font-extrabold text-brand-dark" onchange="filterWalksByWalker(this.value)">
                <option value="all" @if(empty($selectedPaseadorId) || $selectedPaseadorId === 'all') selected @endif>Todos los Paseadores</option>
                @foreach($todosPaseadores as $walker)
                    <option value="{{ $walker->id }}" @if($selectedPaseadorId == $walker->id) selected @endif>{{ $walker->nombres }} {{ $walker->apellidos }}</option>
                @endforeach
            </select>
        </div>
        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Monitoreando {{ $paseosActivos->count() }} Paseo(s) Activo(s)</span>
    </div>
    @endif

    @if($paseosActivos->count() > 0)
    <div class="flex flex-wrap gap-2 mb-6">
        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider w-full mb-1">Mascotas en Paseo (Selecciona una para monitorear)</span>
        @foreach($paseosActivos as $pa)
            @php
                $queryParams = ['paseo_id' => $pa->id];
                if (!empty($selectedPaseadorId) && $selectedPaseadorId !== 'all') {
                    $queryParams['paseador_id'] = $selectedPaseadorId;
                }
            @endphp
            <a href="{{ route('paseos.monitoreo', $queryParams) }}" 
               class="walk-tab-item px-4 py-2.5 rounded-xl text-sm font-extrabold transition no-underline flex items-center gap-1.5
                      {{ $paseoActivo->id == $pa->id ? 'bg-brand-primary text-white shadow-md shadow-brand-primary/20' : 'bg-white border border-gray-100 text-gray-500 hover:text-brand-primary hover:bg-brand-primary/5' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z"/>
                </svg>
                {{ $pa->mascota->nombre }} ({{ $pa->paseador->nombres }})
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
                    <span class="font-extrabold text-brand-dark flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                        </svg>
                        {{ $paseoActivo->paseador->nombres }} {{ $paseoActivo->paseador->apellidos }}
                    </span>
                </div>
                
                <div class="flex justify-between items-center py-3 border-b border-gray-50 text-sm">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mascota:</span>
                    <span class="font-extrabold text-brand-dark flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                        </svg>
                        {{ $paseoActivo->mascota->nombre }} ({{ $paseoActivo->mascota->raza }})
                    </span>
                </div>

                <div class="flex justify-between items-center py-3 border-b border-gray-50 text-sm">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Inicio:</span>
                    <span class="font-extrabold text-brand-dark flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        {{ $paseoActivo->hora_inicio ? \Carbon\Carbon::parse($paseoActivo->hora_inicio)->format('g:i A') : 'No definido' }}
                    </span>
                </div>
                
                <div class="bg-brand-bg/50 p-4 rounded-xl mt-6">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1 1 15 0Z"/>
                        </svg>
                        Último Reporte GPS
                    </span>
                    @if($paseoActivo->ubicaciones->isNotEmpty())
                        @php $lastUbi = $paseoActivo->ubicaciones->last(); @endphp
                        <div class="flex justify-between text-xs font-mono text-gray-500 mb-1">
                            <span>Latitud:</span> <span id="latitud-val">{{ $lastUbi->latitud }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-mono text-gray-500">
                            <span>Longitud:</span> <span id="longitud-val">{{ $lastUbi->longitud }}</span>
                        </div>
                    @else
                        <div class="flex justify-between text-xs font-mono text-gray-500 mb-1">
                            <span>Latitud:</span> <span id="latitud-val">Esperando...</span>
                        </div>
                        <div class="flex justify-between text-xs font-mono text-gray-500">
                            <span>Longitud:</span> <span id="longitud-val">Esperando...</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Novedades del Paseo -->
            <div class="pt-4 border-t border-gray-100">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-brand-accent-red" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                    </svg>
                    Bitácora de Novedades
                </span>
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
            
            <!-- Acciones del Paseador en Monitoreo -->
            @if(auth()->check() && auth()->user()->perfilPaseador && $paseoActivo->estado == 'en_progreso')
                <div class="pt-4 border-t border-gray-100 flex gap-2">
                    <form method="POST" action="{{ route('paseos.finalizar', $paseoActivo->id) }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full bg-brand-accent-red hover:bg-red-600 text-white font-extrabold text-xs py-3 px-4 rounded-xl shadow-sm transition cursor-pointer flex items-center justify-center gap-1">
                            Finalizar Paseo
                        </button>
                    </form>
                    <button class="flex-1 bg-white hover:bg-gray-50 border border-gray-200 text-brand-dark font-extrabold text-xs py-3 px-4 rounded-xl transition cursor-pointer flex items-center justify-center gap-1" data-bs-toggle="modal" data-bs-target="#novedadModal{{ $paseoActivo->id }}">
                        Reportar Novedad
                    </button>
                </div>
            @endif
        </div>
        
        <!-- Contenedor del Mapa -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <div id="map" class="m-4 rounded-xl border border-gray-100 shadow-inner"></div>
        </div>
    </div>

    <!-- Modal de Novedad para el Paseador (Monitoreo) -->
    @if(auth()->check() && auth()->user()->perfilPaseador && $paseoActivo->estado == 'en_progreso')
        <div class="modal fade" id="novedadModal{{ $paseoActivo->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-2xl p-2 bg-white rounded-3xl">
                    <div class="modal-header border-0 pb-0 flex justify-between items-center px-6 pt-5">
                        <h5 class="text-lg font-black text-brand-dark flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-accent-red" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                            </svg>
                            <span>Reportar Novedad (Paseo #{{ $paseoActivo->id }})</span>
                        </h5>
                        <button type="button" class="btn-close focus:outline-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-3">
                        <form method="POST" action="{{ route('novedades.registrar', $paseoActivo->id) }}" class="px-2 pb-4 space-y-4">
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
@else
    <div class="bg-white p-12 text-center rounded-3xl border border-gray-100 shadow-xl max-w-lg mx-auto mt-12">
        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
        </svg>
        <h4 class="text-xl font-black text-brand-dark mt-4">No tienes paseos activos en curso</h4>
        <p class="text-sm text-gray-400 mt-2 leading-relaxed">
            Cuando tu paseador asignado inicie el recorrido escaneando el código QR de tu mascota, podrás ver su ubicación satelital e incidentes en este panel.
        </p>
        @if(auth()->check() && !auth()->user()->isAdmin() && !auth()->user()->perfilPaseador)
        <button class="mt-6 bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-sm px-6 py-3 rounded-xl shadow-sm hover:shadow-lg cursor-pointer" data-bs-toggle="modal" data-bs-target="#solicitarPaseoModal">
            Agendar un Paseo
        </button>
        @endif
    </div>
@endif
@endsection

@push('scripts')
@if($paseoActivo)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const paseoId = "{{ $paseoActivo->id }}";
        
        // Coordenadas inyectadas inicialmente desde la BD (casteadas a float para evitar strings que rompan Leaflet)
        let pathCoords = JSON.parse('{!! json_encode($paseoActivo->ubicaciones->map(fn($u) => [(float)$u->latitud, (float)$u->longitud])) !!}');
        
        // Centro inicial: última coordenada o el centro de Cartago por defecto si está vacío
        const centerCoord = pathCoords.length > 0 ? pathCoords[pathCoords.length - 1] : [4.7508, -75.9122];
        
        // Inicializar el mapa
        const map = L.map('map').setView(centerCoord, 16);
 
        // Capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Crear Marcador de Mascota
        let petMarker = L.marker(centerCoord).addTo(map)
            .bindPopup("<b>{{ $paseoActivo->mascota->nombre }}</b> está aquí")
            .openPopup();

        // Crear Polilínea del camino recorrido
        let polyline = L.polyline(pathCoords, {
            color: '#FF8C32', // Naranja WalkyDog
            weight: 5,
            opacity: 0.8,
            smoothFactor: 1
        }).addTo(map);

        if (pathCoords.length > 1) {
            map.fitBounds(polyline.getBounds());
        }

        // Función asíncrona para consultar la API de ubicación en tiempo real
        function refreshTrackingData() {
            fetch(`/api/paseos/${paseoId}/ubicaciones`)
                .then(res => res.json())
                .then(data => {
                    if (data.coordenadas && data.coordenadas.length > 0) {
                        // Mapear a formato de array de Leaflet [[lat, lng], ...]
                        const newCoords = data.coordenadas.map(c => [parseFloat(c.latitud), parseFloat(c.longitud)]);
                        
                        // Actualizar la línea en el mapa
                        polyline.setLatLngs(newCoords);

                        // Mover el marcador a la última coordenada recibida
                        const latest = newCoords[newCoords.length - 1];
                        petMarker.setLatLng(latest);

                        // Actualizar los datos numéricos en el panel lateral
                        document.getElementById('latitud-val').innerText = latest[0];
                        document.getElementById('longitud-val').innerText = latest[1];
                    }
                })
                .catch(err => console.error("Error al actualizar la geolocalización:", err));
        }

        // Consultar la API cada 15 segundos de forma automática
        setInterval(refreshTrackingData, 15000);
    });

    // Función de filtrado para la vista del Administrador
    window.filterWalksByWalker = function(walkerId) {
        const url = new URL(window.location.href);
        if (walkerId && walkerId !== 'all') {
            url.searchParams.set('paseador_id', walkerId);
            url.searchParams.delete('paseo_id');
        } else {
            url.searchParams.delete('paseador_id');
            url.searchParams.delete('paseo_id');
        }
        window.location.href = url.toString();
    }
</script>
@endif
@endpush