<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ClassManagement;
use App\Livewire\Dashboard;
use App\Livewire\KelasManagement;
use App\Livewire\GuruManagement;
use App\Livewire\PerpustakaanManagement;
use App\Livewire\TahunPelajaranManagement;
use App\Livewire\MataPelajaranManagement;
use App\Livewire\ImportManagement;
use App\Livewire\Auth\Login;
use App\Livewire\AnnouncementPage;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Auth;

// Public announcement page (accessible to everyone)
Route::get('/pengumuman', AnnouncementPage::class)->name('announcement');

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
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    
    Route::get('/class-management', ClassManagement::class)->name('class-management');
    Route::get('/kelas-management', KelasManagement::class)->name('kelas-management');
    Route::get('/guru-management', GuruManagement::class)->name('guru-management');
    Route::get('/perpustakaan-management', PerpustakaanManagement::class)->name('perpustakaan-management');
    Route::get('/tahun-pelajaran-management', TahunPelajaranManagement::class)->name('tahun-pelajaran-management');
    Route::get('/mata-pelajaran-management', MataPelajaranManagement::class)->name('mata-pelajaran-management');
    Route::get('/import-management', ImportManagement::class)->name('import-management');
    
    // Export routes
    Route::get('/export/daftar-hadir/{kelasId}', [ExportController::class, 'exportDaftarHadir'])->name('export.daftar-hadir');
    Route::get('/export/daftar-nilai/{kelasId}', [ExportController::class, 'exportDaftarNilai'])->name('export.daftar-nilai');
});

// Redirect root to dashboard if authenticated, otherwise to announcement page
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('announcement');
});
