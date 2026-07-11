@extends('layouts.app')
@section('title', 'Simulador de Pago')

@section('content')
<div class="flex justify-center py-8">
    <div class="w-full max-w-md bg-white p-8 rounded-3xl border border-gray-100 shadow-xl text-center">
        <div class="mb-6">
            <span class="text-4xl mb-2 inline-block">💳</span>
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
                <span class="font-extrabold text-brand-dark">🐶 {{ $paseo->mascota->nombre }}</span>
            </div>
            
            <div class="flex justify-between items-center text-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Duración:</span>
                <span class="font-extrabold text-brand-dark">{{ number_format($paseo->pago->monto / 12000, 0) }} Horas</span>
            </div>

            <div class="flex justify-between items-center text-sm">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Paseador:</span>
                <span class="font-extrabold text-brand-dark">🚶 {{ $paseo->paseador->nombres }} {{ $paseo->paseador->apellidos }}</span>
            </div>
            
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
                    ✅ Autorizar Transacción
                </button>
            </form>
            <a href="{{ route('dashboard') }}" class="block text-center text-xs font-extrabold text-brand-accent-red hover:underline mt-2 pt-2.5 cursor-pointer no-underline">
                Cancelar Pago
            </a>
        </div>
    </div>
</div>
@endsection