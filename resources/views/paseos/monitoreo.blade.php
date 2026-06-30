@extends('layouts.app')
@section('title', 'Monitoreo en Tiempo Real')

@section('content')
<div class="py-4 px-2 mb-2">
    <h2 class="fw-extrabold text-slate mb-1">Monitoreo en Tiempo Real</h2>
    <p class="text-slate-muted font-semibold">Módulo II - Visualización Geográfica (Mock Data)</p>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card border-0 h-100 p-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="fw-extrabold text-slate m-0">Detalle del Paseo</h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="pulse-indicator"></span>
                        <span class="badge badge-custom badge-wd-success">{{ $paseoActivo['estado'] }}</span>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-light">
                    <span class="text-slate-muted small font-semibold">Paseador:</span>
                    <span class="text-slate font-bold">{{ $paseoActivo['paseador'] }}</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-light">
                    <span class="text-slate-muted small font-semibold">Mascota:</span>
                    <span class="text-slate font-bold">🐶 {{ $paseoActivo['mascota'] }}</span>
                </div>
                
                <div class="bg-light p-3 rounded-4 mt-4">
                    <h6 class="fw-bold text-slate small mb-2">📍 Coordenadas de GPS</h6>
                    <div class="d-flex justify-content-between text-slate-muted font-monospace small">
                        <span>Latitud:</span> <span>{{ $paseoActivo['latitud'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-slate-muted font-monospace small mt-1">
                        <span>Longitud:</span> <span>{{ $paseoActivo['longitud'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 mb-4">
        <div class="card border-0 h-100">
            <div class="card-body p-0 d-flex align-items-center justify-content-center map-placeholder" style="min-height: 380px;">
                <div class="text-center p-4">
                    <div class="display-4 mb-3">🗺️</div>
                    <h5 class="fw-extrabold text-slate">Espacio del Mapa (Simulado)</h5>
                    <p class="text-slate-muted small max-w-sm mx-auto">En el Módulo III integraremos la librería de mapas **Leaflet.js** con mapas libres de **OpenStreetMap** aquí.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection