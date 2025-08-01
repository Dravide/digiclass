<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Siswa\MyGrades;
use App\Livewire\Siswa\MyAttendance;
use App\Livewire\Siswa\MyAssignments;
use App\Livewire\Siswa\PelaporanPelanggaran;
use App\Livewire\Siswa\CurhatSiswa;

// Siswa Routes
Route::middleware(['auth.custom'])->group(function () {
    Route::get('/my-grades', MyGrades::class)->name('my-grades');
    Route::get('/my-attendance', MyAttendance::class)->name('my-attendance');
    Route::get('/my-assignments', MyAssignments::class)->name('my-assignments');
    Route::get('/pelaporan-pelanggaran', PelaporanPelanggaran::class)->name('pelaporan-pelanggaran');
    Route::get('/curhat-siswa', CurhatSiswa::class)->name('curhat-siswa');
});