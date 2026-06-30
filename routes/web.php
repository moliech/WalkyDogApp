<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\PaseoController;

// Mapeo directo a Controladores (Módulo II)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/mascotas', [MascotaController::class, 'index'])->name('mascotas.index');
Route::get('/paseos/monitoreo', [PaseoController::class, 'monitoreo'])->name('paseos.monitoreo');
Route::get('/paseos/control', [PaseoController::class, 'control'])->name('paseos.control');
Route::get('/perfil/editar', [App\Http\Controllers\DashboardController::class, 'editarPerfil'])->name('perfil.editar');
Route::get('/pagos/simulacion/{paseo_id}', [App\Http\Controllers\PaseoController::class, 'simularPago'])->name('pagos.simulacion');