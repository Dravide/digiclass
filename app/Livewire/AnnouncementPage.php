<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunPelajaran;
use App\Models\KelasSiswa;

class AnnouncementPage extends Component
{
    public $nis = '';
    public $studentFound = false;
    public $studentName = '';
    public $className = '';
    public $waliKelasName = '';
    public $whatsappLink = '';
    public $errorMessage = '';
    public $tahunPelajaran = '';
    public $libraryStatus = false;
    public $canAccessClassInfo = false;
    
    // Countdown properties
    public $countdownTargetDate = '2025-07-13 14:00:00';
    public $countdownTitle = 'Countdown Menuju 13 Juli 2025 - 14:00 WIB';

    public function searchStudent()
    {
        $this->resetResults();
        
        if (empty($this->nis)) {
            $this->errorMessage = 'Silakan masukkan NIS terlebih dahulu.';
            return;
        }

        // Get active academic year
        $activeTahunPelajaran = TahunPelajaran::where('is_active', true)->first();
        
        if (!$activeTahunPelajaran) {
            $this->errorMessage = 'Tidak ada tahun pelajaran yang aktif.';
            return;
        }

        // Find student by NIS
        $student = Siswa::where('nis', $this->nis)
                        ->where('tahun_pelajaran_id', $activeTahunPelajaran->id)
                        ->first();

        if (!$student) {
            $this->errorMessage = 'NIS tidak ditemukan untuk tahun pelajaran ' . $activeTahunPelajaran->tahun_pelajaran . '.';
            return;
        }

        // Find student's class through KelasSiswa relationship
        $kelasSiswa = KelasSiswa::where('siswa_id', $student->id)->first();
        
        if (!$kelasSiswa) {
            $this->errorMessage = 'Siswa belum memiliki kelas.';
            return;
        }

        $kelas = Kelas::with('guru')->find($kelasSiswa->kelas_id);
        
        if (!$kelas) {
            $this->errorMessage = 'Data kelas tidak ditemukan.';
            return;
        }

        // Check library status
        $perpustakaan = $student->perpustakaan;
        $this->libraryStatus = $perpustakaan && $perpustakaan->terpenuhi;
        
        // Set the results
        $this->studentFound = true;
        $this->studentName = $student->nama_siswa;
        $this->tahunPelajaran = $activeTahunPelajaran->nama_tahun_pelajaran;

   
        
        // Only show class info if library requirements are met
        if ($this->libraryStatus) {
            $this->canAccessClassInfo = true;
            $this->className = $kelas->nama_kelas;
            $this->waliKelasName = $kelas->guru ? $kelas->guru->nama_guru : 'Belum ditentukan';
            $this->whatsappLink = $kelas->link_wa ?? '';
        } else {
            $this->canAccessClassInfo = false;
            $this->className = '';
            $this->waliKelasName = '';
            $this->whatsappLink = '';
        }
    }

    private function resetResults()
    {
        $this->studentFound = false;
        $this->studentName = '';
        $this->className = '';
        $this->waliKelasName = '';
        $this->whatsappLink = '';
        $this->errorMessage = '';
        $this->tahunPelajaran = '';
        $this->libraryStatus = false;
        $this->canAccessClassInfo = false;
    }
    
    /**
     * Check if the countdown target date has passed
     */
    public function isCountdownExpired()
    {
        $targetDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->countdownTargetDate, 'Asia/Jakarta');
        return now('Asia/Jakarta')->greaterThan($targetDate);
    }
    
    /**
     * Get the countdown target date in JavaScript format
     */
    public function getCountdownTargetDateJs()
    {
        $targetDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->countdownTargetDate, 'Asia/Jakarta');
        return $targetDate->toISOString();
    }

    public function render()
    {
        return view('livewire.announcement-page')
            ->layout('layouts.announcement');
    }
}
