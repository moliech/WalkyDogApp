@extends('layouts.app')
@section('title', 'Mis Mascotas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 py-2">
    <h3 class="fw-extrabold text-slate m-0">Mascotas Asociadas</h3>
    <button class="btn btn-wd-outline btn-sm" disabled>+ Agregar (Módulo III)</button>
</div>

<div class="row">
    @foreach($mascotas as $mascota)
        <div class="col-md-4 mb-4">
            <div class="card border-0 h-100">
                <div class="pet-card-header">
                    @if($mascota['nombre'] == 'Toby')
                        🦮
                    @elseif($mascota['nombre'] == 'Luna')
                        🐶
                    @else
                        🐕
                    @endif
                </div>
                <div class="card-body p-4">
                    <h5 class="fw-extrabold text-slate mb-3">{{ $mascota['nombre'] }}</h5>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-slate-muted small font-semibold">Raza:</span>
                        <span class="text-slate font-bold small">{{ $mascota['raza'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-slate-muted small font-semibold">Tamaño:</span>
                        <span class="badge badge-custom @if($mascota['tamano'] == 'Grande') badge-wd-primary @elseif($mascota['tamano'] == 'Pequeño') badge-wd-success @else badge-wd-warning @endif">
                            {{ $mascota['tamano'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection