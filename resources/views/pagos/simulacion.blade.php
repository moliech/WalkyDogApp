@extends('layouts.app')
@section('title', 'Simulador de Pago')

@section('content')
@php
    $mascota = $paseo->mascota;
    $paseador = $paseo->paseador;
    
    // Tarifa base por tamaño
    $tamanoObj = \App\Models\MascotaTamano::where('nombre', $mascota->tamano)->first();
    $tarifaBase = $tamanoObj ? $tamanoObj->tarifa_por_hora : 12000;
    
    // Recargo del paseador
    $porcentajeRecargo = 0;
    $recargoMonto = 0;
    if ($paseador && $paseador->perfilPaseador) {
        $perfil = $paseador->perfilPaseador;
        $ajustes = \App\Models\AjusteTarifa::first();
        $minCalificacion = $ajustes ? $ajustes->calificacion_minima : 4.5;
        $maxPorcentaje = $ajustes ? $ajustes->porcentaje_maximo : 20;
        
        if ($perfil->calificacion_promedio >= $minCalificacion && $perfil->porcentaje_recargo > 0) {
            $porcentajeRecargo = min($perfil->porcentaje_recargo, $maxPorcentaje);
            $recargoMonto = ($tarifaBase * $porcentajeRecargo) / 100;
        }
    }
    
    // Tarifa total por hora
    $tarifaPorHoraTotal = $tarifaBase + $recargoMonto;
    
    // Duración calculada
    $duracionHoras = 1;
    if ($tarifaPorHoraTotal > 0) {
        $duracionHoras = round($paseo->pago->monto / $tarifaPorHoraTotal);
    }
@endphp

<div class="flex justify-center py-8">
    <div class="w-full max-w-md bg-white p-8 rounded-3xl border border-gray-100 shadow-xl text-center">
        <div class="mb-6">
            <!-- Icono SVG de Tarjeta en el Simulador -->
            <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-brand-primary/10 text-brand-primary mx-auto mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-6.188 3.197L1.125 19.5V4.5h21.75V19.5l-2.188-1.053a2.25 2.25 0 0 0-2.074 0l-2.188 1.053a2.25 2.25 0 0 1-2.074 0l-2.188-1.053a2.25 2.25 0 0 0-2.074 0L8.25 19.5l-2.188-1.053a2.25 2.25 0 0 0-2.074 0l-2.188 1.053a2.25 2.25 0 0 1-2.074 0Z"/>
                </svg>
            </div>
            <h4 class="text-xl font-black text-brand-dark mt-1">Simulador de Pago</h4>
            <p class="text-xs text-gray-400 font-semibold mt-1">Entorno Seguro de Pruebas Académicas</p>
        </div>

        <div class="bg-slate-50/80 p-5 rounded-2xl text-left border border-gray-100/50 mb-6 space-y-3.5">
            <h6 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider text-center pb-3 border-b border-gray-200/50 mb-1">Resumen del Servicio</h6>
            
            <div class="flex justify-between items-center text-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Referencia:</span>
                <span class="font-mono font-extrabold text-brand-dark">#{{ $paseo->id }}</span>
            </div>
            
            <div class="flex justify-between items-center text-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mascota:</span>
                <span class="font-extrabold text-brand-dark flex items-center gap-1">
                    <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                    </svg>
                    {{ $paseo->mascota->nombre }}
                </span>
            </div>
            
            <div class="flex justify-between items-center text-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Duración:</span>
                <span class="font-extrabold text-brand-dark">{{ $duracionHoras }} Horas</span>
            </div>

            <div class="flex justify-between items-center text-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Paseador:</span>
                <span class="font-extrabold text-brand-dark flex items-center gap-1">
                    <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                    </svg>
                    {{ $paseo->paseador->nombres }} {{ $paseo->paseador->apellidos }}
                </span>
            </div>

            <div class="flex justify-between items-center text-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tarifa Base:</span>
                <span class="font-extrabold text-brand-dark">${{ number_format($tarifaBase, 0, ',', '.') }} COP / hora</span>
            </div>

            @if($porcentajeRecargo > 0)
                <div class="flex justify-between items-center text-sm bg-brand-primary/5 p-2 rounded-lg border border-brand-primary/10">
                    <span class="text-xs font-black text-brand-primary uppercase tracking-wider">Recargo Destacado:</span>
                    <span class="font-extrabold text-brand-primary">+{{ $porcentajeRecargo }}% (+${{ number_format($recargoMonto, 0, ',', '.') }} COP/h)</span>
                </div>
            @endif
            
            <hr class="border-t border-dashed border-gray-200/80 my-3.5">
            
            <div class="flex justify-between items-center">
                <span class="text-sm font-black text-brand-dark">Total a Pagar:</span>
                <span class="text-lg font-black text-brand-secondary">${{ number_format($paseo->pago->monto, 0, ',', '.') }} COP</span>
            </div>
        </div>

        <div class="space-y-2">
            <form method="POST" action="{{ route('pagos.confirmar', $paseo->id) }}">
                @csrf
                <button type="submit" class="w-full bg-brand-secondary hover:bg-emerald-600 text-white font-extrabold text-sm py-4 px-6 rounded-2xl shadow-md shadow-brand-secondary/10 hover:shadow-lg hover:shadow-brand-secondary/20 hover:-translate-y-0.5 transition duration-200 cursor-pointer">
                    Autorizar Transacción
                </button>
            </form>
            <a href="{{ route('dashboard') }}" class="block text-center text-xs font-extrabold text-brand-accent-red hover:underline mt-2 pt-2.5 cursor-pointer no-underline">
                Cancelar Pago
            </a>
        </div>
    </div>
</div>
@endsection