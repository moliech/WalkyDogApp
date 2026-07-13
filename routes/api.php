<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MascotaController;

// --- RUTAS PÚBLICAS ---
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// --- RUTAS PROTEGIDAS CON JWT ---
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    
    // CRUD completo de Mascotas
    Route::apiResource('mascotas', MascotaController::class);
});