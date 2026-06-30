<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WalkyDog 🐾 - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-extrabold text-slate" href="{{ route('dashboard') }}">WalkyDog 🐾</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('mascotas.index') }}">Mis Mascotas</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('paseos.monitoreo') }}">Monitoreo</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('paseos.control') }}">Paseador</a></li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <button class="btn btn-wd-primary btn-sm" data-bs-toggle="modal" data-bs-target="#solicitarPaseoModal">
                            🐾 Agendar Paseo
                        </button>
                    </li>
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0 border-start-lg ps-lg-3">
                        <a class="btn btn-wd-outline btn-sm" href="{{ route('perfil.editar') }}">⚙️ Mi Perfil</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    <div class="modal fade" id="solicitarPaseoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-slate">🦮 Agendar Nuevo Paseo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3">
                    <form onsubmit="event.preventDefault(); alert('Orden de paseo registrada como PENDING. Redirigiendo a pasarela para simulación de pago...'); window.location.href='/pagos/simulacion/101';">
                        <div class="mb-3">
                            <label class="form-label font-semibold text-slate small">Selecciona tu mascota</label>
                            <select class="form-select">
                                <option>Toby (Golden Retriever)</option>
                                <option>Luna (Pug)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label font-semibold text-slate small">Paseador de preferencia</label>
                            <select class="form-select">
                                <option>Carlos Mendoza (Disponible - Calificación: 4.9)</option>
                                <option>Laura Restrepo (Disponible - Calificación: 4.8)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label font-semibold text-slate small">Duración estimada del paseo</label>
                            <select class="form-select">
                                <option>1 Hora ($12.000 COP)</option>
                                <option>2 Horas ($24.000 COP)</option>
                                <option>3 Horas ($36.000 COP)</option>
                            </select>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-wd-primary text-white fw-bold">Confirmar y proceder al Pago</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>