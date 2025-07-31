<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Admin\ClassManagement;
use App\Livewire\Admin\InactiveSiswaManagement;
use App\Livewire\Admin\KelasManagement;
use App\Livewire\Admin\GuruManagement;
use App\Livewire\Admin\PerpustakaanManagement;
use App\Livewire\Admin\TahunPelajaranManagement;
use App\Livewire\Admin\MataPelajaranManagement;
use App\Livewire\Admin\ImportManagement;
use App\Livewire\Admin\StatistikManagement;
use App\Livewire\Admin\JadwalManagement;
use App\Livewire\Admin\RekapPresensi;
use App\Livewire\Admin\TugasManagement;
use App\Livewire\Admin\NilaiManagement;
use App\Livewire\Admin\RekapNilai;

use App\Livewire\Admin\PelanggaranManagement;
use App\Livewire\Admin\SuratManagement;
use App\Livewire\Admin\SuratSignature;
use App\Livewire\Admin\RolePermissionManagement;
use App\Livewire\Admin\UserManagement;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PelanggaranController;

// Admin Management Routes
Route::middleware(['auth.custom', 'permission:manage-users'])->group(function () {
    // Route::get('/user-link-management', UserLinkManagement::class)->name('user-link-management'); // Removed - replaced with automatic account creation
    Route::get('/class-management', ClassManagement::class)->name('class-management');
    Route::get('/inactive-siswa-management', InactiveSiswaManagement::class)->name('inactive-siswa-management');
    Route::get('/kelas-management', KelasManagement::class)->name('kelas-management');
    Route::get('/guru-management', GuruManagement::class)->name('guru-management');
    Route::get('/perpustakaan-management', PerpustakaanManagement::class)->name('perpustakaan-management');
    Route::get('/tahun-pelajaran-management', TahunPelajaranManagement::class)->name('tahun-pelajaran-management');
    Route::get('/mata-pelajaran-management', MataPelajaranManagement::class)->name('mata-pelajaran-management');
    Route::get('/import-management', ImportManagement::class)->name('import-management');
    Route::get('/jadwal-management', JadwalManagement::class)->name('jadwal-management');
    Route::get('/statistik-management', StatistikManagement::class)->name('statistik-management');
    Route::get('/role-permission-management', RolePermissionManagement::class)->name('role-permission-management');
    Route::get('/user-management', UserManagement::class)->name('user-management');
    Route::get('/rekap-presensi', RekapPresensi::class)->name('rekap-presensi');
    Route::get('/tugas-management', TugasManagement::class)->name('tugas-management');
    Route::get('/nilai-management', NilaiManagement::class)->name('nilai-management');
    Route::get('/rekap-nilai', RekapNilai::class)->name('rekap-nilai');
    

    
    // Pelanggaran Management
    Route::get('/pelanggaran-management', PelanggaranManagement::class)->name('pelanggaran-management');
    Route::get('/surat-management', SuratManagement::class)->name('surat-management');
    Route::get('/surat-signature/{suratId}', SuratSignature::class)->name('surat-signature');
    
    // Pelanggaran Siswa routes
    Route::resource('pelanggaran', PelanggaranController::class);
    Route::get('/pelanggaran-dashboard', [PelanggaranController::class, 'dashboard'])->name('pelanggaran.dashboard');
    Route::get('/pelanggaran-laporan', [PelanggaranController::class, 'laporan'])->name('pelanggaran.laporan');
    Route::get('/pelanggaran-detail-siswa/{siswa}', [PelanggaranController::class, 'detailSiswa'])->name('pelanggaran.detail-siswa');
    Route::get('/api/jenis-pelanggaran-by-kategori', [PelanggaranController::class, 'getJenisPelanggaranByKategori'])->name('api.jenis-pelanggaran-by-kategori');
    Route::get('/pelanggaran-export', [PelanggaranController::class, 'export'])->name('pelanggaran.export');
    
    // Export routes
    Route::get('/export/daftar-hadir/{kelasId}', [ExportController::class, 'exportDaftarHadir'])->name('export.daftar-hadir');
    Route::get('/export/daftar-nilai/{kelasId}', [ExportController::class, 'exportDaftarNilai'])->name('export.daftar-nilai');
});