<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Presensi;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RekapPresensi extends Component
{
    public $selectedKelas = null;
    public $selectedTahunPelajaran = null;
    public $selectedMataPelajaran = null;
    public $selectedBulan = null;
    public $selectedTahun = null;
    public $tanggalMulai = null;
    public $tanggalSelesai = null;
    public $filterType = 'bulan'; // bulan, rentang
    
    public $rekapData = [];
    public $statistik = [];
    
    public function mount()
    {
        $this->selectedTahun = date('Y');
        $this->selectedBulan = date('m');
        $this->selectedTahunPelajaran = TahunPelajaran::where('is_active', true)->first()?->id;
        $this->tanggalMulai = date('Y-m-01');
        $this->tanggalSelesai = date('Y-m-t');
    }
    
    public function updatedSelectedKelas()
    {
        $this->loadRekapData();
    }
    
    public function updatedSelectedMataPelajaran()
    {
        $this->loadRekapData();
    }
    
    public function updatedSelectedBulan()
    {
        if ($this->filterType === 'bulan') {
            $this->loadRekapData();
        }
    }
    
    public function updatedSelectedTahun()
    {
        if ($this->filterType === 'bulan') {
            $this->loadRekapData();
        }
    }
    
    public function updatedFilterType()
    {
        $this->loadRekapData();
    }
    
    public function applyFilter()
    {
        $this->loadRekapData();
    }
    
    public function loadRekapData()
    {
        if (!$this->selectedKelas) {
            $this->rekapData = [];
            $this->statistik = [];
            return;
        }
        
        // Tentukan rentang tanggal berdasarkan filter type
        if ($this->filterType === 'bulan') {
            $startDate = Carbon::createFromDate($this->selectedTahun, $this->selectedBulan, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($this->selectedTahun, $this->selectedBulan, 1)->endOfMonth();
        } else {
            $startDate = Carbon::parse($this->tanggalMulai);
            $endDate = Carbon::parse($this->tanggalSelesai);
        }
        
        // Query dasar untuk presensi
        $query = Presensi::with(['siswa', 'jadwal.mataPelajaran'])
            ->whereHas('jadwal', function($q) {
                $q->where('kelas_id', $this->selectedKelas);
                if ($this->selectedMataPelajaran) {
                    $q->where('mata_pelajaran_id', $this->selectedMataPelajaran);
                }
            })
            ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            
        $presensiData = $query->get();
        
        // Ambil semua siswa di kelas
        $siswaList = Siswa::whereHas('kelasSiswa', function($q) {
            $q->where('kelas_id', $this->selectedKelas);
        })->get();
        
        // Proses data rekap per siswa
        $this->rekapData = [];
        foreach ($siswaList as $siswa) {
            $presensiSiswa = $presensiData->where('siswa_id', $siswa->id);
            
            $hadir = $presensiSiswa->where('status', 'hadir')->count();
            $terlambat = $presensiSiswa->where('status', 'terlambat')->count();
            $izin = $presensiSiswa->where('status', 'izin')->count();
            $sakit = $presensiSiswa->where('status', 'sakit')->count();
            $alpha = $presensiSiswa->where('status', 'alpha')->count();
            
            $totalPertemuan = $presensiSiswa->count();
            $persentaseKehadiran = $totalPertemuan > 0 ? round((($hadir + $terlambat) / $totalPertemuan) * 100, 1) : 0;
            
            $this->rekapData[] = [
                'siswa' => $siswa,
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpha' => $alpha,
                'total_pertemuan' => $totalPertemuan,
                'persentase_kehadiran' => $persentaseKehadiran
            ];
        }
        
        // Hitung statistik keseluruhan
        $this->calculateStatistik();
    }
    
    private function calculateStatistik()
    {
        if (empty($this->rekapData)) {
            $this->statistik = [];
            return;
        }
        
        $totalSiswa = count($this->rekapData);
        $totalHadir = array_sum(array_column($this->rekapData, 'hadir'));
        $totalTerlambat = array_sum(array_column($this->rekapData, 'terlambat'));
        $totalIzin = array_sum(array_column($this->rekapData, 'izin'));
        $totalSakit = array_sum(array_column($this->rekapData, 'sakit'));
        $totalAlpha = array_sum(array_column($this->rekapData, 'alpha'));
        $totalPertemuan = array_sum(array_column($this->rekapData, 'total_pertemuan'));
        
        $rataRataKehadiran = $totalSiswa > 0 ? round(array_sum(array_column($this->rekapData, 'persentase_kehadiran')) / $totalSiswa, 1) : 0;
        
        $this->statistik = [
            'total_siswa' => $totalSiswa,
            'total_hadir' => $totalHadir,
            'total_terlambat' => $totalTerlambat,
            'total_izin' => $totalIzin,
            'total_sakit' => $totalSakit,
            'total_alpha' => $totalAlpha,
            'total_pertemuan' => $totalPertemuan,
            'rata_rata_kehadiran' => $rataRataKehadiran
        ];
    }
    
    public function exportExcel()
    {
        // TODO: Implementasi export Excel
        session()->flash('info', 'Fitur export Excel akan segera tersedia.');
    }
    
    public function exportPDF()
    {
        // TODO: Implementasi export PDF
        session()->flash('info', 'Fitur export PDF akan segera tersedia.');
    }
    
    public function render()
    {
        $kelasList = Kelas::where('tahun_pelajaran_id', $this->selectedTahunPelajaran)
            ->orderBy('nama_kelas')
            ->get();
            
        $tahunPelajaranList = TahunPelajaran::orderBy('tanggal_mulai', 'desc')->get();
        $mataPelajaranList = MataPelajaran::orderBy('nama_mapel')->get();
        
        $bulanList = [
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
        
        $tahunList = range(date('Y') - 2, date('Y') + 1);
        
        return view('livewire.admin.rekap-presensi', [
            'kelasList' => $kelasList,
            'tahunPelajaranList' => $tahunPelajaranList,
            'mataPelajaranList' => $mataPelajaranList,
            'bulanList' => $bulanList,
            'tahunList' => $tahunList
        ])->layout('layouts.app');
    }
}