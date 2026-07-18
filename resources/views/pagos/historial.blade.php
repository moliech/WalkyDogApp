@extends('layouts.app')
@section('title', 'Historial de Pagos')

@section('content')
<div class="py-6 mb-6 border-b border-gray-100">
    <h2 class="text-3xl font-black text-brand-dark tracking-tight">Historial de Pagos</h2>
    <p class="text-gray-400 font-semibold mt-1">Revisa el estado de tus transacciones y cobros por paseos agendados</p>
</div>

<form action="{{ route('pagos.historial') }}" method="GET" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Búsqueda por Mascota -->
        <div class="flex flex-col">
            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Mascota</label>
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Nombre de mascota..." class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-brand-primary outline-none bg-white">
        </div>

        <!-- Filtro de Estado -->
        <div class="flex flex-col">
            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Estado Paseo</label>
            <select name="estado" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-brand-primary outline-none bg-white">
                <option value="">Todos</option>
                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente (Espera Aceptación)</option>
                <option value="esperando_pago" {{ request('estado') == 'esperando_pago' ? 'selected' : '' }}>Esperando Pago</option>
                <option value="programado" {{ request('estado') == 'programado' ? 'selected' : '' }}>Programado</option>
                <option value="en_progreso" {{ request('estado') == 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
                <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado / Rechazado</option>
            </select>
        </div>

        <!-- Rango Fechas: Desde -->
        <div class="flex flex-col">
            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Desde</label>
            <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-brand-primary outline-none bg-white">
        </div>

        <!-- Rango Fechas: Hasta -->
        <div class="flex flex-col">
            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Hasta</label>
            <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-brand-primary outline-none bg-white">
        </div>
    </div>
    
    <div class="flex flex-wrap items-center justify-between gap-3 mt-4 pt-4 border-t border-gray-50">
        <div>
            <a href="{{ route('paseos.exportar-pdf', request()->query()) }}" class="inline-flex items-center gap-1.5 text-xs font-extrabold px-4 py-2.5 rounded-xl transition duration-200 no-underline shadow-sm hover:brightness-95" style="background-color: #ef4444 !important; color: #ffffff !important;">
                <!-- SVG Icono Descargar -->
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                Exportar PDF
            </a>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('pagos.historial') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-extrabold rounded-xl transition duration-200 no-underline">
                Limpiar
            </a>
            <button type="submit" class="px-5 py-2.5 bg-brand-primary hover:bg-brand-primary-hover text-white text-xs font-extrabold rounded-xl shadow-md shadow-brand-primary/10 transition duration-200 cursor-pointer">
                Filtrar Resultados
            </button>
        </div>
    </div>
</form>

<div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
    <table class="w-full text-left border-collapse text-sm">
        <thead>
            <tr class="bg-slate-50 border-b border-gray-100">
                <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Fecha / Hora</th>
                <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Referencia</th>
                <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Mascota</th>
                <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Paseador</th>
                <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Monto (COP)</th>
                <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Estado Pago</th>
                <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Detalle Paseo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paseos as $p)
                <tr class="border-b border-gray-50 hover:bg-slate-50/30 transition">
                    <td class="p-4 font-semibold text-gray-500">
                        {{ \Carbon\Carbon::parse($p->pago->created_at)->format('d/m/Y g:i A') }}
                    </td>
                    <td class="p-4 font-mono font-bold text-brand-dark">
                        #{{ $p->id }}
                    </td>
                    <td class="p-4">
                        <span class="font-extrabold text-brand-dark flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                            </svg>
                            {{ $p->mascota->nombre }}
                        </span>
                        <span class="text-xs text-gray-400 block pl-5.5">{{ $p->mascota->raza }}</span>
                    </td>
                    <td class="p-4">
                        <span class="font-extrabold text-brand-dark flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                            </svg>
                            {{ $p->paseador->nombres }} {{ $p->paseador->apellidos }}
                        </span>
                    </td>
                    <td class="p-4 font-mono font-black text-brand-dark">
                        ${{ number_format($p->pago->monto, 0, ',', '.') }}
                    </td>
                    <td class="p-4">
                        @if($p->pago->estado_pago == 'approved')
                            <span class="text-[10px] font-extrabold uppercase tracking-widest bg-emerald-500/10 text-emerald-600 px-2.5 py-1 rounded-full">Aprobado</span>
                        @elseif($p->pago->estado_pago == 'pending')
                            <span class="text-[10px] font-extrabold uppercase tracking-widest bg-amber-400/10 text-amber-600 px-2.5 py-1 rounded-full">Pendiente</span>
                        @else
                            <span class="text-[10px] font-extrabold uppercase tracking-widest bg-red-500/10 text-red-600 px-2.5 py-1 rounded-full">Rechazado</span>
                        @endif
                    </td>
                    <td class="p-4">
                        @if($p->pago->estado_pago == 'pending')
                            @if($p->estado == 'pendiente')
                                <span class="text-xs text-amber-500 font-bold">Espera Aceptación</span>
                            @elseif($p->estado == 'esperando_pago')
                                <a href="{{ route('pagos.simulacion', $p->id) }}" class="inline-block bg-brand-primary hover:bg-brand-primary-hover text-white text-xs font-extrabold px-3 py-1.5 rounded-lg no-underline transition">
                                    Pagar ahora →
                                </a>
                            @elseif($p->estado == 'cancelado')
                                <span class="text-xs text-red-500 font-bold">Rechazado</span>
                            @endif
                        @else
                            <span class="text-xs text-gray-400 font-semibold capitalize">Paseo {{ str_replace('_', ' ', $p->estado) }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-12 text-center text-gray-400 italic">
                        <!-- Icono SVG de Tarjeta de Crédito en lugar de emoji -->
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-6.188 3.197L1.125 19.5V4.5h21.75V19.5l-2.188-1.053a2.25 2.25 0 0 0-2.074 0l-2.188 1.053a2.25 2.25 0 0 1-2.074 0l-2.188-1.053a2.25 2.25 0 0 0-2.074 0L8.25 19.5l-2.188-1.053a2.25 2.25 0 0 0-2.074 0l-2.188 1.053a2.25 2.25 0 0 1-2.074 0Z"/>
                        </svg>
                        No has realizado ninguna transacción todavía.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($paseos->hasPages())
    <div class="mt-6 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        {{ $paseos->links() }}
    </div>
@endif
@endsection
