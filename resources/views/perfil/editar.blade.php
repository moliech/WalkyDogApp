@extends('layouts.app')
@section('title', 'Editar Perfil')

@section('content')
<div class="flex justify-center py-8">
    <div class="w-full max-w-2xl bg-white p-8 rounded-3xl border border-gray-100 shadow-xl">
        <div class="mb-8">
            <h4 class="text-2xl font-black text-brand-dark">⚙️ Configuración del Perfil</h4>
            <p class="text-xs text-gray-400 font-semibold mt-1.5 leading-relaxed">Actualiza tus datos de contacto básicos. La dirección ingresada será el punto de recogida por defecto para los paseadores.</p>
        </div>
        
        <form class="space-y-6" onsubmit="event.preventDefault(); alert('Estructura de formulario validada. En el Módulo IV conectaremos esta petición PUT/PATCH para actualizar la base de datos MySQL.');">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nombre Completo</label>
                    <input type="text" class="rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white" value="{{ $usuario['nombre'] }}">
                </div>
                <div class="flex flex-col">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Correo Electrónico</label>
                    <input type="email" class="rounded-xl border border-gray-100 px-4 py-3 text-sm bg-gray-50 text-gray-400 cursor-not-allowed outline-none" value="{{ $usuario['email'] }}" disabled>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Teléfono de Emergencia</label>
                    <input type="text" class="rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white" value="{{ $usuario['telefono'] }}">
                </div>
                <div class="flex flex-col">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Dirección de Residencia (Cartago)</label>
                    <input type="text" class="rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white" value="{{ $usuario['direccion'] }}">
                </div>
            </div>

            <!-- Campos condicionales para Paseador -->
            @if($usuario['es_paseador'])
                <hr class="border-t border-gray-100 my-6">
                <div class="mb-4">
                    <h5 class="text-sm font-black text-brand-dark flex items-center gap-1.5">
                        <span>📋 Información del Perfil de Paseador</span>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-0.5 rounded-full 
                            @if($usuario['estado_paseador'] == 'activo') bg-emerald-500/10 text-emerald-600
                            @elseif($usuario['estado_paseador'] == 'pendiente') bg-amber-400/10 text-amber-600
                            @else bg-red-500/10 text-red-600
                            @endif">
                            {{ $usuario['estado_paseador'] }}
                        </span>
                    </h5>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Identificación (Cédula)</label>
                        <input type="text" class="rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white" value="{{ $usuario['identificacion'] }}" placeholder="Ej: 1118000000">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Experiencia (Meses)</label>
                        <input type="number" class="rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white" value="{{ $usuario['experiencia_meses'] }}" placeholder="Ej: 12">
                    </div>
                </div>
                <div class="flex flex-col mt-4">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Documento de Soporte (Identificación/Cédula PDF)</label>
                    <input type="file" class="rounded-xl border border-gray-200 px-4 py-2 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white">
                    <span class="text-[10px] text-gray-400 mt-1 leading-relaxed">Sube tu cédula o carta de experiencia en formato PDF. El Administrador verificará este documento antes de activar tu perfil.</span>
                </div>
            @endif

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('dashboard') }}" class="inline-block text-center border border-gray-200 text-brand-dark hover:border-brand-primary hover:text-brand-primary font-bold text-sm px-6 py-3 rounded-xl transition duration-200 no-underline">Cancelar</a>
                <button type="submit" class="bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-sm px-6 py-3 rounded-xl shadow-md shadow-brand-primary/10 hover:shadow-lg hover:shadow-brand-primary/20 hover:-translate-y-0.5 transition duration-200 cursor-pointer">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection