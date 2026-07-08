@extends('layouts.app')
@section('title', 'Monitoreo en Tiempo Real')

@section('content')
<div class="py-6 mb-6">
    <h2 class="text-3xl font-black text-brand-dark tracking-tight">Monitoreo en Tiempo Real</h2>
    <p class="text-gray-400 font-semibold mt-1">Módulo II - Visualización Geográfica (Mock Data)</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Panel de Detalle -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-fit">
        <div>
            <div class="flex items-center justify-between mb-6">
                <h5 class="text-lg font-black text-brand-dark">Detalle del Paseo</h5>
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-extrabold px-2.5 py-1 rounded-full bg-brand-secondary/15 text-brand-secondary">
                        {{ $paseoActivo['estado'] }}
                    </span>
                </div>
            </div>
            
            <div class="flex justify-between items-center py-3.5 border-b border-gray-50">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Paseador:</span>
                <span class="text-sm font-extrabold text-brand-dark">{{ $paseoActivo['paseador'] }}</span>
            </div>
            
            <div class="flex justify-between items-center py-3.5 border-b border-gray-50">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mascota:</span>
                <span class="text-sm font-extrabold text-brand-dark">🐶 {{ $paseoActivo['mascota'] }}</span>
            </div>
            
            <div class="bg-brand-bg/50 p-4 rounded-xl mt-6">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5 block">📍 Coordenadas GPS</span>
                <div class="flex justify-between text-xs font-mono text-gray-500 mb-1">
                    <span>Latitud:</span> <span>{{ $paseoActivo['latitud'] }}</span>
                </div>
                <div class="flex justify-between text-xs font-mono text-gray-500">
                    <span>Longitud:</span> <span>{{ $paseoActivo['longitud'] }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contenedor del Mapa -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
        <div class="h-96 lg:h-[400px] flex items-center justify-center bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] [background-size:16px_16px] bg-slate-50/70 border-2 border-dashed border-gray-100 m-4 rounded-xl">
            <div class="text-center p-6">
                <div class="text-5xl mb-4 animate-bounce">🗺️</div>
                <h5 class="text-lg font-black text-brand-dark">Espacio del Mapa (Simulado)</h5>
                <p class="text-xs text-gray-400 max-w-xs mx-auto mt-2 font-medium leading-relaxed">
                    En el Módulo III integraremos la librería de mapas **Leaflet.js** con mapas libres de **OpenStreetMap** aquí.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection