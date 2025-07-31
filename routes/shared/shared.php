<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Shared\Dashboard;
use App\Livewire\Shared\RoleDashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Shared\AnnouncementPage;
use App\Livewire\Shared\MainPage;

use Illuminate\Support\Facades\Auth;

// Public pages (accessible to everyone)
Route::get('/main', MainPage::class)->name('main-page');
Route::get('/pengumuman', AnnouncementPage::class)->name('announcement'); // Keep for backward compatibility
Route::get('/cek-data-siswa', \App\Livewire\Shared\StudentCheckPage::class)->name('student-check');
Route::get('/surat/validate/{id}', \App\Livewire\Shared\SuratValidation::class)->name('surat.validate');



// Public Export routes
Route::get('/download', \App\Livewire\Shared\DownloadPage::class)->name('download');
Route::get('/public-export/daftar-hadir', [App\Http\Controllers\PublicExportController::class, 'exportDaftarHadir'])->name('public-export.daftar-hadir');
Route::get('/public-export/daftar-nilai', [App\Http\Controllers\PublicExportController::class, 'exportDaftarNilai'])->name('public-export.daftar-nilai');
Route::get('/api/kelas', [App\Http\Controllers\PublicExportController::class, 'getKelas'])->name('api.kelas');
Route::get('/api/mata-pelajaran', [App\Http\Controllers\PublicExportController::class, 'getMataPelajaran'])->name('api.mata-pelajaran');

// Guest routes (for non-authenticated users)
Route::middleware('guest.custom')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login')->with('success', 'Berhasil logout.');
})->name('logout');

// Protected routes (for authenticated users)
Route::middleware('auth.custom')->group(function () {
    Route::get('/dashboard', RoleDashboard::class)->name('dashboard');
});

// Redirect root to main page
Route::get('/', function () {
    return redirect()->route('main-page');
});