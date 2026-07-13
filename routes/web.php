<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\PaseoController;

// Agrupamos todas las rutas de negocio bajo el middleware 'auth'
// Si un usuario no está autenticado, será redirigido automáticamente al '/login'
Route::middleware(['auth'])->group(function () {
    
    // El Dashboard será la raíz '/'
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // CRUD completo de Mascotas usando recurso (excluye show que no se usa)
    Route::resource('mascotas', MascotaController::class)->except(['show']);
    
    // Rutas de paseos y monitoreo
    Route::get('/paseos/monitoreo', [PaseoController::class, 'monitoreo'])->name('paseos.monitoreo');
    Route::get('/paseos/control', [PaseoController::class, 'control'])->name('paseos.control');
    Route::get('/perfil/editar', [DashboardController::class, 'editarPerfil'])->name('perfil.editar');
    
    // Agendamiento y pagos
    Route::post('/paseos/agendar', [PaseoController::class, 'agendar'])->name('paseos.agendar');
    Route::get('/pagos/simulacion/{paseo_id}', [PaseoController::class, 'simularPago'])->name('pagos.simulacion');
    Route::post('/pagos/confirmar/{paseo_id}', [PaseoController::class, 'confirmarPago'])->name('pagos.confirmar');
    Route::get('/pagos/historial', [PaseoController::class, 'historialPagos'])->name('pagos.historial');
    
    // Operaciones del paseador
    Route::post('/paseos/{id}/iniciar', [PaseoController::class, 'iniciarPaseo'])->name('paseos.iniciar');
    Route::post('/paseos/{id}/finalizar', [PaseoController::class, 'finalizarPaseo'])->name('paseos.finalizar');
    Route::post('/paseos/{id}/novedad', [PaseoController::class, 'registrarNovedad'])->name('novedades.registrar');

    // Panel de Auditoría del Administrador
    Route::get('/admin/paseadores', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.paseadores');
    Route::post('/admin/paseadores/{id}/aprobar', [App\Http\Controllers\AdminController::class, 'aprobar'])->name('admin.paseadores.aprobar');
    Route::post('/admin/paseadores/{id}/rechazar', [App\Http\Controllers\AdminController::class, 'rechazar'])->name('admin.paseadores.rechazar');
    Route::get('/admin/usuarios', [App\Http\Controllers\AdminController::class, 'usuarios'])->name('admin.usuarios');
});

// Importamos las rutas de registro y login creadas por Breeze
require __DIR__.'/auth.php';