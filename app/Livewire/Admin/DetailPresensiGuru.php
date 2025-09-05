<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PresensiQr;
use App\Models\Guru;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DetailPresensiGuru extends Component
{
    use WithPagination;
    
    public $guruId;
    public $guru;
    public $tanggalMulai;
    public $tanggalSelesai;
    public $filterJenis = 'semua'; // semua, masuk, pulang
    public $filterStatus = 'semua'; // semua, tepat_waktu, terlambat
    public $searchTanggal = '';
    
    public $statistikDetail = [];
    
    protected $paginationTheme = 'bootstrap';
    
    public function mount($guruId, $tanggalMulai = null, $tanggalSelesai = null)
    {
        $this->guruId = $guruId;
        $this->guru = Guru::with(['user', 'mataPelajaran'])->findOrFail($guruId);
        
        // Set default date range (current month if not provided)
        $this->tanggalMulai = $tanggalMulai ?? date('Y-m-01');
        $this->tanggalSelesai = $tanggalSelesai ?? date('Y-m-t');
        
        $this->calculateStatistikDetail();
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
    
    public function updatedFilterJenis()
    {
        $this->resetPage();
    }
    
    public function updatedFilterStatus()
    {
        $this->resetPage();
    }
    
    public function updatedSearchTanggal()
    {
        $this->resetPage();
    }
    
    public function applyFilter()
    {
        $this->resetPage();
        $this->calculateStatistikDetail();
    }
    
    private function calculateStatistikDetail()
    {
        if (!$this->guru || !$this->guru->user) {
            $this->statistikDetail = [];
            return;
        }
        
        $startDate = Carbon::parse($this->tanggalMulai);
        $endDate = Carbon::parse($this->tanggalSelesai);
        
        // Hitung hari kerja dalam periode
        $hariKerja = $this->getHariKerja($startDate, $endDate);
        
        // Ambil semua presensi dalam periode
        $presensiData = PresensiQr::where('user_id', $this->guru->user->id)
            ->whereBetween('waktu_presensi', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();
        
        // Hitung statistik
        $totalMasuk = $presensiData->where('jenis_presensi', 'masuk')->count();
        $totalPulang = $presensiData->where('jenis_presensi', 'pulang')->count();
        $totalLembur = $presensiData->where('jenis_presensi', 'lembur')->count();
        
        // Hitung keterlambatan (asumsi jam masuk 07:30)
        $terlambat = $presensiData->where('jenis_presensi', 'masuk')
            ->filter(function($item) {
                return Carbon::parse($item->waktu_presensi)->format('H:i') > '07:30';
            })->count();
        
        // Hitung total menit lembur
        $totalMenitLembur = $presensiData->where('jenis_presensi', 'lembur')
            ->sum('menit_lembur');
        
        $tepatWaktu = $totalMasuk - $terlambat;
        $alpha = $hariKerja - $totalMasuk;
        $persentaseKehadiran = $hariKerja > 0 ? round(($totalMasuk / $hariKerja) * 100, 1) : 0;
        
        $this->statistikDetail = [
            'hari_kerja' => $hariKerja,
            'total_masuk' => $totalMasuk,
            'total_pulang' => $totalPulang,
            'total_lembur' => $totalLembur,
            'total_menit_lembur' => $totalMenitLembur,
            'total_jam_lembur' => round($totalMenitLembur / 60, 1),
            'tepat_waktu' => $tepatWaktu,
            'terlambat' => $terlambat,
            'alpha' => $alpha,
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
        $query = PresensiQr::with(['user'])
            ->where('user_id', $this->guru->user->id)
            ->whereBetween('waktu_presensi', [
                Carbon::parse($this->tanggalMulai)->format('Y-m-d'),
                Carbon::parse($this->tanggalSelesai)->format('Y-m-d')
            ]);
        
        // Filter berdasarkan tanggal pencarian
        if ($this->searchTanggal) {
            $query->whereDate('waktu_presensi', $this->searchTanggal);
        }
        
        $allPresensi = $query->orderBy('waktu_presensi', 'desc')->get();
        
        // Group presensi by date
        $groupedPresensi = $allPresensi->groupBy(function($item) {
            return Carbon::parse($item->waktu_presensi)->format('Y-m-d');
        });
        
        // Transform grouped data into combined records
        $presensiData = collect();
        foreach ($groupedPresensi as $tanggal => $presensiHari) {
            $masuk = $presensiHari->where('jenis_presensi', 'masuk')->first();
            $pulang = $presensiHari->where('jenis_presensi', 'pulang')->first();
            $lembur = $presensiHari->where('jenis_presensi', 'lembur')->first();
            
            // Apply filters
            $shouldInclude = true;
            
            if ($this->filterJenis === 'masuk' && !$masuk) {
                $shouldInclude = false;
            } elseif ($this->filterJenis === 'pulang' && !$pulang) {
                $shouldInclude = false;
            } elseif ($this->filterJenis === 'lembur' && !$lembur) {
                $shouldInclude = false;
            }
            
            if ($this->filterStatus === 'tepat_waktu' && $masuk) {
                $waktuMasuk = Carbon::parse($masuk->waktu_presensi);
                if ($waktuMasuk->format('H:i') > '07:30') {
                    $shouldInclude = false;
                }
            } elseif ($this->filterStatus === 'terlambat' && $masuk) {
                $waktuMasuk = Carbon::parse($masuk->waktu_presensi);
                if ($waktuMasuk->format('H:i') <= '07:30') {
                    $shouldInclude = false;
                }
            }
            
            if ($shouldInclude) {
                $presensiData->push((object) [
                    'tanggal' => $tanggal,
                    'masuk' => $masuk,
                    'pulang' => $pulang,
                    'lembur' => $lembur
                ]);
            }
        }
        
        // Manual pagination
        $currentPage = request()->get('page', 1);
        $perPage = 15;
        $total = $presensiData->count();
        $offset = ($currentPage - 1) * $perPage;
        $paginatedData = $presensiData->slice($offset, $perPage)->values();
        
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedData,
            $total,
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );
        
        return view('livewire.admin.detail-presensi-guru', [
            'presensiData' => $paginator
        ])->layout('layouts.app');
    }
}