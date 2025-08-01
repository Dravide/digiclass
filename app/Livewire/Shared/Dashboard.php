<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\TahunPelajaran;

class Dashboard extends Component
{
    public function render()
    {
        // Get active academic year
        $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
        
        // Filter statistics by active academic year
        $totalSiswa = Siswa::when($activeTahunPelajaran, function ($query) use ($activeTahunPelajaran) {
            $query->where('tahun_pelajaran_id', $activeTahunPelajaran->id);
        })->count();
        
        $totalKelas = Kelas::when($activeTahunPelajaran, function ($query) use ($activeTahunPelajaran) {
            $query->where('tahun_pelajaran_id', $activeTahunPelajaran->id);
        })->count();
        
        $totalGuru = Guru::count();
        
        $siswaAktifPerpustakaan = Siswa::perpustakaanAktif()
            ->when($activeTahunPelajaran, function ($query) use ($activeTahunPelajaran) {
                $query->where('tahun_pelajaran_id', $activeTahunPelajaran->id);
            })->count();
        
        return view('livewire.shared.dashboard', [
            'totalSiswa' => $totalSiswa,
            'totalKelas' => $totalKelas,
            'totalGuru' => $totalGuru,
            'totalPerpustakaan' => $siswaAktifPerpustakaan,
            'activeTahunPelajaran' => $activeTahunPelajaran
        ])->layout('layouts.app', [
            'title' => 'Dashboard - DigiClass',
            'pageTitle' => 'Dashboard'
        ]);
    }
}
