@extends('layouts.app')
@section('title', 'Panel del Paseador')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-12 col-md-5">
        <div class="card border-0 p-3 shadow-lg">
            <div class="card-body text-center">
                <div class="mb-4">
                    <span class="fs-1">📱</span>
                    <h4 class="fw-extrabold text-slate mt-2">Panel del Paseador</h4>
                    <span class="badge badge-custom badge-wd-warning">Modo Activo</span>
                </div>
                
                <div class="bg-light p-4 rounded-4 text-start mb-4">
                    <div class="text-center pb-3 mb-3 border-bottom border-light">
                        <span class="text-slate-muted small font-semibold">Servicio Asignado</span>
                        <h5 class="fw-extrabold text-slate mb-0">Orden #{{ $paseoAsignado['id'] }}</h5>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-slate-muted small font-semibold">Mascota:</span>
                        <span class="text-slate font-bold">🐶 {{ $paseoAsignado['mascota'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-slate-muted small font-semibold">Dueño:</span>
                        <span class="text-slate font-bold">{{ $paseoAsignado['propietario'] }}</span>
                    </div>
                </div>
                
                <div class="d-grid gap-3">
                    <button class="btn btn-wd-primary p-3 rounded-4" onclick="alert('Iniciando rastreo GPS e interactuando con la cámara para validación QR...')">
                        📷 Escanear QR para Iniciar
                    </button>
                    <button class="btn btn-wd-outline p-3 rounded-4" disabled>
                        🛑 Finalizar Servicio
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection