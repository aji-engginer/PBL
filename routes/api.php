<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\DataUserController;
use App\Http\Controllers\Api\DesaController;
use App\Http\Controllers\Api\KecamatanController;
use App\Http\Controllers\Api\PengajuanController;
use App\Http\Controllers\Api\PondokController;
use App\Http\Controllers\Api\ProgramController;
use Illuminate\Support\Facades\Route;

// ================================================
// Route Publik (Bisa diakses tanpa login)
// ================================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('kecamatan', KecamatanController::class);
Route::apiResource('program', ProgramController::class);

// ================================================
// Route Terproteksi — Semua Role (Admin & Super Admin)
// ================================================
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Master data wilayah
    Route::apiResource('desa', DesaController::class);
    Route::apiResource('pondok', PondokController::class);
    Route::apiResource('data-user', DataUserController::class);

    // Pengajuan — role check dilakukan di dalam controller
    Route::apiResource('pengajuan', PengajuanController::class);
    Route::patch('pengajuan/{id}/validasi', [PengajuanController::class, 'validate']);

    // ================================================
    // Route Khusus Super Admin
    // ================================================
    Route::middleware('role.superadmin')->group(function () {

        // Manajemen Admin Kecamatan
        Route::apiResource('admin', AdminController::class);
        Route::patch('admin/{id}/reset-password', [AdminController::class, 'resetPassword']);
    });
});