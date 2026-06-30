@extends('layouts.app')
@section('title', 'Simulador de Pago')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-12 col-md-5">
        <div class="card border-0 shadow-lg payment-receipt">
            <div class="card-body p-4 text-center">
                <div class="mb-4">
                    <span class="fs-1">💳</span>
                    <h4 class="fw-extrabold text-slate mt-2">Simulador de Pago</h4>
                    <p class="text-slate-muted small font-semibold">Entorno Seguro de Pruebas Académicas</p>
                </div>

                <div class="bg-light p-3 rounded-4 text-start mb-4">
                    <h6 class="fw-extrabold text-slate mb-3 text-center text-uppercase small letter-spacing">Resumen del Servicio</h6>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span class="text-slate-muted font-semibold">Referencia:</span>
                        <span class="text-slate font-bold">#{{ $pagoSimulado['paseo_id'] }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span class="text-slate-muted font-semibold">Mascota:</span>
                        <span class="text-slate font-bold">🐶 {{ $pagoSimulado['mascota'] }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span class="text-slate-muted font-semibold">Duración:</span>
                        <span class="text-slate font-bold">{{ $pagoSimulado['horas'] }} Horas</span>
                    </div>
                    
                    <hr class="my-3 border-light">
                    
                    <div class="d-flex justify-content-between align-items-center fw-bold text-dark">
                        <span class="text-slate font-extrabold">Total a Pagar:</span>
                        <span class="fs-5 text-success font-extrabold">${{ number_format($pagoSimulado['total'], 0, ',', '.') }} COP</span>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-wd-secondary p-3 text-uppercase fw-extrabold rounded-4" onclick="alert('Simulación exitosa: Estado cambiado a APPROVED en base de datos.')">
                        ✅ Autorizar Transacción
                    </button>
                    <button class="btn btn-wd-outline text-danger p-2 small border-0 mt-2" onclick="alert('Simulación rechazada: Estado cambiado a REJECTED.')">
                        Rechazar Pago
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection