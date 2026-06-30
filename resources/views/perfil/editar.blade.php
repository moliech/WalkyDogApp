@extends('layouts.app')
@section('title', 'Editar Perfil')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-12 col-md-8">
        <div class="card border-0 p-4 shadow-lg">
            <div class="card-body">
                <div class="mb-4">
                    <h4 class="fw-extrabold text-slate mb-1">⚙️ Configuración del Perfil</h4>
                    <p class="text-slate-muted small">Actualiza tus datos de contacto básicos. La dirección ingresada será el punto de recogida por defecto para los paseadores.</p>
                </div>
                
                <form onsubmit="event.preventDefault(); alert('Estructura de formulario validada. En el Módulo III conectaremos esta petición PUT/PATCH para actualizar la base de datos MySQL.');">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-semibold text-slate small">Nombre Completo</label>
                            <input type="text" class="form-control" value="Jhon Esteban Molina">
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="form-label font-semibold text-slate small">Correo Electrónico</label>
                                <input type="email" class="form-control bg-light" value="esteban.molina@cotecnova.edu.co" disabled style="cursor: not-allowed;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-semibold text-slate small">Teléfono de Emergencia</label>
                            <input type="text" class="form-control" value="3123456789">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-semibold text-slate small">Dirección de Residencia (Cartago)</label>
                            <input type="text" class="form-control" value="Calle 10 # 4-50, Cartago, Valle">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-wd-outline">Cancelar</a>
                        <button type="submit" class="btn btn-wd-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection