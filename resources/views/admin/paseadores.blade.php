@extends('layouts.app')
@section('title', 'Auditoría de Paseadores')

@section('content')
<div class="py-6 mb-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-3xl font-black text-brand-dark tracking-tight">Auditoría de Paseadores</h2>
        <p class="text-gray-400 font-semibold mt-1">Valida los soportes y documentos de identidad de los paseadores postulados</p>
    </div>
    <span class="text-xs font-extrabold px-3 py-1.5 rounded-full bg-brand-primary/10 text-brand-primary uppercase tracking-wider">
        Rol: Administrador
    </span>
</div>

<div class="flex gap-2 border-b border-gray-100 mb-6">
    <a href="{{ route('admin.paseadores') }}" class="py-3 px-4 text-sm font-extrabold text-brand-primary border-b-2 border-brand-primary no-underline transition flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
        </svg>
        Paseadores
    </a>
    <a href="{{ route('admin.usuarios') }}" class="py-3 px-4 text-sm font-bold text-gray-400 hover:text-brand-primary no-underline transition flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>
        </svg>
        Usuarios Registrados
    </a>
</div>

<div class="space-y-12">
    <!-- SECCIÓN: Postulaciones Pendientes -->
    <div>
        <h4 class="text-lg font-black text-brand-dark mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <span>Postulaciones Pendientes</span>
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
            <svg class="w-5 h-5 text-brand-secondary" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <span>Paseadores Activos</span>
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
                            <td class="p-4 text-brand-primary font-black">
                                <svg class="w-3.5 h-3.5 inline-block text-amber-500 mr-0.5 align-text-bottom" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                {{ number_format($perfil->calificacion_promedio, 1) }}
                            </td>
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
