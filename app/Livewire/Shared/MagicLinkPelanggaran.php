<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use App\Models\Siswa;
use App\Models\PelanggaranSiswa;
use App\Models\SanksiPelanggaran;
use App\Models\TahunPelajaran;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class MagicLinkPelanggaran extends Component
{
    public $token;
    public $siswa;
    public $pelanggaranList = [];
    public $totalPoin = 0;
    public $isValidToken = false;
    public $errorMessage = '';
    public $tahunPelajaranAktif;
    public $sanksiSiswa = null;
    public $statusPenanganan = 'belum_ditangani';
    public $showSanksiInfo = false;
    
    public function mount($token)
    {
        $this->token = $token;
        $this->validateToken();
        
        if ($this->isValidToken) {
            $this->loadPelanggaranData();
        }
    }
    
    private function validateToken()
    {
        // Get cached magic link data
        $linkData = Cache::get("magic_link_{$this->token}");
        
        if (!$linkData) {
            $this->errorMessage = 'Link tidak valid atau sudah kedaluwarsa.';
            return;
        }
        
        // Check if token has expired
        if (Carbon::now()->isAfter($linkData['expires_at'])) {
            $this->errorMessage = 'Link sudah kedaluwarsa.';
            Cache::forget("magic_link_{$this->token}");
            return;
        }
        
        // Get student data
        $this->siswa = Siswa::find($linkData['siswa_id']);
        
        if (!$this->siswa) {
            $this->errorMessage = 'Data siswa tidak ditemukan.';
            return;
        }
        
        $this->isValidToken = true;
    }
    
    private function loadPelanggaranData()
    {
        $this->tahunPelajaranAktif = TahunPelajaran::where('is_active', true)->first();
        
        if (!$this->tahunPelajaranAktif) {
            $this->errorMessage = 'Tahun pelajaran aktif tidak ditemukan.';
            return;
        }
        
        // Get all violations for this student in the active academic year
        $this->pelanggaranList = PelanggaranSiswa::with([
            'jenisPelanggaran.kategoriPelanggaran',
            'tahunPelajaran'
        ])
        ->where('siswa_id', $this->siswa->id)
        ->where('tahun_pelajaran_id', $this->tahunPelajaranAktif->id)
        ->orderBy('tanggal_pelanggaran', 'desc')
        ->get();
        
        // Calculate total points
        $this->totalPoin = PelanggaranSiswa::getTotalPoinSiswa(
            $this->siswa->id, 
            $this->tahunPelajaranAktif->id
        );
        
        // Load sanksi data if student has violations
        $this->loadSanksiData();
    }
    
    private function loadSanksiData()
    {
        if ($this->totalPoin > 0) {
            $currentKelas = $this->siswa->getCurrentKelas();
            if ($currentKelas) {
                $tingkatPelanggaran = $this->getTingkatPelanggaranByKelas($currentKelas->tingkat);
                $this->sanksiSiswa = SanksiPelanggaran::getSanksiByPoin($tingkatPelanggaran, $this->totalPoin);
                
                // Check status penanganan from latest violation
                $pelanggaranTerbaru = PelanggaranSiswa::where('siswa_id', $this->siswa->id)
                    ->where('tahun_pelajaran_id', $this->tahunPelajaranAktif->id)
                    ->orderBy('tanggal_pelanggaran', 'desc')
                    ->first();
                    
                $this->statusPenanganan = $pelanggaranTerbaru ? $pelanggaranTerbaru->status_penanganan : 'belum_ditangani';
                $this->showSanksiInfo = $this->sanksiSiswa !== null;
            }
        }
    }
    
    /**
     * Konversi tingkat kelas ke tingkat pelanggaran
     * Mapping berdasarkan kebijakan sekolah:
     * - Kelas 7-8: ringan
     * - Kelas 9-10: sedang  
     * - Kelas 11-12: berat
     */
    private function getTingkatPelanggaranByKelas($tingkatKelas)
    {
        if ($tingkatKelas <= 8) {
            return 'ringan';
        } elseif ($tingkatKelas <= 10) {
            return 'sedang';
        } else {
            return 'berat';
        }
    }
    
    public function getStatusBadgeClass($status)
    {
        return match($status) {
            'selesai' => 'bg-success',
            'dalam_proses' => 'bg-warning',
            'belum_ditangani' => 'bg-danger',
            default => 'bg-secondary'
        };
    }
    
    public function getStatusLabel($status)
    {
        return match($status) {
            'selesai' => 'Sudah Ditangani',
            'dalam_proses' => 'Dalam Proses',
            'belum_ditangani' => 'Belum Ditangani',
            default => 'Tidak Diketahui'
        };
    }
    
    public function render()
    {
        return view('livewire.shared.magic-link-pelanggaran')
            ->layout('layouts.main');
    }
}