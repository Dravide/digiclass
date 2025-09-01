<?php

namespace App\Livewire\Admin;

use App\Models\SecureCode;
use App\Models\PresensiQR;
use App\Models\User;

use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class AdminDashboard extends Component
{
    use WithPagination;

    // Filter properties
    public string $filterTanggal = '';
    public string $filterUser = '';
    public string $filterStatus = '';
    public string $searchTerm = '';
    
    // Modal properties
    public bool $showSecureCodeModal = false;
    public bool $showDetailModal = false;
    public bool $showExportModal = false;
    
    // Secure code management
    public int $selectedUserId = 0;
    public int $expireHours = 24;
    public string $deviceInfo = '';
    
    // Detail modal
    public array $selectedPresensi = [];
    
    // Export properties
    public string $exportStartDate = '';
    public string $exportEndDate = '';
    public string $exportFormat = 'excel';
    
    // Statistics
    public array $todayStats = [];
    public array $weeklyStats = [];
    public array $monthlyStats = [];
    

    
    public function mount(): void
    {
        $this->filterTanggal = now()->format('Y-m-d');
        $this->exportStartDate = now()->startOfMonth()->format('Y-m-d');
        $this->exportEndDate = now()->format('Y-m-d');
        $this->loadStatistics();
    }
    
    public function loadStatistics(): void
    {
        // Today's statistics
        $today = now()->format('Y-m-d');
        $this->todayStats = [
            'total_presensi' => PresensiQR::whereDate('tanggal_presensi', $today)->count(),
            'hadir' => PresensiQR::whereDate('tanggal_presensi', $today)
                                ->where('status_presensi', 'hadir')
                                ->count(),
            'terlambat' => PresensiQR::whereDate('tanggal_presensi', $today)
                                    ->where('status_presensi', 'terlambat')
                                    ->count(),
            'pulang_cepat' => PresensiQR::whereDate('tanggal_presensi', $today)
                                       ->where('status_presensi', 'pulang_cepat')
                                       ->count(),
            'active_codes' => SecureCode::where('is_active', true)
                                       ->where('expires_at', '>', now())
                                       ->count()
        ];
        
        // Weekly statistics
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();
        $this->weeklyStats = PresensiQR::whereBetween('tanggal_presensi', [$weekStart, $weekEnd])
                                      ->select('status_presensi', DB::raw('count(*) as total'))
                                      ->groupBy('status_presensi')
                                      ->pluck('total', 'status_presensi')
                                      ->toArray();
        
        // Monthly statistics
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $this->monthlyStats = PresensiQR::whereBetween('tanggal_presensi', [$monthStart, $monthEnd])
                                       ->select(
                                           DB::raw('DATE(tanggal_presensi) as tanggal'),
                                           DB::raw('count(*) as total')
                                       )
                                       ->groupBy('tanggal')
                                       ->orderBy('tanggal')
                                       ->get()
                                       ->toArray();
    }
    
    public function bukaSecureCodeModal(): void
    {
        $this->showSecureCodeModal = true;
        $this->selectedUserId = 0;
        $this->expireHours = 24;
        $this->deviceInfo = '';
    }
    
    public function tutupSecureCodeModal(): void
    {
        $this->showSecureCodeModal = false;
        $this->reset(['selectedUserId', 'expireHours', 'deviceInfo']);
    }
    
    public function generateSecureCode(): void
    {
        $this->validate([
            'selectedUserId' => 'required|exists:users,id',
            'expireHours' => 'required|integer|min:1|max:168', // Max 1 week
            'deviceInfo' => 'nullable|string|max:255'
        ]);
        
        try {
            $user = User::findOrFail($this->selectedUserId);
            
            // Hapus secure code lama untuk user ini
            SecureCode::where('user_id', $user->id)->delete();
            
            // Buat secure code baru
            $secureCode = SecureCode::createForUser($user->id);
            
            session()->flash('message', "Secure code berhasil dibuat untuk {$user->name}: {$secureCode->secure_code}");
            $this->tutupSecureCodeModal();
            $this->loadStatistics();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat secure code: ' . $e->getMessage());
        }
    }
    
    public function deactivateSecureCode(int $secureCodeId): void
    {
        try {
            $secureCode = SecureCode::findOrFail($secureCodeId);
            $secureCode->delete();
            
            session()->flash('message', 'Secure code berhasil dihapus.');
            $this->loadStatistics();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus secure code: ' . $e->getMessage());
        }
    }
    
    public function lihatDetailPresensi(int $presensiId): void
    {
        $presensi = PresensiQR::with(['user', 'secureCode'])
                              ->findOrFail($presensiId);
        
        $this->selectedPresensi = [
            'id' => $presensi->id,
            'user_name' => $presensi->user->name,
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
            'created_at' => $presensi->created_at->format('d/m/Y H:i:s')
        ];
        
        $this->showDetailModal = true;
    }
    
    public function tutupDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedPresensi = [];
    }
    
    public function bukaExportModal(): void
    {
        $this->showExportModal = true;
    }
    
    public function tutupExportModal(): void
    {
        $this->showExportModal = false;
    }
    
    public function exportLaporan()
    {
        $this->validate([
            'exportStartDate' => 'required|date',
            'exportEndDate' => 'required|date|after_or_equal:exportStartDate',
            'exportFormat' => 'required|in:excel,csv,pdf'
        ]);
        
        try {
            $data = PresensiQR::with(['user', 'secureCode'])
                             ->whereBetween('tanggal_presensi', [
                                 $this->exportStartDate,
                                 $this->exportEndDate
                             ])
                             ->orderBy('tanggal_presensi', 'desc')
                             ->orderBy('jam_masuk', 'desc')
                             ->get();
            
            $filename = 'laporan_presensi_' . $this->exportStartDate . '_to_' . $this->exportEndDate;
            
            if ($this->exportFormat === 'csv') {
                return $this->exportToCsv($data, $filename);
            } elseif ($this->exportFormat === 'excel') {
                return $this->exportToExcel($data, $filename);
            } else {
                return $this->exportToPdf($data, $filename);
            }
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengekspor laporan: ' . $e->getMessage());
        }
    }
    
    private function exportToCsv($data, string $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'Nama',
                'Tanggal',
                'Jam Masuk',
                'Jam Keluar',
                'Status',
                'Lokasi',
                'Device Info',
                'IP Address',
                'Keterangan',
                'Valid'
            ]);
            
            // Data CSV
            foreach ($data as $presensi) {
                fputcsv($file, [
                    $presensi->user->name,
                    $presensi->tanggal_presensi,
                    $presensi->jam_masuk,
                    $presensi->jam_keluar,
                    $presensi->status_presensi,
                    $presensi->lokasi_presensi,
                    $presensi->device_info,
                    $presensi->ip_address,
                    $presensi->keterangan,
                    $presensi->is_valid ? 'Ya' : 'Tidak'
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
    
    private function exportToExcel($data, string $filename)
    {
        // Simplified Excel export - in real implementation, use PhpSpreadsheet
        return $this->exportToCsv($data, $filename);
    }
    
    private function exportToPdf($data, string $filename)
    {
        // Simplified PDF export - in real implementation, use DomPDF or similar
        return $this->exportToCsv($data, $filename);
    }
    
    public function cleanupExpiredCodes(): void
    {
        try {
            // Hapus semua secure code (karena tidak ada kolom expired)
            $cleaned = SecureCode::count();
            SecureCode::truncate();
            session()->flash('message', "Berhasil membersihkan {$cleaned} secure code.");
            $this->loadStatistics();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membersihkan secure code: ' . $e->getMessage());
        }
    }
    
    public function updatedFilterTanggal(): void
    {
        $this->resetPage();
    }
    
    public function updatedFilterUser(): void
    {
        $this->resetPage();
    }
    
    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }
    
    public function updatedSearchTerm(): void
    {
        $this->resetPage();
    }
    
    public function render(): View
    {
        // Get all secure codes
        $secureCodes = SecureCode::with('user')
                                ->orderBy('created_at', 'desc')
                                ->paginate(10, ['*'], 'secure-codes-page');
        
        // Get presensi data with filters
        $presensiQuery = PresensiQR::with(['user', 'secureCode'])
                                   ->when($this->filterTanggal, function($query) {
                                       return $query->whereDate('tanggal_presensi', $this->filterTanggal);
                                   })
                                   ->when($this->filterUser, function($query) {
                                       return $query->where('user_id', $this->filterUser);
                                   })
                                   ->when($this->filterStatus, function($query) {
                                       return $query->where('status_presensi', $this->filterStatus);
                                   })
                                   ->when($this->searchTerm, function($query) {
                                       return $query->whereHas('user', function($q) {
                                           $q->where('name', 'like', '%' . $this->searchTerm . '%');
                                       });
                                   })
                                   ->orderBy('tanggal_presensi', 'desc')
                                   ->orderBy('jam_masuk', 'desc');
        
        $presensiData = $presensiQuery->paginate(15, ['*'], 'presensi-page');
        
        // Get users for dropdown
        $users = User::whereIn('role', ['guru', 'tata_usaha'])
                    ->orderBy('name')
                    ->get();
        
        return view('livewire.admin.admin-dashboard', [
            'secureCodes' => $secureCodes,
            'presensiData' => $presensiData,
            'users' => $users
        ])->layout('layouts.app');
    }
}