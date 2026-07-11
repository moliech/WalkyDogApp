@extends('layouts.app')
@section('title', 'Historial de Pagos')

@section('content')
<div class="py-6 mb-6 border-b border-gray-100">
    <h2 class="text-3xl font-black text-brand-dark tracking-tight">Historial de Pagos 💳</h2>
    <p class="text-gray-400 font-semibold mt-1">Revisa el estado de tus transacciones y cobros por paseos agendados</p>
</div>

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
                        <span class="font-extrabold text-brand-dark">🐶 {{ $p->mascota->nombre }}</span>
                        <span class="text-xs text-gray-400 block">{{ $p->mascota->raza }}</span>
                    </td>
                    <td class="p-4">
                        <span class="font-extrabold text-brand-dark">🚶 {{ $p->paseador->nombres }} {{ $p->paseador->apellidos }}</span>
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
                            <a href="{{ route('pagos.simulacion', $p->id) }}" class="text-xs font-bold text-brand-primary hover:underline">
                                Pagar ahora →
                            </a>
                        @else
                            <span class="text-xs text-gray-400 capitalize">Paseo {{ $p->estado }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-12 text-center text-gray-400 italic">
                        <span class="text-4xl block mb-2">💳</span>
                        No has realizado ninguna transacción todavía.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
