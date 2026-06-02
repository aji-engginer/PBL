<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DataUserController;
use App\Http\Controllers\Api\DesaController;
use App\Http\Controllers\Api\KecamatanController;
use App\Http\Controllers\Api\PondokController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\PengajuanController; // 1. IMPORT CONTROLLER BARU
use Illuminate\Support\Facades\Route;

// ================================================
// Route Publik (Bisa diakses tanpa login)
// ================================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Kecamatan publik (data referensi wilayah)
Route::apiResource('kecamatan', KecamatanController::class);

// Program publik (data referensi anggaran tahunan)
Route::apiResource('program', ProgramController::class);


// ================================================
// Route Terproteksi (Harus Login Sanctum)
// ================================================
Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('desa', DesaController::class);
    Route::apiResource('pondok', PondokController::class);
    Route::apiResource('data-user', DataUserController::class);

    // 2. RUTE MODUL PENGAJUAN (Hanya untuk User yang Login)
    // Menggunakan .only() karena update data dilarang/diganti lewat mekanisme Validasi
    Route::apiResource('pengajuan', PengajuanController::class)->only(['index', 'store', 'show']);
    
    // 3. RUTE KHUSUS VALIDASI SUPER ADMIN (Menggunakan PATCH untuk mengubah sebagian data status)
    Route::patch('pengajuan/{id}/validate', [PengajuanController::class, 'validateSubmission']);

    Route::post('/logout', [AuthController::class, 'logout']);
});