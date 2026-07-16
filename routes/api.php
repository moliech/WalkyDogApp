<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MascotaController;
use App\Http\Controllers\Api\UbicacionController;
use App\Http\Controllers\PaseoController;

// --- RUTAS PÚBLICAS ---
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// --- RUTAS PROTEGIDAS CON JWT Y SESIÓN ---
Route::middleware(['web', 'auth:api,web'])->group(function () {
Route::post('logout', [AuthController::class, 'logout']);
Route::get('me', [AuthController::class, 'me']);

// CRUD completo de Mascotas con nombres de ruta prefijados
Route::apiResource('mascotas', MascotaController::class)->names('api.mascotas');

// --- API DE GEOLOCALIZACIÓN Y SEGUIMIENTO GPS ---
Route::post('paseos/{id}/ubicacion', [UbicacionController::class, 'store']);
Route::get('paseos/{id}/ubicaciones', [UbicacionController::class, 'index']);

Route::post('paseos/{id}/validar-qr', [PaseoController::class, 'validarQr']);
});