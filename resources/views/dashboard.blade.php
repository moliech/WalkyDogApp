@extends('layouts.app')
@section('title', 'Panel Admin')

@section('content')
<div class="py-6 mb-6">
    <h2 class="text-3xl font-black text-brand-dark tracking-tight">Panel Administrativo WalkyDog</h2>
    <p class="text-gray-400 font-semibold mt-1">Módulo II - Renderización de Entidades Principales (Mock Data)</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Paseos Activos -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition duration-300 flex items-center justify-between border-t-4 border-t-brand-primary">
        <div>
            <h6 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider">Paseos Activos</h6>
            <h2 class="text-3xl font-black text-brand-dark mt-2">{{ $metricas['paseos_activos'] }}</h2>
        </div>
        <div class="w-12 h-12 flex items-center justify-center rounded-xl text-xl bg-brand-primary/10 text-brand-primary">
            🦮
        </div>
    </div>

    <!-- Mascotas Totales -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition duration-300 flex items-center justify-between border-t-4 border-t-brand-secondary">
        <div>
            <h6 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider">Mascotas Totales</h6>
            <h2 class="text-3xl font-black text-brand-dark mt-2">{{ $metricas['mascotas_totales'] }}</h2>
        </div>
        <div class="w-12 h-12 flex items-center justify-center rounded-xl text-xl bg-brand-secondary/15 text-brand-secondary">
            🐶
        </div>
    </div>

    <!-- Paseadores Disponibles -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition duration-300 flex items-center justify-between border-t-4 border-t-amber-400">
        <div>
            <h6 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider">Paseadores</h6>
            <h2 class="text-3xl font-black text-brand-dark mt-2">{{ $metricas['paseadores_disponibles'] }}</h2>
        </div>
        <div class="w-12 h-12 flex items-center justify-center rounded-xl text-xl bg-amber-400/10 text-amber-500">
            🚶
        </div>
    </div>

    <!-- Alertas SOS -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition duration-300 flex items-center justify-between border-t-4 border-t-brand-accent-red">
        <div>
            <h6 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider">Alertas SOS</h6>
            <h2 class="text-3xl font-black text-brand-dark mt-2">{{ $metricas['alertas_sos'] }}</h2>
        </div>
        <div class="w-12 h-12 flex items-center justify-center rounded-xl text-xl bg-brand-accent-red/10 text-brand-accent-red">
            🚨
        </div>
    </div>
</div>
@endsection