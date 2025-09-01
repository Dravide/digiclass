<?php

namespace App\Livewire\TataUsaha;

use App\Models\SecureCode;
use App\Models\PresensiQR;
use App\Services\SecureCodeService;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TataUsahaDashboard extends Component
{
    use WithPagination;

    // Filter properties
    public string $filterBulan = '';
    public string $filterTahun = '';
    
    // Modal properties
    public bool $showDetailModal = false;
    public bool $showStatistikModal = false;
    public bool $showLaporanModal = false;
    
    // Detail modal
    public array $selectedPresensi = [];
    
    // Laporan properties
    public string $laporanStartDate = '';
    public string $laporanEndDate = '';
    public string $laporanType = 'summary';
    
    // Statistics
    public array $todayStats = [];
    public array $monthlyStats = [];
    public array $yearlyStats = [];
    public array $weeklyAttendance = [];
    public array $overtimeStats = [];
    
    protected SecureCodeService $secureCodeService;
    
    public function boot(SecureCodeService $secureCodeService): void
    {
        $this->secureCodeService = $secureCodeService;
    }
    
    public function mount(): void
    {
        $this->filterBulan = now()->format('m');
        $this->filterTahun = now()->format('Y');
        $this->laporanStartDate = now()->startOfMonth()->format('Y-m-d');
        $this->laporanEndDate = now()->format('Y-m-d');
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
            'durasi_kerja' => $this->calculateWorkDuration($todayPresensi),
            'overtime' => $this->calculateOvertime($todayPresensi)
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
            'persentase_kehadiran' => $this->calculateAttendancePercentage($monthlyData->count()),
            'total_jam_kerja' => $this->calculateTotalWorkHours($monthlyData),
            'rata_rata_jam_kerja' => $this->calculateAverageWorkHours($monthlyData)
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
                'jam_keluar' => $presensi?->jam_keluar,
                'overtime' => $this->calculateOvertime($presensi)
            ];
        }
        
        // Overtime statistics
        $overtimeData = PresensiQR::where('user_id', $userId)
                                  ->where('tanggal_presensi', 'like', $thisMonth . '%')
                                  ->whereNotNull('jam_keluar')
                                  ->get();
        
        $totalOvertime = 0;
        $overtimeDays = 0;
        
        foreach ($overtimeData as $presensi) {
            $overtime = $this->calculateOvertimeMinutes($presensi);
            if ($overtime > 0) {
                $totalOvertime += $overtime;
                $overtimeDays++;
            }
        }
        
        $this->overtimeStats = [
            'total_overtime_hours' => round($totalOvertime / 60, 1),
            'overtime_days' => $overtimeDays,
            'average_overtime' => $overtimeDays > 0 ? round(($totalOvertime / $overtimeDays) / 60, 1) : 0
        ];
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
    
    private function calculateOvertime($presensi): ?string
    {
        if (!$presensi || !$presensi->jam_keluar) {
            return null;
        }
        
        $keluar = Carbon::parse($presensi->tanggal_presensi . ' ' . $presensi->jam_keluar);
        $standardEnd = Carbon::parse($presensi->tanggal_presensi . ' 16:00:00'); // Assuming 4 PM is standard end time
        
        if ($keluar->gt($standardEnd)) {
            $overtime = $standardEnd->diff($keluar);
            return $overtime->format('%H:%I');
        }
        
        return null;
    }
    
    private function calculateOvertimeMinutes($presensi): int
    {
        if (!$presensi || !$presensi->jam_keluar) {
            return 0;
        }
        
        $keluar = Carbon::parse($presensi->tanggal_presensi . ' ' . $presensi->jam_keluar);
        $standardEnd = Carbon::parse($presensi->tanggal_presensi . ' 16:00:00');
        
        if ($keluar->gt($standardEnd)) {
            return $keluar->diffInMinutes($standardEnd);
        }
        
        return 0;
    }
    
    private function calculateTotalWorkHours($monthlyData): float
    {
        $totalMinutes = 0;
        
        foreach ($monthlyData as $presensi) {
            if ($presensi->jam_masuk && $presensi->jam_keluar) {
                $masuk = Carbon::parse($presensi->tanggal_presensi . ' ' . $presensi->jam_masuk);
                $keluar = Carbon::parse($presensi->tanggal_presensi . ' ' . $presensi->jam_keluar);
                $totalMinutes += $masuk->diffInMinutes($keluar);
            }
        }
        
        return round($totalMinutes / 60, 1);
    }
    
    private function calculateAverageWorkHours($monthlyData): float
    {
        $workingDays = $monthlyData->whereNotNull('jam_masuk')->whereNotNull('jam_keluar')->count();
        
        if ($workingDays === 0) {
            return 0;
        }
        
        $totalHours = $this->calculateTotalWorkHours($monthlyData);
        return round($totalHours / $workingDays, 1);
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
            'overtime' => $this->calculateOvertime($presensi),
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
    
    public function bukaLaporanModal(): void
    {
        $this->showLaporanModal = true;
    }
    
    public function tutupLaporanModal(): void
    {
        $this->showLaporanModal = false;
    }
    
    public function generateLaporan()
    {
        $this->validate([
            'laporanStartDate' => 'required|date',
            'laporanEndDate' => 'required|date|after_or_equal:laporanStartDate',
            'laporanType' => 'required|in:summary,detail,overtime'
        ]);
        
        try {
            $data = PresensiQR::where('user_id', Auth::user()->id)
                             ->whereBetween('tanggal_presensi', [
                                 $this->laporanStartDate,
                                 $this->laporanEndDate
                             ])
                             ->orderBy('tanggal_presensi', 'desc')
                             ->get();
            
            $filename = 'laporan_presensi_' . Auth::user()->name . '_' . $this->laporanStartDate . '_to_' . $this->laporanEndDate;
            
            return $this->exportLaporanToCsv($data, $filename);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menggenerate laporan: ' . $e->getMessage());
        }
    }
    
    private function exportLaporanToCsv($data, string $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            if ($this->laporanType === 'overtime') {
                fputcsv($file, [
                    'Tanggal',
                    'Jam Masuk',
                    'Jam Keluar',
                    'Durasi Kerja',
                    'Overtime',
                    'Status',
                    'Lokasi'
                ]);
            } else {
                fputcsv($file, [
                    'Tanggal',
                    'Jam Masuk',
                    'Jam Keluar',
                    'Status',
                    'Lokasi',
                    'Keterangan'
                ]);
            }
            
            // Data CSV
            foreach ($data as $presensi) {
                if ($this->laporanType === 'overtime') {
                    fputcsv($file, [
                        $presensi->tanggal_presensi,
                        $presensi->jam_masuk,
                        $presensi->jam_keluar,
                        $this->calculateWorkDuration($presensi),
                        $this->calculateOvertime($presensi),
                        $presensi->status_presensi,
                        $presensi->lokasi_presensi
                    ]);
                } else {
                    fputcsv($file, [
                        $presensi->tanggal_presensi,
                        $presensi->jam_masuk,
                        $presensi->jam_keluar,
                        $presensi->status_presensi,
                        $presensi->lokasi_presensi,
                        $presensi->keterangan
                    ]);
                }
            }
            
            fclose($file);
        };
        
        return \Illuminate\Support\Facades\Response::stream($callback, 200, $headers);
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
        
        return view('livewire.tata-usaha.tata-usaha-dashboard', [
            'presensiData' => $presensiData,
            'activeSecureCode' => $activeSecureCode
        ])->layout('layouts.app');
    }
}