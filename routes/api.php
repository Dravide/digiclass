<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QrPresensiApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// QR Presensi API routes (public access for standalone app)
Route::middleware(['cors'])->prefix('qr-presensi')->group(function () {
    // Process QR code attendance
    Route::post('/process', [QrPresensiApiController::class, 'processQrCode'])->name('api.qr-presensi.process');
    
    // Get today's attendance list
    Route::get('/today', [QrPresensiApiController::class, 'getTodayAttendance'])->name('api.qr-presensi.today');
    
    // Auto-detect attendance type based on current time
    Route::get('/auto-detect', [QrPresensiApiController::class, 'autoDetectAttendanceType'])->name('api.qr-presensi.auto-detect');
    
    // Get user info by QR code
    Route::post('/user-info', [QrPresensiApiController::class, 'getUserByQrCode'])->name('api.qr-presensi.user-info');
});