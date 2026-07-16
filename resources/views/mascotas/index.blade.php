@extends('layouts.app')
@section('title', 'Mis Mascotas')

@section('content')
<!-- Encabezado de la Sección -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 py-2">
    <div>
        <h3 class="text-2xl font-black text-brand-dark m-0">Mascotas Asociadas</h3>
        <p class="text-sm text-gray-400 font-semibold mt-1">Registra y administra tus compañeros de cuatro patas</p>
    </div>
    <a href="{{ route('mascotas.create') }}" class="inline-flex items-center justify-center bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-sm px-5 py-3 rounded-xl shadow-md hover:shadow-lg hover:shadow-brand-primary/25 hover:-translate-y-0.5 transition duration-200 no-underline cursor-pointer gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Agregar Nueva Mascota
    </a>
</div>

<!-- Barra de Filtros y Búsqueda -->
<div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm mb-8">
    <form method="GET" action="{{ route('mascotas.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Buscar por nombre o raza</label>
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Ej: Toby o Pug..." class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Filtrar por tamaño</label>
            <select name="tamano" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white">
                <option value="">Todos los tamaños</option>
                @foreach($tamanos as $tam)
                    <option value="{{ $tam->nombre }}" {{ request('tamano') == $tam->nombre ? 'selected' : '' }}>{{ $tam->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 bg-brand-secondary hover:bg-brand-secondary/90 text-white font-bold text-sm py-2.5 px-4 rounded-xl transition duration-200 cursor-pointer">
                Filtrar Resultados
            </button>
            @if(request()->filled('buscar') || request()->filled('tamano'))
                <a href="{{ route('mascotas.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold text-sm py-2.5 px-4 rounded-xl transition duration-200 text-center no-underline">
                    Limpiar
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Listado en Rejilla -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @forelse($mascotas as $mascota)
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-1 transition duration-300 flex flex-col h-full">
            <div class="bg-gradient-to-br from-brand-primary/5 to-brand-primary/10 h-32 flex items-center justify-center border-b border-gray-100">
                <!-- Icono SVG de huella o perro limpio en lugar de emojis -->
                <svg class="w-12 h-12 text-brand-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 0 1-6.364 0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z"/>
                </svg>
            </div>
            <div class="p-6 flex-1 flex flex-col justify-between">
                <div>
                    <h5 class="text-lg font-black text-brand-dark mb-1">{{ $mascota->nombre }}</h5>
                    <p class="text-xs text-gray-400 mb-4">{{ $mascota->observaciones ?? 'Sin observaciones' }}</p>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Raza:</span>
                        <span class="text-sm font-extrabold text-brand-dark">{{ $mascota->raza }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tamaño:</span>
                        <span class="text-xs font-extrabold px-3 py-1 rounded-full inline-block @if($mascota->tamano == 'Grande') bg-brand-primary/10 text-brand-primary @elseif($mascota->tamano == 'Pequeño') bg-brand-secondary/15 text-brand-secondary @else bg-amber-400/10 text-amber-500 @endif">
                            {{ $mascota->tamano }}
                        </span>
                    </div>
                </div>

                <!-- Botones de Acción sin emojis -->
                <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                    <a href="{{ route('mascotas.edit', $mascota->id) }}" class="flex-1 text-center bg-gray-50 hover:bg-gray-100 border border-gray-200 text-brand-dark font-bold text-xs py-2.5 rounded-lg no-underline transition">
                        Editar
                    </a>
                    
                    <form action="{{ route('mascotas.destroy', $mascota->id) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Estás seguro de eliminar a esta mascota?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-brand-accent-red/10 hover:bg-brand-accent-red hover:text-white border border-brand-accent-red/25 text-brand-accent-red font-bold text-xs py-2.5 rounded-lg transition cursor-pointer">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-3 bg-white p-12 text-center rounded-2xl border border-gray-100 shadow-sm">
            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
            </svg>
            <h4 class="text-lg font-bold text-brand-dark">No tienes mascotas registradas</h4>
            <p class="text-sm text-gray-400 mt-1">Registra tu primer compañero para agendar servicios de paseo.</p>
        </div>
    @endforelse
</div>
@endsection