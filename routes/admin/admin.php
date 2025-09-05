<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Admin\ClassManagement;
use App\Livewire\Admin\InactiveSiswaManagement;
use App\Livewire\Admin\KelasManagement;
use App\Livewire\Admin\GuruManagement;
use App\Livewire\Admin\TataUsahaManagement;
use App\Livewire\Admin\PerpustakaanManagement;
use App\Livewire\Admin\TahunPelajaranManagement;
use App\Livewire\Admin\MataPelajaranManagement;
use App\Livewire\Admin\ImportManagement;
use App\Livewire\Admin\StatistikManagement;
use App\Livewire\Admin\JadwalManagement;
use App\Livewire\Admin\RekapPresensi;
use App\Livewire\Admin\RekapPresensiGuru;
use App\Livewire\Admin\RekapPresensiTataUsaha;
use App\Livewire\Admin\DetailPresensiGuru;
use App\Livewire\Admin\DetailPresensiTataUsaha;
use App\Livewire\Admin\TugasManagement;
use App\Livewire\Admin\NilaiManagement;
use App\Livewire\Admin\RekapNilai;

use App\Livewire\Admin\PelanggaranManagement;
use App\Livewire\Admin\KategoriPelanggaranManagement;
use App\Livewire\Admin\JenisPelanggaranManagement;
use App\Livewire\Admin\SanksiPelanggaranManagement;
use App\Livewire\Admin\NotifikasiSanksiSiswa;
use App\Livewire\Admin\SuratManagement;
use App\Livewire\Admin\SuratSignature;
use App\Livewire\Admin\RolePermissionManagement;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\CurhatSiswaManagement;
use App\Livewire\Admin\MenuManagement;
use App\Livewire\Admin\PaktaIntegritasManagement;
use App\Livewire\Admin\MagicLinkManagement;
use App\Livewire\Admin\LibraryDashboard;
use App\Livewire\Admin\LibraryBookManagement;
use App\Livewire\Admin\LibraryBorrowingManagement;
use App\Livewire\Admin\LibraryAttendanceManagement;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\PengaturanJamPresensi;
use App\Livewire\Admin\HariLibur;
use App\Livewire\TataUsaha\TataUsahaDashboard;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PelanggaranController;

// Admin Management Routes
Route::middleware(['auth.custom', 'permission:manage-users'])->group(function () {
    Route::get('/admin-dashboard', AdminDashboard::class)->name('admin.dashboard');
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
    Route::get('/menu-management', MenuManagement::class)->name('menu-management');
    Route::get('/pakta-integritas-management', PaktaIntegritasManagement::class)->name('pakta-integritas-management');
    Route::get('/magic-link-management', MagicLinkManagement::class)->name('magic-link-management');
    Route::get('/rekap-presensi', RekapPresensi::class)->name('rekap-presensi');
    Route::get('/rekap-presensi-guru', RekapPresensiGuru::class)->name('rekap-presensi-guru');
    Route::get('/rekap-presensi-tata-usaha', RekapPresensiTataUsaha::class)->name('rekap-presensi-tata-usaha');
    Route::get('/detail-presensi-guru/{guruId}/{tanggalMulai?}/{tanggalSelesai?}', DetailPresensiGuru::class)->name('admin.detail-presensi-guru');
    Route::get('/detail-presensi-tata-usaha/{tataUsahaId}/{tanggalMulai?}/{tanggalSelesai?}', DetailPresensiTataUsaha::class)->name('admin.detail-presensi-tata-usaha');
    Route::get('/pengaturan-jam-presensi', PengaturanJamPresensi::class)->name('pengaturan-jam-presensi');
    Route::get('/hari-libur-management', HariLibur::class)->name('hari-libur-management');
    Route::get('/tugas-management', TugasManagement::class)->name('tugas-management');
    Route::get('/nilai-management', NilaiManagement::class)->name('nilai-management');
    Route::get('/rekap-nilai', RekapNilai::class)->name('rekap-nilai');
    

    
    Route::get('/surat-management', SuratManagement::class)->name('surat-management');
    Route::get('/surat-signature/{suratId}', SuratSignature::class)->name('surat-signature');
    Route::get('/curhat-siswa-management', CurhatSiswaManagement::class)->name('curhat-siswa-management');
    
    // Export routes
    Route::get('/export/daftar-hadir/{kelasId}', [ExportController::class, 'exportDaftarHadir'])->name('export.daftar-hadir');
    Route::get('/export/daftar-nilai/{kelasId}', [ExportController::class, 'exportDaftarNilai'])->name('export.daftar-nilai');
    
});

// Tata Usaha Management Route
Route::middleware(['auth.custom', 'permission:manage-tata-usaha'])->group(function () {
    Route::get('/tata-usaha-management', TataUsahaManagement::class)->name('tata-usaha-management');
});

// Library Management Routes
Route::middleware(['auth.custom', 'permission:manage-library-books,manage-library-borrowing,manage-library-staff,view-library-reports,manage-library-attendance'])->group(function () {
    Route::get('/library/dashboard', LibraryDashboard::class)->name('admin.library.dashboard');
    Route::get('/library/books', LibraryBookManagement::class)->name('admin.library.books');
    Route::get('/library/borrowings', LibraryBorrowingManagement::class)->name('admin.library.borrowings');
    Route::get('/library/attendance', LibraryAttendanceManagement::class)->name('admin.library.attendance');
    Route::get('/library/reports', LibraryDashboard::class)->name('admin.library.reports'); // Using dashboard for now
});

// Pelanggaran Management Routes (accessible by BK and Admin)
Route::middleware(['auth.custom', 'permission:manage-pelanggaran'])->group(function () {
    Route::get('/pelanggaran-management', PelanggaranManagement::class)->name('pelanggaran-management');
    Route::get('/kategori-pelanggaran-management', KategoriPelanggaranManagement::class)->name('kategori-pelanggaran-management');
    Route::get('/jenis-pelanggaran-management', JenisPelanggaranManagement::class)->name('jenis-pelanggaran-management');
    Route::get('/sanksi-pelanggaran-management', SanksiPelanggaranManagement::class)->name('sanksi-pelanggaran-management');
    Route::get('/notifikasi-sanksi-siswa', NotifikasiSanksiSiswa::class)->name('notifikasi-sanksi-siswa');
    
    // Pelanggaran Siswa routes
    Route::resource('pelanggaran', PelanggaranController::class);
    Route::get('/pelanggaran-dashboard', [PelanggaranController::class, 'dashboard'])->name('pelanggaran.dashboard');
    Route::get('/pelanggaran-laporan', [PelanggaranController::class, 'laporan'])->name('pelanggaran.laporan');
    Route::get('/pelanggaran-detail-siswa/{siswa}', [PelanggaranController::class, 'detailSiswa'])->name('pelanggaran.detail-siswa');
    Route::get('/api/jenis-pelanggaran-by-kategori', [PelanggaranController::class, 'getJenisPelanggaranByKategori'])->name('api.jenis-pelanggaran-by-kategori');
    Route::get('/pelanggaran-export', [PelanggaranController::class, 'export'])->name('pelanggaran.export');
});