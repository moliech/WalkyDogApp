@extends('layouts.app')
@section('title', 'Editar Perfil')

@section('content')
<div class="flex justify-center py-8">
    <div class="w-full max-w-2xl bg-white p-8 rounded-3xl border border-gray-100 shadow-xl">
        <div class="mb-8">
            <h4 class="text-2xl font-black text-brand-dark">⚙️ Configuración del Perfil</h4>
            <p class="text-xs text-gray-400 font-semibold mt-1.5 leading-relaxed">Actualiza tus datos de contacto básicos. La dirección ingresada será el punto de recogida por defecto para los paseadores.</p>
        </div>
        
        <form class="space-y-6" onsubmit="event.preventDefault(); alert('Estructura de formulario validada. En el Módulo III conectaremos esta petición PUT/PATCH para actualizar la base de datos MySQL.');">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nombre Completo</label>
                    <input type="text" class="rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white" value="Jhon Esteban Molina">
                </div>
                <div class="flex flex-col">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Correo Electrónico</label>
                    <input type="email" class="rounded-xl border border-gray-100 px-4 py-3 text-sm bg-gray-50 text-gray-400 cursor-not-allowed outline-none" value="esteban.molina@cotecnova.edu.co" disabled>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Teléfono de Emergencia</label>
                    <input type="text" class="rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white" value="3123456789">
                </div>
                <div class="flex flex-col">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Dirección de Residencia (Cartago)</label>
                    <input type="text" class="rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white" value="Calle 10 # 4-50, Cartago, Valle">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('dashboard') }}" class="inline-block text-center border border-gray-200 text-brand-dark hover:border-brand-primary hover:text-brand-primary font-bold text-sm px-6 py-3 rounded-xl transition duration-200 no-underline">Cancelar</a>
                <button type="submit" class="bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-sm px-6 py-3 rounded-xl shadow-md shadow-brand-primary/10 hover:shadow-lg hover:shadow-brand-primary/20 hover:-translate-y-0.5 transition duration-200 cursor-pointer">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection