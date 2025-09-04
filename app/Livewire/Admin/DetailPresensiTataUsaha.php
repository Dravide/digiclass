<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PresensiQr;
use App\Models\TataUsaha;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class DetailPresensiTataUsaha extends Component
{
    use WithPagination;
    
    public $tataUsahaId;
    public $tanggalMulai;
    public $tanggalSelesai;
    public $jenisPresensi = 'semua'; // semua, masuk, pulang
    public $statusKeterlambatan = 'semua'; // semua, tepat_waktu, terlambat
    public $searchTanggal = '';
    
    public $tataUsaha;
    public $statistikDetail = [];
    
    protected $paginationTheme = 'bootstrap';
    
    public function mount($tataUsahaId, $tanggalMulai = null, $tanggalSelesai = null)
    {
        $this->tataUsahaId = $tataUsahaId;
        $this->tanggalMulai = $tanggalMulai ?? date('Y-m-01');
        $this->tanggalSelesai = $tanggalSelesai ?? date('Y-m-t');
        
        // Load data tata usaha
        $this->tataUsaha = TataUsaha::with('user')->findOrFail($this->tataUsahaId);
        
        $this->calculateStatistikDetail();
    }
    
    public function updatedJenisPresensi()
    {
        $this->resetPage();
        $this->calculateStatistikDetail();
    }
    
    public function updatedStatusKeterlambatan()
    {
        $this->resetPage();
        $this->calculateStatistikDetail();
    }
    
    public function updatedSearchTanggal()
    {
        $this->resetPage();
    }
    
    public function updatedTanggalMulai()
    {
        $this->resetPage();
        $this->calculateStatistikDetail();
    }
    
    public function updatedTanggalSelesai()
    {
        $this->resetPage();
        $this->calculateStatistikDetail();
    }
    
    public function applyDateFilter()
    {
        $this->resetPage();
        $this->calculateStatistikDetail();
    }
    
    private function calculateStatistikDetail()
    {
        $startDate = Carbon::parse($this->tanggalMulai);
        $endDate = Carbon::parse($this->tanggalSelesai);
        
        // Ambil semua data presensi dalam rentang tanggal
        $presensiData = PresensiQr::where('user_id', $this->tataUsaha->user->id)
            ->whereBetween('waktu_presensi', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();
        
        // Hitung hari kerja
        $hariKerja = $this->getHariKerja($startDate, $endDate);
        
        // Hitung statistik
        $totalMasuk = $presensiData->where('jenis_presensi', 'masuk')->count();
        $totalPulang = $presensiData->where('jenis_presensi', 'pulang')->count();
        
        // Hitung keterlambatan (jam masuk 07:30)
        $totalTerlambat = $presensiData->where('jenis_presensi', 'masuk')
            ->filter(function($item) {
                return Carbon::parse($item->waktu_presensi)->format('H:i') > '07:30';
            })->count();
        
        $totalAlpha = $hariKerja - $totalMasuk;
        $persentaseKehadiran = $hariKerja > 0 ? round(($totalMasuk / $hariKerja) * 100, 1) : 0;
        
        $this->statistikDetail = [
            'total_hari_kerja' => $hariKerja,
            'total_masuk' => $totalMasuk,
            'total_pulang' => $totalPulang,
            'total_terlambat' => $totalTerlambat,
            'total_tepat_waktu' => $totalMasuk - $totalTerlambat,
            'total_alpha' => $totalAlpha,
            'persentase_kehadiran' => $persentaseKehadiran
        ];
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
    
    public function render()
    {
        $startDate = Carbon::parse($this->tanggalMulai);
        $endDate = Carbon::parse($this->tanggalSelesai);
        
        // Ambil semua data presensi
        $allPresensiData = PresensiQr::where('user_id', $this->tataUsaha->user->id)
            ->whereBetween('waktu_presensi', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('waktu_presensi', 'asc')
            ->get();
        
        // Group by date dan gabungkan masuk/pulang
        $groupedData = [];
        $dates = [];
        
        // Buat array tanggal dalam rentang
        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            if ($current->dayOfWeek >= 1 && $current->dayOfWeek <= 5) { // Senin-Jumat
                $dates[] = $current->format('Y-m-d');
            }
            $current->addDay();
        }
        
        // Group presensi by date
        foreach ($dates as $date) {
            $dayPresensi = $allPresensiData->filter(function($item) use ($date) {
                return Carbon::parse($item->waktu_presensi)->format('Y-m-d') === $date;
            });
            
            $masuk = $dayPresensi->where('jenis_presensi', 'masuk')->first();
            $pulang = $dayPresensi->where('jenis_presensi', 'pulang')->first();
            
            $combinedData = (object) [
                'tanggal' => $date,
                'masuk' => $masuk,
                'pulang' => $pulang
            ];
            
            $groupedData[] = $combinedData;
        }
        
        // Apply filters
        $filteredData = collect($groupedData);
        
        // Filter by jenis presensi
        if ($this->jenisPresensi === 'masuk') {
            $filteredData = $filteredData->filter(function($item) {
                return $item->masuk !== null;
            });
        } elseif ($this->jenisPresensi === 'pulang') {
            $filteredData = $filteredData->filter(function($item) {
                return $item->pulang !== null;
            });
        }
        
        // Filter by status keterlambatan
        if ($this->statusKeterlambatan === 'tepat_waktu') {
            $filteredData = $filteredData->filter(function($item) {
                if (!$item->masuk) return false;
                return Carbon::parse($item->masuk->waktu_presensi)->format('H:i') <= '07:30';
            });
        } elseif ($this->statusKeterlambatan === 'terlambat') {
            $filteredData = $filteredData->filter(function($item) {
                if (!$item->masuk) return false;
                return Carbon::parse($item->masuk->waktu_presensi)->format('H:i') > '07:30';
            });
        }
        
        // Filter by search tanggal
        if ($this->searchTanggal) {
            $filteredData = $filteredData->filter(function($item) {
                return strpos($item->tanggal, $this->searchTanggal) !== false;
            });
        }
        
        // Manual pagination
        $perPage = 10;
        $currentPage = Paginator::resolveCurrentPage();
        $currentItems = $filteredData->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $presensiData = new LengthAwarePaginator(
            $currentItems,
            $filteredData->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page'
            ]
        );
        
        return view('livewire.admin.detail-presensi-tata-usaha', [
            'presensiData' => $presensiData
        ])->layout('layouts.app');
    }
}