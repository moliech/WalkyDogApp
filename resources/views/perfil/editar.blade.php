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

        <!-- Mensajes de Error de Laravel -->
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-600 rounded-xl text-sm font-semibold">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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
            @if(auth()->user()->isPaseador() && auth()->user()->perfilPaseador)
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
                    @if(!empty($usuario['documento_soporte']))
                        <div class="mt-2 flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100/50 w-full sm:w-fit">
                            <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <span class="text-xs font-bold text-gray-500">Documento actual:</span>
                            <a href="{{ Storage::url($usuario['documento_soporte']) }}" target="_blank" class="text-xs font-black text-brand-primary hover:underline flex items-center gap-1">
                                Ver PDF Soporte
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>
                            </a>
                        </div>
                    @endif
                    @error('documento_soporte')
                        <span class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</span>
                    @enderror
                </div>

                @php
                    $ajustes = \App\Models\AjusteTarifa::first();
                    $minCalificacion = $ajustes ? $ajustes->calificacion_minima : 4.5;
                    $maxPorcentaje = $ajustes ? $ajustes->porcentaje_maximo : 20;
                    $calificaRecargo = $usuario['calificacion_promedio'] >= $minCalificacion;
                @endphp

                <div class="mt-6 p-5 rounded-2xl border transition duration-200 @if($calificaRecargo) bg-brand-primary/5 border-brand-primary/10 @else bg-slate-50 border-slate-100 @endif">
                    <h6 class="text-xs font-black text-brand-dark uppercase tracking-wider mb-1">Beneficio de Tarifa Destacada</h6>
                    @if($calificaRecargo)
                        <p class="text-xs text-gray-500 font-semibold mb-4 leading-relaxed">
                            ¡Felicitaciones! Cumples con el puntaje promedio mínimo de **{{ number_format($minCalificacion, 2) }}** (Tu calificación promedio es **{{ number_format($usuario['calificacion_promedio'], 2) }} ★**). Puedes configurar un recargo adicional sobre la tarifa por hora de los paseos.
                        </p>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex-1">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Porcentaje de Recargo Adicional</label>
                                <span class="text-[10px] text-gray-400 font-semibold leading-relaxed block">Puedes definir entre 0% y un máximo de {{ $maxPorcentaje }}%</span>
                            </div>
                            <div class="w-36 flex items-center bg-white border border-gray-200 rounded-xl px-3 py-2 focus-within:border-brand-primary transition">
                                <input type="number" name="porcentaje_recargo" value="{{ old('porcentaje_recargo', $usuario['porcentaje_recargo']) }}" required min="0" max="{{ $maxPorcentaje }}" class="w-full text-right text-sm font-black text-brand-dark outline-none border-0 p-0 focus:ring-0">
                                <span class="text-xs font-extrabold text-gray-400 ml-1.5">%</span>
                            </div>
                        </div>
                        @error('porcentaje_recargo')
                            <span class="text-red-500 text-xs mt-1 font-bold block">{{ $message }}</span>
                        @enderror
                    @else
                        <p class="text-xs text-gray-400 font-semibold leading-relaxed">
                            Para desbloquear el cobro de recargos adicionales, requieres una calificación promedio mínima de **{{ number_format($minCalificacion, 2) }} ★**. Actualmente tu promedio de calificación es de **{{ number_format($usuario['calificacion_promedio'], 2) }} ★**. ¡Sigue brindando un excelente servicio para habilitar este beneficio!
                        </p>
                    @endif
                </div>
            @endif

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('dashboard') }}" class="inline-block text-center border border-gray-200 text-brand-dark hover:border-brand-primary hover:text-brand-primary font-bold text-sm px-6 py-3 rounded-xl transition duration-200 no-underline">Cancelar</a>
                <button type="submit" class="bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-sm px-6 py-3 rounded-xl shadow-md shadow-brand-primary/10 hover:shadow-lg hover:shadow-brand-primary/20 hover:-translate-y-0.5 transition duration-200 cursor-pointer">Guardar Cambios</button>
            </div>
        </form>

        @if(!$usuario['es_paseador'])
            <div class="mt-8 pt-8 border-t border-gray-100">
                @if(empty($usuario['estado_paseador']))
                    <!-- Nunca se ha postulado -->
                    <div>
                        <div id="postulation-banner" class="bg-brand-primary/5 p-6 rounded-2xl border border-brand-primary/10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div class="flex-1">
                                <h5 class="text-sm font-black text-brand-dark mb-1">🐾 ¿Quieres ser parte del equipo de Paseadores?</h5>
                                <p class="text-xs text-gray-500 font-semibold leading-relaxed">Postúlate subiendo tus soportes e identificación. Una vez aprobada tu solicitud por el administrador, podrás empezar a pasear perros y ganar dinero.</p>
                            </div>
                            <button onclick="document.getElementById('postulation-form-container').style.display = 'block'; document.getElementById('postulation-banner').style.display = 'none';" class="bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-xs px-4 py-2.5 rounded-xl shrink-0 transition cursor-pointer border-0">
                                Postularme Ahora
                            </button>
                        </div>

                        <!-- Formulario de Postulación -->
                        <div id="postulation-form-container" style="display: none;" class="mt-6">
                            <form class="space-y-4 p-5 bg-slate-50 rounded-2xl border border-slate-100/50" action="{{ route('perfil.postularse') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <h6 class="text-xs font-black text-brand-dark uppercase tracking-wider mb-2">Formulario de Postulación</h6>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="flex flex-col">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Identificación / Cédula</label>
                                        <input type="text" name="identificacion" required class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition outline-none text-brand-dark bg-white">
                                    </div>
                                    <div class="flex flex-col">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Experiencia (En Meses)</label>
                                        <input type="number" name="experiencia_meses" required min="0" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition outline-none text-brand-dark bg-white">
                                    </div>
                                </div>

                                <div class="flex flex-col mt-3">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Soporte de Identificación o Cédula (PDF)</label>
                                    <input type="file" name="documento_soporte" required class="rounded-xl border border-gray-200 px-4 py-2 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition outline-none text-brand-dark bg-white">
                                    <span class="text-[10px] text-gray-400 mt-1 font-semibold leading-relaxed">Sube tu documento de identidad en formato PDF (Max 2MB). El administrador verificará este documento.</span>
                                </div>

                                <div class="flex justify-end pt-4">
                                    <button type="submit" class="bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-xs px-5 py-2.5 rounded-xl transition cursor-pointer border-0 shadow-sm">
                                        Enviar Postulación
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @elseif($usuario['estado_paseador'] === 'pendiente')
                    <!-- Postulación Pendiente -->
                    <div class="bg-amber-50 p-6 rounded-2xl border border-amber-200 flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        <div>
                            <h5 class="text-sm font-black text-brand-dark mb-1">🔎 Postulación en revisión</h5>
                            <p class="text-xs text-amber-800 font-semibold leading-relaxed">Tu postulación para ser paseador está siendo verificada por el administrador. Te notificaremos una vez sea aprobada o rechazada.</p>
                        </div>
                    </div>
                @elseif($usuario['estado_paseador'] === 'rechazado')
                    <!-- Postulación Rechazada, permite volver a enviar -->
                    <div>
                        <div id="postulation-rejected-banner" class="bg-red-50 p-6 rounded-2xl border border-red-200 flex items-start justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <h5 class="text-sm font-black text-brand-dark mb-1">❌ Postulación Rechazada / Cuenta Desactivada</h5>
                                    <p class="text-xs text-red-800 font-semibold leading-relaxed">Tu postulación anterior fue rechazada o tu cuenta fue desactivada.</p>
                                    @if(!empty($usuario['observacion_rechazo']))
                                        <p class="text-xs text-red-700 font-extrabold mt-1">Motivo: <span class="font-bold text-brand-dark">{{ $usuario['observacion_rechazo'] }}</span></p>
                                    @endif
                                    <p class="text-xs text-gray-500 font-semibold mt-1.5 leading-relaxed">Si deseas volver a postularte con nuevos soportes correctos, puedes enviar una nueva solicitud.</p>
                                </div>
                            </div>
                            <button onclick="document.getElementById('repostulation-form-container').style.display = 'block'; document.getElementById('postulation-rejected-banner').style.display = 'none';" class="bg-red-600 hover:bg-red-700 text-white font-extrabold text-xs px-4 py-2.5 rounded-xl shrink-0 transition cursor-pointer border-0">
                                Volver a Postularme
                            </button>
                        </div>

                        <!-- Formulario de Re-Postulación -->
                        <div id="repostulation-form-container" style="display: none;" class="mt-6">
                            <form class="space-y-4 p-5 bg-slate-50 rounded-2xl border border-slate-100/50" action="{{ route('perfil.postularse') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <h6 class="text-xs font-black text-brand-dark uppercase tracking-wider mb-2">Nueva Solicitud de Postulación</h6>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="flex flex-col">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Identificación / Cédula</label>
                                        <input type="text" name="identificacion" value="{{ old('identificacion', $usuario['identificacion']) }}" required class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition outline-none text-brand-dark bg-white">
                                    </div>
                                    <div class="flex flex-col">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Experiencia (En Meses)</label>
                                        <input type="number" name="experiencia_meses" value="{{ old('experiencia_meses', $usuario['experiencia_meses']) }}" required min="0" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition outline-none text-brand-dark bg-white">
                                    </div>
                                </div>

                                <div class="flex flex-col mt-3">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nuevo Soporte de Cédula (PDF)</label>
                                    <input type="file" name="documento_soporte" required class="rounded-xl border border-gray-200 px-4 py-2 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition outline-none text-brand-dark bg-white">
                                    <span class="text-[10px] text-gray-400 mt-1 font-semibold leading-relaxed">Sube tu cédula o carta de experiencia corregida en formato PDF (Max 2MB).</span>
                                </div>

                                <div class="flex justify-end pt-4">
                                    <button type="submit" class="bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-xs px-5 py-2.5 rounded-xl transition cursor-pointer border-0 shadow-sm">
                                        Enviar Postulación
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        @endif
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