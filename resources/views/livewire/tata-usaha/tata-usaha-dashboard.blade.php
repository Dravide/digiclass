<div>
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard Tata Usaha</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tata Usaha</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Today's Status Card -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="card-title mb-2">Status Presensi Hari Ini</h5>
                            @if($todayStats['sudah_presensi'])
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-clock-in text-success me-2"></i>
                                        <span><strong>Masuk:</strong> {{ $todayStats['jam_masuk'] ?? '-' }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-clock-out text-danger me-2"></i>
                                        <span><strong>Keluar:</strong> {{ $todayStats['jam_keluar'] ?? 'Belum keluar' }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-timer text-info me-2"></i>
                                        <span><strong>Durasi:</strong> {{ $todayStats['durasi_kerja'] ?? '-' }}</span>
                                    </div>
                                    @if($todayStats['overtime'])
                                        <div class="d-flex align-items-center">
                                            <i class="mdi mdi-clock-plus text-warning me-2"></i>
                                            <span><strong>Overtime:</strong> {{ $todayStats['overtime'] }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-{{ $todayStats['status'] === 'hadir' ? 'success' : ($todayStats['status'] === 'terlambat' ? 'warning' : 'danger') }}">
                                        {{ ucfirst(str_replace('_', ' ', $todayStats['status'])) }}
                                    </span>
                                    @if($todayStats['lokasi'])
                                        <small class="text-muted ms-2">📍 {{ $todayStats['lokasi'] }}</small>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="mdi mdi-clock-alert text-warning" style="font-size: 2rem;"></i>
                                    <p class="text-muted mb-0">Anda belum melakukan presensi hari ini</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <div class="d-flex flex-column gap-2">
                                <button type="button" class="btn btn-primary btn-sm" wire:click="generateNewSecureCode">
                                    <i class="mdi mdi-qrcode"></i> Generate QR Code
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm" wire:click="bukaStatistikModal">
                                    <i class="mdi mdi-chart-line"></i> Lihat Statistik
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title">
                                    <i class="mdi mdi-calendar-check text-white font-size-16"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Kehadiran Bulan Ini</h6>
                            <p class="text-muted mb-0">{{ $monthlyStats['total_hadir'] }}/{{ $monthlyStats['total_hari_kerja'] }} hari</p>
                            <small class="text-success">{{ $monthlyStats['persentase_kehadiran'] }}%</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning">
                                <span class="avatar-title">
                                    <i class="mdi mdi-clock-alert text-white font-size-16"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Terlambat</h6>
                            <p class="text-muted mb-0">{{ $monthlyStats['total_terlambat'] }} kali</p>
                            <small class="text-warning">Bulan ini</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info">
                                <span class="avatar-title">
                                    <i class="mdi mdi-timer text-white font-size-16"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Total Jam Kerja</h6>
                            <p class="text-muted mb-0">{{ $monthlyStats['total_jam_kerja'] }} jam</p>
                            <small class="text-info">Rata-rata: {{ $monthlyStats['rata_rata_jam_kerja'] }} jam/hari</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success">
                                <span class="avatar-title">
                                    <i class="mdi mdi-clock-plus text-white font-size-16"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Overtime</h6>
                            <p class="text-muted mb-0">{{ $overtimeStats['total_overtime_hours'] }} jam</p>
                            <small class="text-success">{{ $overtimeStats['overtime_days'] }} hari</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Status -->
    @if($activeSecureCode)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-qrcode me-2"></i>
                        <div class="flex-grow-1">
                            <strong>QR Code Aktif:</strong> Berlaku hingga {{ \Carbon\Carbon::parse($activeSecureCode->expires_at)->format('d/m/Y H:i') }}
                            <br><small class="text-muted">Gunakan QR code ini untuk presensi</small>
                        </div>
                        <div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Weekly Attendance Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Kehadiran 7 Hari Terakhir</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        @foreach($weeklyAttendance as $day)
                            <div class="col">
                                <div class="p-2 border rounded">
                                    <div class="fw-bold">{{ $day['hari'] }}</div>
                                    <div class="small text-muted">{{ $day['tanggal'] }}</div>
                                    <div class="mt-2">
                                        @if($day['status'] === 'hadir')
                                            <i class="mdi mdi-check-circle text-success" style="font-size: 1.5rem;"></i>
                                        @elseif($day['status'] === 'terlambat')
                                            <i class="mdi mdi-clock-alert text-warning" style="font-size: 1.5rem;"></i>
                                        @elseif($day['status'] === 'pulang_cepat')
                                            <i class="mdi mdi-clock-minus text-info" style="font-size: 1.5rem;"></i>
                                        @else
                                            <i class="mdi mdi-close-circle text-danger" style="font-size: 1.5rem;"></i>
                                        @endif
                                    </div>
                                    @if($day['jam_masuk'])
                                        <div class="small mt-1">
                                            <div>{{ $day['jam_masuk'] }}</div>
                                            @if($day['jam_keluar'])
                                                <div>{{ $day['jam_keluar'] }}</div>
                                            @endif
                                            @if($day['overtime'])
                                                <div class="text-warning">+{{ $day['overtime'] }}</div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="header-title mb-0">Riwayat Presensi</h4>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex gap-2">
                                <select wire:model.live="filterBulan" class="form-select form-select-sm">
                                    <option value="">Semua Bulan</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">
                                            {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                                <select wire:model.live="filterTahun" class="form-select form-select-sm">
                                    <option value="">Semua Tahun</option>
                                    @for($year = now()->year; $year >= now()->year - 2; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                                <button type="button" class="btn btn-outline-primary btn-sm" wire:click="bukaLaporanModal">
                                    <i class="mdi mdi-download"></i> Export
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($presensiData->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Keluar</th>
                                        <th>Status</th>
                                        <th>Durasi</th>
                                        <th>Overtime</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($presensiData as $presensi)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ \Carbon\Carbon::parse($presensi->tanggal_presensi)->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($presensi->tanggal_presensi)->format('l') }}</small>
                                            </td>
                                            <td>
                                                @if($presensi->jam_masuk)
                                                    <span class="badge bg-success">{{ $presensi->jam_masuk }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($presensi->jam_keluar)
                                                    <span class="badge bg-danger">{{ $presensi->jam_keluar }}</span>
                                                @else
                                                    <span class="text-muted">Belum keluar</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $presensi->status_presensi === 'hadir' ? 'success' : ($presensi->status_presensi === 'terlambat' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $presensi->status_presensi)) }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $durasi = $this->calculateWorkDuration($presensi);
                                                @endphp
                                                {{ $durasi ?? '-' }}
                                            </td>
                                            <td>
                                                @php
                                                    $overtime = $this->calculateOvertime($presensi);
                                                @endphp
                                                @if($overtime)
                                                    <span class="badge bg-warning">{{ $overtime }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-outline-info btn-sm" 
                                                        wire:click="lihatDetailPresensi({{ $presensi->id }})">
                                                    <i class="mdi mdi-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $presensiData->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="mdi mdi-calendar-remove text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Tidak ada data presensi untuk periode yang dipilih</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    @if($showDetailModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Presensi</h5>
                        <button type="button" class="btn-close" wire:click="tutupDetailModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Tanggal:</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($selectedPresensi['tanggal_presensi'])->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jam Masuk:</strong></td>
                                        <td>{{ $selectedPresensi['jam_masuk'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jam Keluar:</strong></td>
                                        <td>{{ $selectedPresensi['jam_keluar'] ?? 'Belum keluar' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $selectedPresensi['status_presensi'] === 'hadir' ? 'success' : ($selectedPresensi['status_presensi'] === 'terlambat' ? 'warning' : 'danger') }}">
                                                {{ ucfirst(str_replace('_', ' ', $selectedPresensi['status_presensi'])) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Durasi Kerja:</strong></td>
                                        <td>{{ $selectedPresensi['durasi_kerja'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Overtime:</strong></td>
                                        <td>{{ $selectedPresensi['overtime'] ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Lokasi:</strong></td>
                                        <td>{{ $selectedPresensi['lokasi_presensi'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>IP Address:</strong></td>
                                        <td><code>{{ $selectedPresensi['ip_address'] ?? '-' }}</code></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Device Info:</strong></td>
                                        <td><small>{{ $selectedPresensi['device_info'] ?? '-' }}</small></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Valid:</strong></td>
                                        <td>
                                            @if($selectedPresensi['is_valid'])
                                                <span class="badge bg-success">Valid</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Valid</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Waktu Input:</strong></td>
                                        <td><small>{{ $selectedPresensi['created_at'] }}</small></td>
                                    </tr>
                                </table>
                                
                                @if($selectedPresensi['foto_selfie'])
                                    <div class="mt-3">
                                        <strong>Foto Selfie:</strong>
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($selectedPresensi['foto_selfie']) }}" 
                                                 class="img-thumbnail" style="max-width: 200px;" alt="Foto Selfie">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        @if($selectedPresensi['keterangan'])
                            <div class="mt-3">
                                <strong>Keterangan:</strong>
                                <p class="text-muted">{{ $selectedPresensi['keterangan'] }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="tutupDetailModal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Statistics Modal -->
    @if($showStatistikModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Statistik Kehadiran Tahunan</h5>
                        <button type="button" class="btn-close" wire:click="tutupStatistikModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @foreach($yearlyStats as $stat)
                                <div class="col-md-2 col-sm-4 col-6 mb-3">
                                    <div class="card text-center">
                                        <div class="card-body py-3">
                                            <h5 class="card-title mb-1">{{ $stat['total'] }}</h5>
                                            <p class="card-text small text-muted mb-0">{{ $stat['bulan'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Total Overtime</h6>
                                        <h4 class="text-warning">{{ $overtimeStats['total_overtime_hours'] }} jam</h4>
                                        <small class="text-muted">{{ $overtimeStats['overtime_days'] }} hari kerja</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Rata-rata Overtime</h6>
                                        <h4 class="text-info">{{ $overtimeStats['average_overtime'] }} jam</h4>
                                        <small class="text-muted">per hari overtime</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Persentase Kehadiran</h6>
                                        <h4 class="text-success">{{ $monthlyStats['persentase_kehadiran'] }}%</h4>
                                        <small class="text-muted">bulan ini</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="tutupStatistikModal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Export Report Modal -->
    @if($showLaporanModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Export Laporan Presensi</h5>
                        <button type="button" class="btn-close" wire:click="tutupLaporanModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="generateLaporan">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" wire:model="laporanStartDate" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" wire:model="laporanEndDate" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis Laporan</label>
                                <select class="form-select" wire:model="laporanType" required>
                                    <option value="summary">Ringkasan</option>
                                    <option value="detail">Detail</option>
                                    <option value="overtime">Overtime</option>
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-download"></i> Download CSV
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="tutupLaporanModal">Batal</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Loading Overlay -->
    <div wire:loading class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
         style="background: rgba(0,0,0,0.5); z-index: 9999;">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .card-body {
        padding: 1rem 0.75rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .d-flex.gap-3 {
        flex-direction: column;
        gap: 0.75rem !important;
    }
}

@media (max-width: 576px) {
    .col {
        padding: 0.25rem;
    }
    
    .avatar-sm {
        width: 2rem;
        height: 2rem;
    }
    
    .avatar-title {
        font-size: 0.875rem;
    }
}
</style>