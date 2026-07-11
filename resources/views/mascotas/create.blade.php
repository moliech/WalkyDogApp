@extends('layouts.app')
@section('title', 'Registrar Mascota')

@section('content')
<div class="max-w-2xl mx-auto py-4">
    <div class="mb-6">
        <a href="{{ route('mascotas.index') }}" class="text-sm font-bold text-brand-primary hover:text-brand-primary-hover no-underline">
            ← Volver a Mis Mascotas
        </a>
        <h3 class="text-2xl font-black text-brand-dark mt-2">Registrar Nuevo Canino 🐕</h3>
        <p class="text-sm text-gray-400 font-semibold">Completa la información de tu mascota para afiliarla</p>
    </div>

    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-xl shadow-gray-100/40">
        <form method="POST" action="{{ route('mascotas.store') }}" class="space-y-5">
            @csrf

            <!-- Nombre -->
            <div>
                <label for="nombre" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nombre del canino</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Toby" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white">
                @error('nombre')
                    <span class="text-xs text-brand-accent-red font-bold mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Raza -->
            <div>
                <label for="raza" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Raza de la mascota</label>
                <input type="text" id="raza" name="raza" value="{{ old('raza') }}" placeholder="Ej: Golden Retriever" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white">
                @error('raza')
                    <span class="text-xs text-brand-accent-red font-bold mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tamaño -->
            <div>
                <label for="tamano" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Tamaño estimado</label>
                <select id="tamano" name="tamano" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white">
                    <option value="">Selecciona un tamaño</option>
                    <option value="Pequeño" {{ old('tamano') == 'Pequeño' ? 'selected' : '' }}>Pequeño (Ej: Pug, Chihuahua)</option>
                    <option value="Mediano" {{ old('tamano') == 'Mediano' ? 'selected' : '' }}>Mediano (Ej: French Poodle, Beagle)</option>
                    <option value="Grande" {{ old('tamano') == 'Grande' ? 'selected' : '' }}>Grande (Ej: Golden, Pastor Alemán)</option>
                </select>
                @error('tamano')
                    <span class="text-xs text-brand-accent-red font-bold mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Observaciones -->
            <div>
                <label for="observaciones" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Observaciones / Cuidados Especiales</label>
                <textarea id="observaciones" name="observaciones" rows="4" placeholder="Indica comportamientos, problemas de salud, medicamentos, etc..." class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white">{{ old('observaciones') }}</textarea>
                @error('observaciones')
                    <span class="text-xs text-brand-accent-red font-bold mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="flex-1 bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-sm py-3.5 px-6 rounded-xl shadow-md hover:shadow-lg transition duration-200 cursor-pointer">
                    Registrar Mascota 🐾
                </button>
                <a href="{{ route('mascotas.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold text-sm py-3.5 px-6 rounded-xl transition duration-200 text-center no-underline">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection