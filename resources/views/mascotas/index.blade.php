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
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition duration-300 flex flex-col justify-between h-full">
            <div>
                <!-- Encabezado de la Mascota -->
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-primary/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-brand-primary" viewBox="0 0 100 100" fill="currentColor">
                                <path d="M 50 43 C 35 43, 26 56, 28 70 C 30 82, 42 86, 50 86 C 58 86, 70 82, 72 70 C 74 56, 65 43, 50 43 Z"/>
                                <circle cx="24" cy="42" r="9"/>
                                <circle cx="39" cy="24" r="10.5"/>
                                <circle cx="61" cy="24" r="10.5"/>
                                <circle cx="76" cy="42" r="9"/>
                            </svg>
                        </div>
                        <div>
                            <h5 class="text-base font-black text-brand-dark leading-tight">{{ $mascota->nombre }}</h5>
                            <span class="text-xs font-bold text-gray-400 block mt-0.5">{{ $mascota->raza }}</span>
                        </div>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full shrink-0 @if($mascota->tamano == 'Grande') bg-brand-primary/10 text-brand-primary @elseif($mascota->tamano == 'Pequeño') bg-brand-secondary/15 text-brand-secondary @else bg-amber-400/10 text-amber-600 @endif">
                        {{ $mascota->tamano }}
                    </span>
                </div>

                <!-- Observaciones -->
                <div class="mb-4">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Observaciones</span>
                    <p class="text-xs text-gray-600 font-semibold leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100/80 min-h-[42px] flex items-center">
                        {{ $mascota->observaciones ?? 'Sin observaciones registradas' }}
                    </p>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                <a href="{{ route('mascotas.edit', $mascota->id) }}" class="flex-1 text-center bg-gray-50 hover:bg-gray-100 border border-gray-200 text-brand-dark font-bold text-xs py-2.5 rounded-xl no-underline transition">
                    Editar
                </a>
                
                <form action="{{ route('mascotas.destroy', $mascota->id) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Estás seguro de eliminar a esta mascota?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-brand-accent-red/10 hover:bg-brand-accent-red hover:text-white border border-brand-accent-red/25 text-brand-accent-red font-bold text-xs py-2.5 rounded-xl transition cursor-pointer">
                        Eliminar
                    </button>
                </form>
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