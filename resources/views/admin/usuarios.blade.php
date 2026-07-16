@extends('layouts.app')
@section('title', 'Auditoría de Usuarios')

@section('content')
<div class="py-6 mb-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-3xl font-black text-brand-dark tracking-tight">Auditoría de Usuarios Registrados</h2>
        <p class="text-gray-400 font-semibold mt-1">Consulta y gestiona la base de datos de los clientes registrados en el sistema</p>
    </div>
    <span class="text-xs font-extrabold px-3 py-1.5 rounded-full bg-brand-primary/10 text-brand-primary uppercase tracking-wider">
        Rol: Administrador
    </span>
</div>

<div class="flex gap-2 border-b border-gray-100 mb-6">
    <a href="{{ route('admin.paseadores') }}" class="py-3 px-4 text-sm font-bold text-gray-400 hover:text-brand-primary no-underline transition">
        Paseadores
    </a>
    <a href="{{ route('admin.usuarios') }}" class="py-3 px-4 text-sm font-extrabold text-brand-primary border-b-2 border-brand-primary no-underline transition">
        Usuarios Registrados
    </a>
</div>

<div class="space-y-6">
    <div>
        <h4 class="text-lg font-black text-brand-dark mb-4 flex items-center gap-2">
            <span>Clientes Registrados</span>
            <span class="text-xs bg-brand-primary/10 text-brand-primary font-bold px-2 py-0.5 rounded-full">{{ $usuarios->count() }}</span>
        </h4>
        
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-gray-100">
                            <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Cliente</th>
                            <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Teléfono</th>
                            <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Dirección</th>
                            <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs text-center">Mascotas</th>
                            <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                            <tr class="border-b border-gray-50 hover:bg-slate-50/30 transition">
                                <td class="p-4">
                                    <span class="font-extrabold text-brand-dark block">{{ $usuario->nombres }} {{ $usuario->apellidos }}</span>
                                    <span class="text-xs text-gray-400 font-semibold">{{ $usuario->email }}</span>
                                </td>
                                <td class="p-4 font-bold text-gray-500">{{ $usuario->telefono ?? 'No registrado' }}</td>
                                <td class="p-4 text-gray-500 font-semibold">{{ $usuario->direccion }}</td>
                                <td class="p-4 text-center">
                                    @if($usuario->mascotas_count > 0)
                                        <span class="text-xs font-black px-3 py-1 rounded-full bg-brand-secondary/15 text-brand-secondary">
                                            {{ $usuario->mascotas_count }}
                                        </span>
                                    @else
                                        <span class="text-xs font-extrabold px-3 py-1 rounded-full bg-gray-100 text-gray-400">
                                            Ninguna
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-xs font-bold text-gray-400">
                                    {{ \Carbon\Carbon::parse($usuario->created_at)->format('d/m/Y g:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400 italic">No hay clientes registrados en el sistema.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
