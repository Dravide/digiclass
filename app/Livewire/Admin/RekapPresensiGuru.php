<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\PresensiQr;
use App\Models\Guru;
use App\Models\TahunPelajaran;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RekapPresensiGuru extends Component
{
    public $selectedTahunPelajaran = null;
    public $selectedBulan = null;
    public $selectedTahun = null;
    public $tanggalMulai = null;
    public $tanggalSelesai = null;
    public $filterType = 'bulan'; // bulan, rentang
    public $searchGuru = '';
    
    public $rekapData = [];
    public $statistik = [];
    
    public function mount()
    {
        $this->selectedTahun = date('Y');
        $this->selectedBulan = date('m');
        $this->selectedTahunPelajaran = TahunPelajaran::where('is_active', true)->first()?->id;
        $this->tanggalMulai = date('Y-m-01');
        $this->tanggalSelesai = date('Y-m-t');
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
    
    public function updatedSearchGuru()
    {
        $this->loadRekapData();
    }
    
    public function applyFilter()
    {
        $this->loadRekapData();
    }
    
    public function loadRekapData()
    {
        // Tentukan rentang tanggal berdasarkan filter type
        if ($this->filterType === 'bulan') {
            $startDate = Carbon::createFromDate($this->selectedTahun, $this->selectedBulan, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($this->selectedTahun, $this->selectedBulan, 1)->endOfMonth();
        } else {
            $startDate = Carbon::parse($this->tanggalMulai);
            $endDate = Carbon::parse($this->tanggalSelesai);
        }
        
        // Ambil semua guru dengan user dan mata pelajaran
        $guruQuery = Guru::with(['user', 'mataPelajaran'])
            ->whereHas('user', function($q) {
                $q->where('role', 'guru');
            });
            
        if ($this->searchGuru) {
            $guruQuery->where(function($q) {
                $q->where('nama_guru', 'like', '%' . $this->searchGuru . '%')
                  ->orWhere('nip', 'like', '%' . $this->searchGuru . '%')
                  ->orWhere('email', 'like', '%' . $this->searchGuru . '%')
                  ->orWhereHas('user', function($subQ) {
                      $subQ->where('name', 'like', '%' . $this->searchGuru . '%')
                           ->orWhere('username', 'like', '%' . $this->searchGuru . '%');
                  })
                  ->orWhereHas('mataPelajaran', function($subQ) {
                      $subQ->where('nama_mata_pelajaran', 'like', '%' . $this->searchGuru . '%');
                  });
            });
        }
        $guruList = $guruQuery->get();
        
        // Ambil user IDs dari guru
        $userIds = $guruList->pluck('user.id')->filter();
        
        // Query untuk presensi berdasarkan user IDs
        $presensiData = PresensiQr::with(['user'])
            ->whereIn('user_id', $userIds)
            ->whereBetween('waktu_presensi', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();
        
        // Proses data rekap per guru
        $this->rekapData = [];
        foreach ($guruList as $guru) {
            if (!$guru->user) continue;
            
            $presensiGuru = $presensiData->where('user_id', $guru->user->id);
            
            // Hitung hari kerja dalam periode
            $hariKerja = $this->getHariKerja($startDate, $endDate);
            
            // Hitung presensi masuk, pulang, dan lembur
            $masuk = $presensiGuru->where('jenis_presensi', 'masuk')->count();
            $pulang = $presensiGuru->where('jenis_presensi', 'pulang')->count();
            $lembur = $presensiGuru->where('jenis_presensi', 'lembur')->count();
            
            // Hitung keterlambatan (asumsi jam masuk 07:00)
            $terlambat = $presensiGuru->where('jenis_presensi', 'masuk')
                ->filter(function($item) {
                    return Carbon::parse($item->waktu_presensi)->format('H:i') > '07:30';
                })->count();
            
            // Hitung total menit lembur
            $totalMenitLembur = $presensiGuru->where('jenis_presensi', 'lembur')
                ->sum('menit_lembur') ?? 0;
            
            $alpha = $hariKerja - $masuk;
            $persentaseKehadiran = $hariKerja > 0 ? round(($masuk / $hariKerja) * 100, 1) : 0;
            
            $this->rekapData[] = [
                'guru' => $guru,
                'masuk' => $masuk,
                'pulang' => $pulang,
                'lembur' => $lembur,
                'total_menit_lembur' => $totalMenitLembur,
                'terlambat' => $terlambat,
                'alpha' => $alpha,
                'hari_kerja' => $hariKerja,
                'persentase_kehadiran' => $persentaseKehadiran
            ];
        }
        
        // Hitung statistik keseluruhan
        $this->calculateStatistik();
    }
    
    private function getHariKerja($startDate, $endDate)
    {
        $count = 0;
        $current = $startDate->copy();
        
        while ($current->lte($endDate)) {
            // Hitung hari Senin-Jumat (1-5)
            if ($current->dayOfWeek >= 1 && $current->dayOfWeek <= 5) {
                $count++;
            }
            $current->addDay();
        }
        
        return $count;
    }
    
    private function calculateStatistik()
    {
        if (empty($this->rekapData)) {
            $this->statistik = [];
            return;
        }
        
        $totalGuru = count($this->rekapData);
        $totalMasuk = array_sum(array_column($this->rekapData, 'masuk'));
        $totalPulang = array_sum(array_column($this->rekapData, 'pulang'));
        $totalLembur = array_sum(array_column($this->rekapData, 'lembur'));
        $totalMenitLembur = array_sum(array_column($this->rekapData, 'total_menit_lembur'));
        $totalTerlambat = array_sum(array_column($this->rekapData, 'terlambat'));
        $totalAlpha = array_sum(array_column($this->rekapData, 'alpha'));
        $totalHariKerja = array_sum(array_column($this->rekapData, 'hari_kerja'));
        
        $rataRataKehadiran = $totalGuru > 0 ? round(array_sum(array_column($this->rekapData, 'persentase_kehadiran')) / $totalGuru, 1) : 0;
        
        $this->statistik = [
            'total_guru' => $totalGuru,
            'total_masuk' => $totalMasuk,
            'total_pulang' => $totalPulang,
            'total_lembur' => $totalLembur,
            'total_menit_lembur' => $totalMenitLembur,
            'total_jam_lembur' => round($totalMenitLembur / 60, 1),
            'total_terlambat' => $totalTerlambat,
            'total_alpha' => $totalAlpha,
            'total_hari_kerja' => $totalHariKerja,
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
        $tahunPelajaranList = TahunPelajaran::orderBy('tanggal_mulai', 'desc')->get();
        
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
        
        return view('livewire.admin.rekap-presensi-guru', [
            'tahunPelajaranList' => $tahunPelajaranList,
            'bulanList' => $bulanList,
            'tahunList' => $tahunList
        ])->layout('layouts.app');
    }
}