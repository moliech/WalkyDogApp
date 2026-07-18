@extends('layouts.app')
@section('title', 'Editar Perfil')

@section('content')
<div class="flex justify-center py-8">
    <div class="w-full max-w-2xl bg-white p-8 rounded-3xl border border-gray-100 shadow-xl">
        <div class="mb-8">
            <h4 class="text-2xl font-black text-brand-dark">Configuración del Perfil</h4>
            <p class="text-xs text-gray-400 font-semibold mt-1.5 leading-relaxed">Actualiza tus datos de contacto básicos. La dirección ingresada será el punto de recogida por defecto para los paseadores.</p>
        </div>
        
        <!-- Mensaje de Éxito de Laravel -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 rounded-xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <form class="space-y-6" action="{{ route('perfil.actualizar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Avatar / Foto de Perfil -->
            <div class="flex flex-col items-center sm:flex-row gap-6 p-5 bg-slate-50 rounded-2xl border border-slate-100 mb-6">
                <div class="relative group shrink-0">
                    <div class="rounded-full overflow-hidden border-2 border-brand-primary shadow-sm bg-white flex items-center justify-center" style="width: 80px; height: 80px; flex-shrink: 0;">
                        @if(auth()->user()->avatar)
                            <img id="avatar-preview" src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover" alt="Vista previa del avatar">
                        @else
                            <svg id="avatar-placeholder" class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                            </svg>
                            <img id="avatar-preview" src="" class="w-full h-full object-cover hidden" alt="Vista previa del avatar">
                        @endif
                    </div>
                </div>
                <div class="flex-1 text-center sm:text-left min-w-0">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Foto de Perfil</label>
                    <input type="file" name="avatar" id="avatar-input" accept="image/*" class="rounded-xl border @error('avatar') border-red-500 @else border-gray-200 @enderror px-3 py-1.5 text-xs focus:border-brand-primary outline-none bg-white cursor-pointer w-full sm:w-auto mb-1.5" onchange="previewImage(event)">
                    <p class="text-[10px] text-gray-400 leading-relaxed font-semibold">Formatos permitidos: JPG, PNG, GIF. Máximo 2MB.</p>
                    @error('avatar')
                        <span class="text-red-500 text-xs mt-1 font-bold block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Nombres y Apellidos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nombres</label>
                    <input type="text" name="nombres" class="rounded-xl border @error('nombres') border-red-500 @else border-gray-200 @enderror px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white" value="{{ old('nombres', auth()->user()->nombres) }}" required>
                    @error('nombres')
                        <span class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Apellidos</label>
                    <input type="text" name="apellidos" class="rounded-xl border @error('apellidos') border-red-500 @else border-gray-200 @enderror px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white" value="{{ old('apellidos', auth()->user()->apellidos) }}" required>
                    @error('apellidos')
                        <span class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Username y Correo Electrónico -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nombre de Usuario (Único)</label>
                    <input type="text" name="username" class="rounded-xl border @error('username') border-red-500 @else border-gray-200 @enderror px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white" value="{{ old('username', auth()->user()->username) }}" required>
                    @error('username')
                        <span class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Correo Electrónico (No modificable)</label>
                    <input type="email" class="rounded-xl border border-gray-100 px-4 py-3 text-sm bg-gray-50 text-gray-400 cursor-not-allowed outline-none" value="{{ auth()->user()->email }}" disabled>
                </div>
            </div>

            <!-- Teléfono y Dirección -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Teléfono de Emergencia</label>
                    <input type="text" name="telefono" class="rounded-xl border @error('telefono') border-red-500 @else border-gray-200 @enderror px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white" value="{{ old('telefono', auth()->user()->telefono) }}">
                    @error('telefono')
                        <span class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Dirección de Residencia (Cartago)</label>
                    <input type="text" name="direccion" class="rounded-xl border @error('direccion') border-red-500 @else border-gray-200 @enderror px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white" value="{{ old('direccion', auth()->user()->direccion) }}" required>
                    @error('direccion')
                        <span class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Campos condicionales para Paseador -->
            @if(auth()->user()->perfilPaseador)
                <hr class="border-t border-gray-100 my-6">
                <div class="mb-4">
                    <h5 class="text-sm font-black text-brand-dark flex items-center gap-1.5">
                        <span>Información del Perfil de Paseador</span>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-0.5 rounded-full 
                            @if(auth()->user()->perfilPaseador->estado == 'activo') bg-emerald-500/10 text-emerald-600
                            @elseif(auth()->user()->perfilPaseador->estado == 'pendiente') bg-amber-400/10 text-amber-600
                            @else bg-red-500/10 text-red-600
                            @endif">
                            {{ auth()->user()->perfilPaseador->estado }}
                        </span>
                    </h5>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Identificación (Cédula)</label>
                        <input type="text" name="identificacion" class="rounded-xl border @error('identificacion') border-red-500 @else border-gray-200 @enderror px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white" value="{{ old('identificacion', auth()->user()->perfilPaseador->identificacion) }}" placeholder="Ej: 1118000000">
                        @error('identificacion')
                            <span class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Experiencia (Meses)</label>
                        <input type="number" name="experiencia_meses" class="rounded-xl border @error('experiencia_meses') border-red-500 @else border-gray-200 @enderror px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white" value="{{ old('experiencia_meses', auth()->user()->perfilPaseador->experiencia_meses) }}" placeholder="Ej: 12">
                        @error('experiencia_meses')
                            <span class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-col mt-4">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Documento de Soporte (Identificación/Cédula PDF)</label>
                    <input type="file" name="documento_soporte" class="rounded-xl border @error('documento_soporte') border-red-500 @else border-gray-200 @enderror px-4 py-2 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white">
                    <span class="text-[10px] text-gray-400 mt-1 leading-relaxed">Sube tu cédula o carta de experiencia en formato PDF. El Administrador verificará este documento antes de activar tu perfil.</span>
                    @error('documento_soporte')
                        <span class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('dashboard') }}" class="inline-block text-center border border-gray-200 text-brand-dark hover:border-brand-primary hover:text-brand-primary font-bold text-sm px-6 py-3 rounded-xl transition duration-200 no-underline">Cancelar</a>
                <button type="submit" class="bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-sm px-6 py-3 rounded-xl shadow-md shadow-brand-primary/10 hover:shadow-lg hover:shadow-brand-primary/20 hover:-translate-y-0.5 transition duration-200 cursor-pointer">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
<script>
    function previewImage(event) {
        const input = event.target;
        const reader = new FileReader();
        reader.onload = function(){
            const preview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-placeholder');
            
            preview.src = reader.result;
            preview.classList.remove('hidden');
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection