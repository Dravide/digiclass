<?php

namespace App\Livewire\Guru;

use App\Models\SecureCode;
use App\Models\PresensiQR;
use App\Services\SecureCodeService;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GuruDashboard extends Component
{
    use WithPagination;

    // Filter properties
    public string $filterBulan = '';
    public string $filterTahun = '';
    
    // Modal properties
    public bool $showDetailModal = false;
    public bool $showStatistikModal = false;
    
    // Detail modal
    public array $selectedPresensi = [];
    
    // Statistics
    public array $todayStats = [];
    public array $monthlyStats = [];
    public array $yearlyStats = [];
    public array $weeklyAttendance = [];
    
    protected SecureCodeService $secureCodeService;
    
    public function boot(SecureCodeService $secureCodeService): void
    {
        $this->secureCodeService = $secureCodeService;
    }
    
    public function mount(): void
    {
        $this->filterBulan = now()->format('m');
        $this->filterTahun = now()->format('Y');
        $this->loadStatistics();
    }
    
    public function loadStatistics(): void
    {
        $userId = Auth::user()->id;
        $today = now()->format('Y-m-d');
        $thisMonth = now()->format('Y-m');
        $thisYear = now()->format('Y');
        
        // Today's statistics
        $todayPresensi = PresensiQR::where('user_id', $userId)
                                  ->whereDate('tanggal_presensi', $today)
                                  ->first();
        
        $this->todayStats = [
            'sudah_presensi' => $todayPresensi ? true : false,
            'jam_masuk' => $todayPresensi?->jam_masuk,
            'jam_keluar' => $todayPresensi?->jam_keluar,
            'status' => $todayPresensi?->status_presensi,
            'lokasi' => $todayPresensi?->lokasi_presensi,
            'durasi_kerja' => $this->calculateWorkDuration($todayPresensi)
        ];
        
        // Monthly statistics
        $monthlyData = PresensiQR::where('user_id', $userId)
                                 ->where('tanggal_presensi', 'like', $thisMonth . '%')
                                 ->get();
        
        $this->monthlyStats = [
            'total_hari_kerja' => $this->getWorkingDaysInMonth(),
            'total_hadir' => $monthlyData->where('status_presensi', 'hadir')->count(),
            'total_terlambat' => $monthlyData->where('status_presensi', 'terlambat')->count(),
            'total_pulang_cepat' => $monthlyData->where('status_presensi', 'pulang_cepat')->count(),
            'persentase_kehadiran' => $this->calculateAttendancePercentage($monthlyData->count())
        ];
        
        // Yearly statistics
        $yearlyData = PresensiQR::where('user_id', $userId)
                                ->where('tanggal_presensi', 'like', $thisYear . '%')
                                ->selectRaw('MONTH(tanggal_presensi) as bulan, COUNT(*) as total')
                                ->groupBy('bulan')
                                ->pluck('total', 'bulan')
                                ->toArray();
        
        $this->yearlyStats = [];
        for ($i = 1; $i <= 12; $i++) {
            $this->yearlyStats[] = [
                'bulan' => Carbon::create()->month($i)->format('M'),
                'total' => $yearlyData[$i] ?? 0
            ];
        }
        
        // Weekly attendance (last 7 days)
        $this->weeklyAttendance = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $presensi = PresensiQR::where('user_id', $userId)
                                  ->whereDate('tanggal_presensi', $date->format('Y-m-d'))
                                  ->first();
            
            $this->weeklyAttendance[] = [
                'tanggal' => $date->format('d/m'),
                'hari' => $date->format('D'),
                'status' => $presensi?->status_presensi ?? 'tidak_hadir',
                'jam_masuk' => $presensi?->jam_masuk,
                'jam_keluar' => $presensi?->jam_keluar
            ];
        }
    }
    
    private function calculateWorkDuration($presensi): ?string
    {
        if (!$presensi || !$presensi->jam_masuk || !$presensi->jam_keluar) {
            return null;
        }
        
        $masuk = Carbon::parse($presensi->tanggal_presensi . ' ' . $presensi->jam_masuk);
        $keluar = Carbon::parse($presensi->tanggal_presensi . ' ' . $presensi->jam_keluar);
        
        $diff = $masuk->diff($keluar);
        return $diff->format('%H:%I');
    }
    
    private function getWorkingDaysInMonth(): int
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $workingDays = 0;
        
        while ($startOfMonth->lte($endOfMonth)) {
            // Skip weekends (Saturday = 6, Sunday = 0)
            if (!in_array($startOfMonth->dayOfWeek, [0, 6])) {
                $workingDays++;
            }
            $startOfMonth->addDay();
        }
        
        return $workingDays;
    }
    
    private function calculateAttendancePercentage(int $totalHadir): float
    {
        $workingDays = $this->getWorkingDaysInMonth();
        return $workingDays > 0 ? round(($totalHadir / $workingDays) * 100, 1) : 0;
    }
    
    public function lihatDetailPresensi(int $presensiId): void
    {
        $presensi = PresensiQR::with('secureCode')
                              ->where('user_id', Auth::user()->id)
                              ->findOrFail($presensiId);
        
        $this->selectedPresensi = [
            'id' => $presensi->id,
            'tanggal_presensi' => $presensi->tanggal_presensi,
            'jam_masuk' => $presensi->jam_masuk,
            'jam_keluar' => $presensi->jam_keluar,
            'status_presensi' => $presensi->status_presensi,
            'lokasi_presensi' => $presensi->lokasi_presensi,
            'device_info' => $presensi->device_info,
            'ip_address' => $presensi->ip_address,
            'keterangan' => $presensi->keterangan,
            'foto_selfie' => $presensi->foto_selfie,
            'is_valid' => $presensi->is_valid,
            'durasi_kerja' => $this->calculateWorkDuration($presensi),
            'created_at' => $presensi->created_at->format('d/m/Y H:i:s')
        ];
        
        $this->showDetailModal = true;
    }
    
    public function tutupDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedPresensi = [];
    }
    
    public function bukaStatistikModal(): void
    {
        $this->showStatistikModal = true;
    }
    
    public function tutupStatistikModal(): void
    {
        $this->showStatistikModal = false;
    }
    
    public function getActiveSecureCode()
    {
        return SecureCode::where('user_id', Auth::user()->id)->first();
    }
    
    public function generateNewSecureCode(): void
    {
        try {
            // Hapus secure code yang sudah ada
            SecureCode::where('user_id', Auth::user()->id)->delete();
            
            // Generate new code
            $secureCode = SecureCode::createForUser(Auth::user()->id);
            
            session()->flash('message', 'Secure code baru berhasil dibuat: ' . $secureCode->secure_code);
            $this->loadStatistics();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat secure code: ' . $e->getMessage());
        }
    }
    
    public function updatedFilterBulan(): void
    {
        $this->resetPage();
        $this->loadStatistics();
    }
    
    public function updatedFilterTahun(): void
    {
        $this->resetPage();
        $this->loadStatistics();
    }
    
    public function render(): View
    {
        $userId = Auth::user()->id;
        
        // Get presensi data with filters
        $presensiQuery = PresensiQR::where('user_id', $userId)
                                   ->when($this->filterBulan && $this->filterTahun, function($query) {
                                       $dateFilter = $this->filterTahun . '-' . str_pad($this->filterBulan, 2, '0', STR_PAD_LEFT);
                                       return $query->where('tanggal_presensi', 'like', $dateFilter . '%');
                                   })
                                   ->orderBy('tanggal_presensi', 'desc');
        
        $presensiData = $presensiQuery->paginate(15);
        
        // Get active secure code
        $activeSecureCode = $this->getActiveSecureCode();
        
        return view('livewire.guru.guru-dashboard', [
            'presensiData' => $presensiData,
            'activeSecureCode' => $activeSecureCode
        ])->layout('layouts.dashboard');
    }
}