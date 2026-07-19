<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\PaseoController;
use App\Http\Controllers\OtpVerificationController;

// Agrupamos todas las rutas de negocio bajo el middleware 'auth'
// Si un usuario no está autenticado, será redirigido automáticamente al '/login'
Route::middleware(['auth'])->group(function () {
    
    // El Dashboard será la raíz '/'
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // CRUD completo de Mascotas usando recurso (excluye show que no se usa)
    Route::resource('mascotas', MascotaController::class)->except(['show']);

    // --- INTEGRACIÓN DE API EXTERNA ---
    Route::get('/posts', [App\Http\Controllers\PostController::class, 'index'])->name('posts.index');
    
    // Rutas de paseos y monitoreo
    Route::get('/paseos/monitoreo', [PaseoController::class, 'monitoreo'])->name('paseos.monitoreo');
    Route::post('/paseos/{id}/calificar', [PaseoController::class, 'calificar'])->name('paseos.calificar');
    Route::get('/perfil/editar', [DashboardController::class, 'editarPerfil'])->name('perfil.editar');
    Route::put('/perfil/actualizar', [App\Http\Controllers\ProfileController::class, 'updateCustom'])->name('perfil.actualizar');

    // Agendamiento y pagos
    Route::post('/paseos/agendar', [PaseoController::class, 'agendar'])->name('paseos.agendar');
    Route::get('/pagos/simulacion/{paseo_id}', [PaseoController::class, 'simularPago'])->name('pagos.simulacion');
    Route::post('/pagos/confirmar/{paseo_id}', [PaseoController::class, 'confirmarPago'])->name('pagos.confirmar');
    Route::get('/pagos/historial', [PaseoController::class, 'historialPagos'])->name('pagos.historial');
    Route::get('/paseos/exportar-pdf', [PaseoController::class, 'exportarPdf'])->name('paseos.exportar-pdf');
    Route::get('/paseos/{id}/status', [PaseoController::class, 'getStatus'])->name('paseos.status');
    Route::get('/notificaciones/{id}/ir', [\App\Http\Controllers\NotificationController::class, 'readAndRedirect'])->name('notificaciones.ir');
    Route::post('/notificaciones/marcar-leidas', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notificaciones.marcar-leidas');
    Route::get('/api/notificaciones/unread', [\App\Http\Controllers\NotificationController::class, 'getUnread'])->name('api.notificaciones.unread');
    
    // --- RUTAS OPERATIVAS DEL PASEADOR (Protegidas por rol) ---
    Route::middleware(['verificar.rol:paseador'])->group(function () {
    Route::get('/paseos/control', [PaseoController::class, 'control'])->name('paseos.control');
    Route::post('/paseos/{id}/aceptar', [PaseoController::class, 'aceptarPaseo'])->name('paseos.aceptar');
    Route::post('/paseos/{id}/rechazar', [PaseoController::class, 'rechazarPaseo'])->name('paseos.rechazar');
    Route::post('/paseos/{id}/iniciar', [PaseoController::class, 'iniciarPaseo'])->name('paseos.iniciar');
    Route::post('/paseos/{id}/finalizar', [PaseoController::class, 'finalizarPaseo'])->name('paseos.finalizar');
    Route::post('/paseos/{id}/novedad', [PaseoController::class, 'registrarNovedad'])->name('novedades.registrar');
    });

    // --- PANEL DE AUDITORÍA DEL ADMINISTRADOR (Protegidas por rol) ---
    Route::middleware(['verificar.rol:admin'])->group(function () {
    Route::get('/admin/paseadores', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.paseadores');
    Route::post('/admin/paseadores/{id}/aprobar', [App\Http\Controllers\AdminController::class, 'aprobar'])->name('admin.paseadores.aprobar');
    Route::post('/admin/paseadores/{id}/rechazar', [App\Http\Controllers\AdminController::class, 'rechazar'])->name('admin.paseadores.rechazar');
    Route::get('/admin/usuarios', [App\Http\Controllers\AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::get('/admin/tarifas', [App\Http\Controllers\AdminController::class, 'tarifas'])->name('admin.tarifas');
    Route::post('/admin/tarifas/actualizar', [App\Http\Controllers\AdminController::class, 'actualizarTarifas'])->name('admin.tarifas.actualizar');
    Route::get('/admin/pagos', [App\Http\Controllers\AdminController::class, 'historialPagosGlobal'])->name('admin.pagos.historial');
    Route::get('/admin/pagos/exportar-pdf', [App\Http\Controllers\AdminController::class, 'exportarPdfGlobal'])->name('admin.pagos.exportar-pdf');
    });

});

// Rutas de Verificación de OTP (Seguridad 2FA) - Públicas porque el usuario se desautentica temporalmente
Route::get('/otp-verify', [OtpVerificationController::class, 'show'])->name('otp.verify');
Route::post('/otp-verify', [OtpVerificationController::class, 'verify'])->name('otp.verify.post');
Route::post('/otp-resend', [OtpVerificationController::class, 'resend'])->name('otp.resend');

// Importamos las rutas de registro y login creadas por Breeze
require __DIR__.'/auth.php';