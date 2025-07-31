<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Guru\MyClasses;
use App\Livewire\Guru\JurnalMengajarManagement;
use App\Livewire\Admin\PresensiPage;

// Guru Routes
Route::middleware(['auth.custom'])->group(function () {
    Route::get('/my-classes', MyClasses::class)->name('my-classes');
    Route::get('/jurnal-mengajar', JurnalMengajarManagement::class)->name('jurnal-mengajar');
});

// Admin only routes (for presensi)
Route::middleware(['admin'])->group(function () {
    Route::get('/presensi', PresensiPage::class)->name('presensi');
});