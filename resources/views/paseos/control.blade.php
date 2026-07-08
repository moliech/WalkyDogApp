@extends('layouts.app')
@section('title', 'Panel del Paseador')

@section('content')
<div class="flex justify-center py-8">
    <div class="w-full max-w-sm bg-white p-8 rounded-3xl border border-gray-100 shadow-xl text-center">
        <div class="mb-6">
            <span class="text-4xl mb-2 inline-block">📱</span>
            <h4 class="text-xl font-black text-brand-dark mt-1">Panel del Paseador</h4>
            <span class="text-[10px] font-extrabold uppercase tracking-widest bg-amber-400/10 text-amber-600 px-3 py-1 rounded-full inline-block mt-2">Modo Activo</span>
        </div>
        
        <div class="bg-slate-50/80 p-5 rounded-2xl text-left border border-gray-100/50 mb-6 space-y-4">
            <div class="text-center pb-3 border-b border-gray-200/50">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Servicio Asignado</span>
                <h5 class="text-base font-black text-brand-dark mb-0">Orden #{{ $paseoAsignado['id'] }}</h5>
            </div>
            
            <div class="flex justify-between items-center text-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mascota:</span>
                <span class="font-extrabold text-brand-dark">🐶 {{ $paseoAsignado['mascota'] }}</span>
            </div>
            
            <div class="flex justify-between items-center text-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Dueño:</span>
                <span class="font-extrabold text-brand-dark">{{ $paseoAsignado['propietario'] }}</span>
            </div>
        </div>
        
        <div class="space-y-3">
            <button class="w-full bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-sm py-4 px-6 rounded-2xl shadow-md shadow-brand-primary/10 hover:shadow-lg hover:shadow-brand-primary/20 hover:-translate-y-0.5 transition duration-200 cursor-pointer" onclick="alert('Iniciando rastreo GPS e interactuando con la cámara para validación QR...')">
                📷 Escanear QR para Iniciar
            </button>
            <button class="w-full border border-gray-100 text-gray-300 font-bold text-sm py-4 px-6 rounded-2xl bg-gray-50/50 cursor-not-allowed" disabled>
                🛑 Finalizar Servicio
            </button>
        </div>
    </div>
</div>
@endsection