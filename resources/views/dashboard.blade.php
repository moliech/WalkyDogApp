@extends('layouts.app')
@section('title', 'Panel Admin')

@section('content')
<div class="py-4 px-2 mb-4">
    <h2 class="fw-extrabold text-slate mb-1">Panel Administrativo WalkyDog</h2>
    <p class="text-slate-muted font-semibold">Módulo II - Renderización de Entidades Principales (Mock Data)</p>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card card-metric card-metric-primary h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-slate-muted font-bold small text-uppercase mb-2">Paseos Activos</h6>
                    <h2 class="fw-extrabold text-slate m-0">{{ $metricas['paseos_activos'] }}</h2>
                </div>
                <div class="metric-icon-bg bg-light-primary">
                    🦮
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card card-metric card-metric-success h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-slate-muted font-bold small text-uppercase mb-2">Mascotas Totales</h6>
                    <h2 class="fw-extrabold text-slate m-0">{{ $metricas['mascotas_totales'] }}</h2>
                </div>
                <div class="metric-icon-bg bg-light-success">
                    🐶
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card card-metric card-metric-warning h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-slate-muted font-bold small text-uppercase mb-2">Paseadores</h6>
                    <h2 class="fw-extrabold text-slate m-0">{{ $metricas['paseadores_disponibles'] }}</h2>
                </div>
                <div class="metric-icon-bg bg-light-warning">
                    🚶
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card card-metric card-metric-danger h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-slate-muted font-bold small text-uppercase mb-2">Alertas SOS</h6>
                    <h2 class="fw-extrabold text-slate m-0">{{ $metricas['alertas_sos'] }}</h2>
                </div>
                <div class="metric-icon-bg bg-light-danger">
                    🚨
                </div>
            </div>
        </div>
    </div>
</div>
@endsection