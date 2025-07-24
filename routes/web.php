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
use App\Livewire\StatistikManagement;
use App\Livewire\JadwalManagement;
use App\Livewire\Auth\Login;
use App\Livewire\AnnouncementPage;
use App\Http\Controllers\ExportController;
use App\Livewire\MainPage;
use App\Livewire\PresensiPage;
use App\Livewire\RekapPresensi;
use App\Livewire\TugasManagement;
use App\Livewire\NilaiManagement;
use App\Livewire\RekapNilai;
use App\Livewire\JurnalMengajarManagement;
use Illuminate\Support\Facades\Auth;

// Public pages (accessible to everyone)
Route::get('/main', MainPage::class)->name('main-page');
Route::get('/pengumuman', AnnouncementPage::class)->name('announcement'); // Keep for backward compatibility
Route::get('/cek-data-siswa', \App\Livewire\StudentCheckPage::class)->name('student-check');

// Public Export routes
Route::get('/download', \App\Livewire\DownloadPage::class)->name('download');
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
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    
    Route::get('/class-management', ClassManagement::class)->name('class-management');
    Route::get('/kelas-management', KelasManagement::class)->name('kelas-management');
    Route::get('/guru-management', GuruManagement::class)->name('guru-management');
    Route::get('/perpustakaan-management', PerpustakaanManagement::class)->name('perpustakaan-management');
    Route::get('/tahun-pelajaran-management', TahunPelajaranManagement::class)->name('tahun-pelajaran-management');
    Route::get('/mata-pelajaran-management', MataPelajaranManagement::class)->name('mata-pelajaran-management');
    Route::get('/import-management', ImportManagement::class)->name('import-management');
    Route::get('/jadwal-management', JadwalManagement::class)->name('jadwal-management');
    Route::get('/statistik-management', StatistikManagement::class)->name('statistik-management');
    Route::get('/rekap-presensi', RekapPresensi::class)->name('rekap-presensi');
    Route::get('/tugas-management', TugasManagement::class)->name('tugas-management');
    Route::get('/nilai-management', NilaiManagement::class)->name('nilai-management');
    Route::get('/rekap-nilai', RekapNilai::class)->name('rekap-nilai');
    Route::get('/jurnal-mengajar', JurnalMengajarManagement::class)->name('jurnal-mengajar');
    
    // Export routes
    Route::get('/export/daftar-hadir/{kelasId}', [ExportController::class, 'exportDaftarHadir'])->name('export.daftar-hadir');
    Route::get('/export/daftar-nilai/{kelasId}', [ExportController::class, 'exportDaftarNilai'])->name('export.daftar-nilai');
});

// Admin only routes
Route::middleware(['admin'])->group(function () {
    Route::get('/presensi', PresensiPage::class)->name('presensi');
});

// Redirect root to main page
Route::get('/', function () {
    return redirect()->route('main-page');
});
