<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Shared\Dashboard;
use App\Livewire\Shared\RoleDashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Shared\AnnouncementPage;
use App\Livewire\Shared\MainPage;
use App\Livewire\Shared\PelanggaranReport;
use App\Livewire\HtmlEditor;
use App\Livewire\Shared\QRGenerator;
use App\Livewire\Shared\QRScanner;
use App\Livewire\Shared\SecureCodeGenerator;
use App\Livewire\Shared\QrPresensi;

use Illuminate\Support\Facades\Auth;

// Public pages (accessible to everyone)
Route::get('/main', MainPage::class)->name('main-page');
Route::get('/license-invalid', function () {
    return view('license.invalid');
})->name('license-invalid');
Route::get('/pengumuman', AnnouncementPage::class)->name('announcement'); // Keep for backward compatibility
Route::get('/cek-data-siswa', \App\Livewire\Shared\StudentCheckPage::class)->name('student-check');
Route::get('/surat/validate/{id}', \App\Livewire\Shared\SuratValidation::class)->name('surat.validate');
Route::get('/curhat-siswa-public', \App\Livewire\Shared\CurhatSiswaPublic::class)->name('curhat-siswa-public');
Route::get('/tata-tertib-siswa', \App\Livewire\Shared\TataTertibSiswa::class)->name('tata-tertib-siswa');
Route::get('/magic-link/{token}', \App\Livewire\Shared\MagicLinkPelanggaran::class)->name('magic-link-pelanggaran');

// Magic Link Card PDF route (public access)
Route::get('/generate-magic-link-card-pdf/{siswaId}', [\App\Http\Controllers\Admin\MagicLinkCardController::class, 'generatePDF'])->name('generate-magic-link-card-pdf');

// Public Export routes
Route::get('/download', \App\Livewire\Shared\DownloadPage::class)->name('download');

// Pelanggaran Report route (public with access code)
Route::get('/pelanggaran-report', PelanggaranReport::class)->name('pelanggaran-report');
Route::get('/public-export/daftar-hadir', [App\Http\Controllers\PublicExportController::class, 'exportDaftarHadir'])->name('public-export.daftar-hadir');
Route::get('/public-export/daftar-nilai', [App\Http\Controllers\PublicExportController::class, 'exportDaftarNilai'])->name('public-export.daftar-nilai');
Route::get('/public-export/daftar-hadir-excel', [App\Http\Controllers\PublicExportController::class, 'exportDaftarHadirExcel'])->name('public-export.daftar-hadir-excel');
Route::get('/public-export/daftar-nilai-excel', [App\Http\Controllers\PublicExportController::class, 'exportDaftarNilaiExcel'])->name('public-export.daftar-nilai-excel');
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
    
    // Secure Code Generator (admin only)
    Route::get('/secure-code-generator', SecureCodeGenerator::class)
        ->name('secure-code-generator')
        ->middleware('role:admin');
    
    // Route lain yang memerlukan auth
});

// QR Presensi - Akses tanpa login dengan kode akses
Route::get('/presensi-qr', QrPresensi::class)->name('presensi-qr');



// Redirect root to main page
Route::get('/', function () {
    return redirect()->route('main-page');
});