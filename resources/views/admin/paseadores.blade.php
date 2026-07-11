@extends('layouts.app')
@section('title', 'Auditoría de Paseadores')

@section('content')
<div class="py-6 mb-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-3xl font-black text-brand-dark tracking-tight">Auditoría de Paseadores 🚶🔍</h2>
        <p class="text-gray-400 font-semibold mt-1">Valida los soportes y documentos de identidad de los paseadores postulados</p>
    </div>
    <span class="text-xs font-extrabold px-3 py-1.5 rounded-full bg-brand-primary/10 text-brand-primary uppercase tracking-wider">
        Rol: Administrador
    </span>
</div>

<div class="space-y-12">
    <!-- SECCIÓN: Postulaciones Pendientes -->
    <div>
        <h4 class="text-lg font-black text-brand-dark mb-4 flex items-center gap-2">
            <span>⏳ Postulaciones Pendientes</span>
            <span class="text-xs bg-amber-400/10 text-amber-500 font-bold px-2 py-0.5 rounded-full">{{ $perfilesPendientes->count() }}</span>
        </h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($perfilesPendientes as $perfil)
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-xl flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h5 class="text-base font-black text-brand-dark mb-0">{{ $perfil->user->nombres }} {{ $perfil->user->apellidos }}</h5>
                                <p class="text-xs text-gray-400 font-semibold">{{ $perfil->user->email }}</p>
                            </div>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest bg-amber-400/10 text-amber-600 px-2.5 py-1 rounded-full">Pendiente</span>
                        </div>
                        
                        <div class="space-y-2 mb-6 text-xs text-gray-500">
                            <div class="flex justify-between">
                                <span class="font-bold uppercase tracking-wider text-gray-400">Cédula:</span>
                                <span class="font-bold text-brand-dark">{{ $perfil->identificacion }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-bold uppercase tracking-wider text-gray-400">Experiencia:</span>
                                <span class="font-bold text-brand-dark">{{ $perfil->experiencia_meses }} meses</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-bold uppercase tracking-wider text-gray-400">Teléfono:</span>
                                <span class="font-bold text-brand-dark">{{ $perfil->user->telefono ?? 'No registrado' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-bold uppercase tracking-wider text-gray-400">Dirección:</span>
                                <span class="font-bold text-brand-dark">{{ $perfil->user->direccion }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-4 border-t border-gray-100">
                        <form method="POST" action="{{ route('admin.paseadores.aprobar', $perfil->id) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-brand-secondary hover:bg-emerald-600 text-white font-extrabold text-xs py-2.5 px-4 rounded-xl shadow-sm transition">
                                ✓ Aprobar Paseador
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.paseadores.rechazar', $perfil->id) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-brand-accent-red/10 hover:bg-brand-accent-red hover:text-white text-brand-accent-red font-extrabold text-xs py-2.5 px-4 rounded-xl transition">
                                ✗ Rechazar
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-gray-50/50 p-8 text-center rounded-2xl border-2 border-dashed border-gray-200 text-gray-400 text-sm font-medium">
                    No hay solicitudes pendientes en este momento.
                </div>
            @endforelse
        </div>
    </div>

    <!-- SECCIÓN: Paseadores Activos -->
    <div>
        <h4 class="text-lg font-black text-brand-dark mb-4 flex items-center gap-2">
            <span>✅ Paseadores Activos</span>
            <span class="text-xs bg-brand-secondary/15 text-brand-secondary font-bold px-2 py-0.5 rounded-full">{{ $perfilesActivos->count() }}</span>
        </h4>
        
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100">
                        <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Paseador</th>
                        <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Cédula</th>
                        <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Experiencia</th>
                        <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Calificación</th>
                        <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perfilesActivos as $perfil)
                        <tr class="border-b border-gray-50 hover:bg-slate-50/30 transition">
                            <td class="p-4">
                                <span class="font-extrabold text-brand-dark block">{{ $perfil->user->nombres }} {{ $perfil->user->apellidos }}</span>
                                <span class="text-xs text-gray-400">{{ $perfil->user->email }}</span>
                            </td>
                            <td class="p-4 font-mono font-bold text-gray-500">{{ $perfil->identificacion }}</td>
                            <td class="p-4 font-bold text-brand-dark">{{ $perfil->experiencia_meses }} meses</td>
                            <td class="p-4 text-brand-primary font-black">⭐ {{ number_format($perfil->calificacion_promedio, 1) }}</td>
                            <td class="p-4">
                                <form method="POST" action="{{ route('admin.paseadores.rechazar', $perfil->id) }}">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold text-brand-accent-red hover:underline bg-transparent border-0 cursor-pointer">
                                        Desactivar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400 italic">No hay paseadores aprobados en el sistema.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
