<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunPelajaran;

class DownloadPage extends Component
{
    public $selectedKelas = '';
    public $selectedMataPelajaran = '';
    public $selectedMonth = '';
    public $selectedYear = '';
    
    public function mount()
    {
        // Set default month and year to current
        $this->selectedMonth = date('m');
        $this->selectedYear = date('Y');
    }
    
    public function downloadDaftarHadir()
    {
        $this->validate([
            'selectedKelas' => 'required',
            'selectedMonth' => 'required',
            'selectedYear' => 'required'
        ]);
        
        return redirect()->route('public-export.daftar-hadir', [
            'kelas_id' => $this->selectedKelas,
            'bulan' => $this->selectedMonth,
            'tahun' => $this->selectedYear
        ]);
    }
    
    public function downloadDaftarNilai()
    {
        $this->validate([
            'selectedKelas' => 'required',
            'selectedMataPelajaran' => 'required'
        ]);
        
        return redirect()->route('public-export.daftar-nilai', [
            'kelas_id' => $this->selectedKelas,
            'mata_pelajaran_id' => $this->selectedMataPelajaran
        ]);
    }
    
    public function render()
    {
        $kelas = Kelas::with(['tahunPelajaran', 'guru'])->get();
        $mataPelajaran = MataPelajaran::active()->get();
        $months = [
            '01' => 'Januari',
            '02' => 'Februari', 
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];
        
        $years = range(date('Y') - 2, date('Y') + 1);
        
        return view('livewire.download-page', [
            'kelas' => $kelas,
            'mataPelajaran' => $mataPelajaran,
            'months' => $months,
            'years' => $years
        ])->layout('layouts.main', ['title' => 'Download Dokumen - DigiClass']);
    }
}