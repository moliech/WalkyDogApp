@extends('layouts.app')
@section('title', 'Publicaciones de la Comunidad')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-brand-dark tracking-tight">Publicaciones de la Comunidad</h2>
        <p class="text-gray-400 font-semibold mt-1">Contenido informativo integrado desde nuestra API de prensa externa</p>
    </div>

    @if($error)
        <!-- Alerta ante fallos de conexión externa -->
        <div class="bg-red-50 border border-red-200 text-red-700 p-6 rounded-3xl flex flex-col items-center text-center space-y-3 shadow-md">
            <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
            </svg>
            <div>
                <h5 class="font-bold text-lg">Error de Sincronización</h5>
                <p class="text-sm mt-1 opacity-90">{{ $error }} Por favor, verifica tu conexión a internet o reintenta más tarde.</p>
            </div>
        </div>
    @else
        <!-- Listado de Publicaciones -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($posts as $post)
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl hover:shadow-2xl transition duration-300 flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-brand-primary bg-brand-primary/10 px-2.5 py-1 rounded-full mb-3 inline-block">Publicidad #{{ $post['id'] }}</span>
                        <h4 class="text-base font-black text-brand-dark mb-2 leading-snug capitalize">{{ $post['title'] }}</h4>
                        <p class="text-sm text-gray-500 leading-relaxed capitalize">{{ $post['body'] }}</p>
                    </div>
                    <div class="pt-4 mt-4 border-t border-slate-50 flex items-center justify-between text-xs text-gray-400 font-semibold">
                        <span>Autor ID: {{ $post['userId'] }}</span>
                        <span class="text-brand-primary hover:underline cursor-pointer">Leer más →</span>
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-white p-12 text-center rounded-3xl border border-slate-100 shadow-xl">
                    <p class="text-gray-400 italic">No hay publicaciones disponibles en este momento.</p>
                </div>
            @endforelse
        </div>
    @endif
</div>
@endsection