@extends('layouts.app')
@section('title', 'Gestión de Tarifas')

@section('content')
<div class="py-6 mb-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-3xl font-black text-brand-dark tracking-tight">Configuración de Tarifas</h2>
        <p class="text-gray-400 font-semibold mt-1">Administra el costo por hora según el tamaño de las mascotas</p>
    </div>
    <span class="text-xs font-extrabold px-3 py-1.5 rounded-full bg-brand-primary/10 text-brand-primary uppercase tracking-wider">
        Rol: Administrador
    </span>
</div>

<div class="max-w-2xl mx-auto mt-4">
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 p-4 rounded-xl font-bold text-sm mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-xl shadow-gray-100/40">
        <form method="POST" action="{{ route('admin.tarifas.actualizar') }}" class="space-y-6">
            @csrf

            <div class="space-y-4">
                @foreach($tarifas as $index => $tarifa)
                    <input type="hidden" name="tarifas[{{ $index }}][id]" value="{{ $tarifa->id }}">
                    
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <div>
                            <span class="text-sm font-black text-brand-dark block">{{ $tarifa->nombre }}</span>
                            <span class="text-xs text-gray-400 font-semibold">Costo base de paseos por hora</span>
                        </div>
                        <div class="w-44 flex items-center bg-white border border-gray-200 rounded-xl px-3 py-2 focus-within:border-brand-primary focus-within:ring-4 focus-within:ring-brand-primary/10 transition duration-200">
                            <span class="text-sm font-extrabold text-gray-400 mr-1">$</span>
                            <input type="number" name="tarifas[{{ $index }}][tarifa_por_hora]" value="{{ old('tarifas.'.$index.'.tarifa_por_hora', $tarifa->tarifa_por_hora) }}" required min="0" step="500" class="w-full text-right text-sm font-black text-brand-dark outline-none border-0 p-0 focus:ring-0">
                            <span class="text-xs font-bold text-gray-400 ml-1.5 uppercase">COP</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-6 border-t border-gray-100 mt-6">
                <h4 class="text-lg font-black text-brand-dark mb-1">Recargos para Paseadores Destacados</h4>
                <p class="text-xs text-gray-400 font-semibold mb-4">Define las reglas para que los paseadores con altas calificaciones cobren tarifas adicionales</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Calificación Mínima Requerida</label>
                        <div class="flex items-center bg-white border border-gray-200 rounded-xl px-3 py-2 focus-within:border-brand-primary transition">
                            <input type="number" name="calificacion_minima" value="{{ old('calificacion_minima', $ajustes->calificacion_minima) }}" required min="0" max="5" step="0.1" class="w-full text-left text-sm font-black text-brand-dark outline-none border-0 p-0 focus:ring-0">
                            <span class="text-xs font-bold text-gray-400 ml-1.5 uppercase">★</span>
                        </div>
                        <span class="text-[10px] text-gray-400 font-semibold mt-1">Puntaje promedio mínimo para acceder al beneficio</span>
                    </div>

                    <div class="flex flex-col p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Porcentaje Máximo de Recargo</label>
                        <div class="flex items-center bg-white border border-gray-200 rounded-xl px-3 py-2 focus-within:border-brand-primary transition">
                            <input type="number" name="porcentaje_maximo" value="{{ old('porcentaje_maximo', $ajustes->porcentaje_maximo) }}" required min="0" max="100" class="w-full text-left text-sm font-black text-brand-dark outline-none border-0 p-0 focus:ring-0">
                            <span class="text-xs font-bold text-gray-400 ml-1.5">%</span>
                        </div>
                        <span class="text-[10px] text-gray-400 font-semibold mt-1">Tope máximo de recargo que el paseador puede configurar</span>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-sm py-3.5 px-8 rounded-xl shadow-md hover:shadow-lg transition duration-200 cursor-pointer">
                    Guardar Tarifas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
