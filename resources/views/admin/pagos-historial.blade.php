@extends('layouts.app')
@section('title', 'Historial Global de Pagos')

@section('content')
<div class="py-6 mb-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-3xl font-black text-brand-dark tracking-tight">Auditoría Global de Pagos</h2>
        <p class="text-gray-400 font-semibold mt-1">Monitorea y exporta el historial consolidado de transacciones de paseos</p>
    </div>
    <span class="text-xs font-extrabold px-3 py-1.5 rounded-full bg-brand-primary/10 text-brand-primary uppercase tracking-wider">
        Rol: Administrador
    </span>
</div>

<!-- Filtros de Auditoría -->
<form action="{{ route('admin.pagos.historial') }}" method="GET" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Paseador -->
        <div class="flex flex-col">
            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Paseador</label>
            <select name="paseador_id" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-brand-primary outline-none bg-white font-semibold text-brand-dark">
                <option value="">Todos los paseadores</option>
                @foreach($paseadores as $paseador)
                    <option value="{{ $paseador->id }}" {{ request('paseador_id') == $paseador->id ? 'selected' : '' }}>
                        {{ $paseador->nombres }} {{ $paseador->apellidos }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Propietario -->
        <div class="flex flex-col">
            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Propietario / Cliente</label>
            <select name="propietario_id" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-brand-primary outline-none bg-white font-semibold text-brand-dark">
                <option value="">Todos los propietarios</option>
                @foreach($propietarios as $prop)
                    <option value="{{ $prop->id }}" {{ request('propietario_id') == $prop->id ? 'selected' : '' }}>
                        {{ $prop->nombres }} {{ $prop->apellidos }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Estado del Paseo -->
        <div class="flex flex-col">
            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Estado Paseo</label>
            <select name="estado" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-brand-primary outline-none bg-white font-semibold text-brand-dark">
                <option value="">Todos los estados</option>
                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="esperando_pago" {{ request('estado') == 'esperando_pago' ? 'selected' : '' }}>Esperando Pago</option>
                <option value="programado" {{ request('estado') == 'programado' ? 'selected' : '' }}>Programado</option>
                <option value="en_progreso" {{ request('estado') == 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
                <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>Finalizado / Completado</option>
                <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado / Rechazado</option>
            </select>
        </div>

        <!-- Fecha Desde -->
        <div class="flex flex-col">
            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Desde</label>
            <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-brand-primary outline-none bg-white text-brand-dark font-semibold">
        </div>

        <!-- Fecha Hasta -->
        <div class="flex flex-col">
            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Hasta</label>
            <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-brand-primary outline-none bg-white text-brand-dark font-semibold">
        </div>
    </div>
    
    <div class="flex flex-wrap items-center justify-between gap-3 mt-5 pt-4 border-t border-gray-50">
        <div>
            <a href="{{ route('admin.pagos.exportar-pdf', request()->query()) }}" class="inline-flex items-center gap-1.5 text-xs font-extrabold px-4 py-2.5 rounded-xl transition duration-200 no-underline shadow-sm hover:brightness-95" style="background-color: #ef4444 !important; color: #ffffff !important;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                Exportar Reporte PDF
            </a>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.pagos.historial') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-extrabold rounded-xl transition duration-200 no-underline">
                Limpiar Filtros
            </a>
            <button type="submit" class="px-5 py-2.5 bg-brand-primary hover:bg-brand-primary-hover text-white text-xs font-extrabold rounded-xl shadow-md shadow-brand-primary/10 transition duration-200 cursor-pointer">
                Aplicar Filtros
            </button>
        </div>
    </div>
</form>

<!-- Listado de Auditoría -->
<div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm shadow-gray-100/30">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm min-w-[850px]">
        <thead>
            <tr class="bg-slate-50 border-b border-gray-100">
                <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Fecha</th>
                <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Ref. Paseo</th>
                <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Cliente / Mascota</th>
                <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Paseador</th>
                <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Transacción (COP)</th>
                <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Estado Pago</th>
                <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Estado Paseo</th>
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
                            {{ $p->mascota->propietario->nombres }} {{ $p->mascota->propietario->apellidos }}
                        </span>
                        <span class="text-xs text-gray-400 block mt-0.5">
                            Mascota: <strong class="text-gray-500">{{ $p->mascota->nombre }}</strong> ({{ $p->mascota->tamano }})
                        </span>
                    </td>
                    <td class="p-4">
                        <span class="font-extrabold text-brand-dark block">
                            {{ $p->paseador->nombres }} {{ $p->paseador->apellidos }}
                        </span>
                        @if($p->paseador->perfilPaseador)
                            <span class="text-[10px] text-gray-400 font-bold block mt-0.5">
                                Calificación: {{ number_format($p->paseador->perfilPaseador->calificacion_promedio, 2) }} ★ 
                                @if($p->paseador->perfilPaseador->porcentaje_recargo > 0)
                                    | Recargo: +{{ $p->paseador->perfilPaseador->porcentaje_recargo }}%
                                @endif
                            </span>
                        @endif
                    </td>
                    <td class="p-4">
                        <span class="font-mono font-black text-brand-dark block">
                            ${{ number_format($p->pago->monto, 0, ',', '.') }}
                        </span>
                        <span class="text-[10px] text-gray-400 font-bold block mt-0.5">
                            Base + Recargo p/h
                        </span>
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
                        <span class="text-xs font-bold text-gray-500 capitalize bg-slate-100 px-2 py-1 rounded-lg">
                            {{ str_replace('_', ' ', $p->estado) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-12 text-center text-gray-400 italic">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-6.188 3.197L1.125 19.5V4.5h21.75V19.5l-2.188-1.053a2.25 2.25 0 0 0-2.074 0l-2.188 1.053a2.25 2.25 0 0 1-2.074 0l-2.188-1.053a2.25 2.25 0 0 0-2.074 0L8.25 19.5l-2.188-1.053a2.25 2.25 0 0 0-2.074 0l-2.188 1.053a2.25 2.25 0 0 1-2.074 0Z"/>
                        </svg>
                        No se encontraron registros de transacciones para los filtros seleccionados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

@if($paseos->hasPages())
    <div class="mt-6 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        {{ $paseos->links() }}
    </div>
@endif
@endsection
