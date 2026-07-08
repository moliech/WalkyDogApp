@extends('layouts.app')
@section('title', 'Mis Mascotas')

@section('content')
<div class="flex justify-between items-center mb-8 py-2">
    <h3 class="text-2xl font-black text-brand-dark m-0">Mascotas Asociadas</h3>
    <button class="border border-gray-200 text-gray-400 font-bold text-xs px-4 py-2.5 rounded-xl bg-gray-50/50 cursor-not-allowed" disabled>+ Agregar (Módulo III)</button>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($mascotas as $mascota)
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-1 transition duration-300 flex flex-col h-full">
            <div class="bg-gradient-to-br from-brand-primary/5 to-brand-primary/10 h-32 flex items-center justify-center text-5xl border-b border-gray-100">
                @if($mascota['nombre'] == 'Toby')
                    🦮
                @elseif($mascota['nombre'] == 'Luna')
                    🐶
                @else
                    🐕
                @endif
            </div>
            <div class="p-6 flex-1 flex flex-col justify-between">
                <h5 class="text-lg font-black text-brand-dark mb-4">{{ $mascota['nombre'] }}</h5>
                
                <div class="flex justify-between items-center mb-2.5">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Raza:</span>
                    <span class="text-sm font-extrabold text-brand-dark">{{ $mascota['raza'] }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tamaño:</span>
                    <span class="text-xs font-extrabold px-3 py-1.5 rounded-full inline-block @if($mascota['tamano'] == 'Grande') bg-brand-primary/10 text-brand-primary @elseif($mascota['tamano'] == 'Pequeño') bg-brand-secondary/15 text-brand-secondary @else bg-amber-400/10 text-amber-500 @endif">
                        {{ $mascota['tamano'] }}
                    </span>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection