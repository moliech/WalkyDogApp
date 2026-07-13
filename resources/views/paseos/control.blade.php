@extends('layouts.app')
@section('title', 'Panel del Paseador')

@section('content')
<div class="max-w-2xl mx-auto py-4">
    <div class="mb-6 text-center">
        <span class="text-4xl mb-2 inline-block">📱</span>
        <h4 class="text-2xl font-black text-brand-dark mt-1">Panel del Paseador</h4>
        <p class="text-sm text-gray-400 font-semibold mt-1">Gestiona tus paseos asignados y reporta novedades en tiempo real</p>
    </div>

    <!-- Selector de Paseos Asignados si hay varios -->
    @if($paseosAsignados->count() > 1)
        <div class="flex flex-wrap gap-2 mb-6 justify-center">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider w-full text-center mb-1">Paseos en Agenda (Selecciona uno para gestionar)</span>
            @foreach($paseosAsignados as $pa)
                <button onclick="switchWalkerTab('{{ $pa->id }}')" 
                        id="btn-walker-tab-{{ $pa->id }}"
                        class="btn-walker-tab px-4 py-2.5 rounded-xl text-sm font-extrabold transition duration-200 flex items-center gap-2 cursor-pointer
                               {{ $loop->first ? 'bg-brand-primary text-white shadow-md shadow-brand-primary/20' : 'bg-white border border-gray-100 text-gray-500 hover:text-brand-primary hover:bg-brand-primary/5' }}">
                    🦮 Orden #{{ $pa->id }} ({{ $pa->mascota->nombre }})
                </button>
            @endforeach
        </div>
    @endif

    <div class="space-y-6">
        @forelse($paseosAsignados as $paseo)
            <div id="walker-card-{{ $paseo->id }}" class="walker-card-content {{ $loop->first ? '' : 'hidden' }}">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100 mb-4">
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Servicio Asignado</span>
                            <h5 class="text-lg font-black text-brand-dark mb-0">Orden #{{ $paseo->id }}</h5>
                        </div>
                        <div>
                            @if($paseo->estado == 'programado')
                                <span class="text-[10px] font-extrabold uppercase tracking-widest bg-brand-primary/10 text-brand-primary px-3 py-1 rounded-full inline-block">Programado</span>
                            @else
                                <span class="text-[10px] font-extrabold uppercase tracking-widest bg-emerald-500/10 text-emerald-600 px-3 py-1 rounded-full inline-block animate-pulse">En Curso</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 text-sm">
                        <div class="flex justify-between sm:justify-start sm:gap-6 items-center">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mascota:</span>
                            <span class="font-extrabold text-brand-dark">🐶 {{ $paseo->mascota->nombre }} ({{ $paseo->mascota->raza }})</span>
                        </div>
                        <div class="flex justify-between sm:justify-start sm:gap-6 items-center">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Propietario:</span>
                            <span class="font-extrabold text-brand-dark">{{ $paseo->mascota->propietario->nombres }} {{ $paseo->mascota->propietario->apellidos }}</span>
                        </div>
                        <div class="flex justify-between sm:justify-start sm:gap-6 items-center">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Contacto:</span>
                            <span class="font-extrabold text-brand-dark">📞 {{ $paseo->mascota->propietario->telefono ?? 'Sin teléfono' }}</span>
                        </div>
                        <div class="flex justify-between sm:justify-start sm:gap-6 items-center">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Dirección:</span>
                            <span class="font-extrabold text-brand-dark">📍 {{ $paseo->mascota->propietario->direccion }}</span>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        @if($paseo->estado == 'programado')
                            <form method="POST" action="{{ route('paseos.iniciar', $paseo->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-sm py-3.5 px-6 rounded-2xl shadow-md hover:shadow-lg transition duration-200 cursor-pointer">
                                    📷 Escanear QR e Iniciar Paseo
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('paseos.finalizar', $paseo->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-brand-accent-red hover:bg-red-600 text-white font-extrabold text-sm py-3.5 px-6 rounded-2xl shadow-md hover:shadow-lg transition duration-200 cursor-pointer">
                                    🛑 Finalizar Recorrido
                                </button>
                            </form>
                            <button class="flex-1 bg-white hover:bg-gray-50 border border-gray-200 text-brand-dark font-extrabold text-sm py-3.5 px-6 rounded-2xl transition duration-200 cursor-pointer" data-bs-toggle="modal" data-bs-target="#novedadModal{{ $paseo->id }}">
                                🚨 Reportar Novedad
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Modal de Novedad para este Paseo -->
                @if($paseo->estado == 'en_progreso')
                    <div class="modal fade" id="novedadModal{{ $paseo->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-2xl p-2 bg-white">
                                <div class="modal-header border-0 pb-0 flex justify-between items-center px-6 pt-5">
                                    <h5 class="text-lg font-black text-brand-dark flex items-center gap-2">
                                        <span>🚨 Reportar Novedad (Paseo #{{ $paseo->id }})</span>
                                    </h5>
                                    <button type="button" class="btn-close focus:outline-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body pt-3">
                                    <form method="POST" action="{{ route('novedades.registrar', $paseo->id) }}" class="px-2 pb-4 space-y-4">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Detalle del incidente o novedad</label>
                                            <textarea name="detalle" rows="4" placeholder="Ej: Se detuvo a tomar agua, el perro se encuentra cansado, etc..." required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white"></textarea>
                                        </div>
                                        <div class="pt-2">
                                            <button type="submit" class="w-full bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-sm py-3.5 px-6 rounded-xl shadow-md transition duration-200 cursor-pointer">
                                                Enviar Reporte
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white p-12 text-center rounded-3xl border border-slate-100 shadow-xl">
                <span class="text-4xl">📭</span>
                <h5 class="text-lg font-bold text-brand-dark mt-4">No tienes paseos programados en este momento</h5>
                <p class="text-sm text-gray-400 mt-1">Los paseos asignados por los propietarios aparecerán aquí cuando estén aprobados.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    function switchWalkerTab(paseoId) {
        // Ocultar todos los bloques de tarjetas
        document.querySelectorAll('.walker-card-content').forEach(el => el.classList.add('hidden'));
        
        // Quitar clases activas de todos los botones del selector
        document.querySelectorAll('.btn-walker-tab').forEach(btn => {
            btn.classList.remove('bg-brand-primary', 'text-white', 'shadow-md', 'shadow-brand-primary/20');
            btn.classList.add('bg-white', 'border', 'border-gray-100', 'text-gray-500', 'hover:text-brand-primary', 'hover:bg-brand-primary/5');
        });
        
        // Mostrar la tarjeta del paseo seleccionado
        document.getElementById('walker-card-' + paseoId).classList.remove('hidden');
        
        // Agregar clase activa al botón clickeado
        const activeBtn = document.getElementById('btn-walker-tab-' + paseoId);
        if (activeBtn) {
            activeBtn.classList.remove('bg-white', 'border', 'border-gray-100', 'text-gray-500', 'hover:text-brand-primary', 'hover:bg-brand-primary/5');
            activeBtn.classList.add('bg-brand-primary', 'text-white', 'shadow-md', 'shadow-brand-primary/20');
        }
    }
</script>
@endsection