<div>
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard Guru - {{ Auth::user()->name }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Guru</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
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
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="card-title text-white mb-2">
                                <i class="mdi mdi-calendar-today me-2"></i>
                                Status Presensi Hari Ini - {{ now()->format('d F Y') }}
                            </h5>
                            @if($todayStats['sudah_presensi'])
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="mb-1">
                                            <i class="mdi mdi-clock-in me-1"></i>
                                            <strong>Jam Masuk:</strong> {{ $todayStats['jam_masuk'] ? \Carbon\Carbon::parse($todayStats['jam_masuk'])->format('H:i') : '-' }}
                                        </p>
                                        <p class="mb-1">
                                            <i class="mdi mdi-clock-out me-1"></i>
                                            <strong>Jam Keluar:</strong> {{ $todayStats['jam_keluar'] ? \Carbon\Carbon::parse($todayStats['jam_keluar'])->format('H:i') : 'Belum keluar' }}
                                        </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="mb-1">
                                            <i class="mdi mdi-map-marker me-1"></i>
                                            <strong>Lokasi:</strong> {{ $todayStats['lokasi'] ?: '-' }}
                                        </p>
                                        <p class="mb-1">
                                            <i class="mdi mdi-timer me-1"></i>
                                            <strong>Durasi Kerja:</strong> {{ $todayStats['durasi_kerja'] ?: 'Belum selesai' }}
                                        </p>
                                    </div>
                                </div>
                                <span class="badge bg-{{ $todayStats['status'] === 'hadir' ? 'success' : ($todayStats['status'] === 'terlambat' ? 'warning' : 'danger') }} fs-6 mt-2">
                                    <i class="mdi mdi-check-circle me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $todayStats['status'])) }}
                                </span>
                            @else
                                <p class="mb-2">
                                    <i class="mdi mdi-alert-circle me-2"></i>
                                    Anda belum melakukan presensi hari ini
                                </p>
                                <span class="badge bg-warning fs-6">
                                    <i class="mdi mdi-clock-alert me-1"></i>
                                    Belum Presensi
                                </span>
                            @endif
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="mt-3 mt-md-0">
                                @if($activeSecureCode)
                                    <div class="mb-2">
                                        <small class="text-white-50">QR Code Aktif hingga:</small><br>
                                        <strong>{{ $activeSecureCode->expires_at->format('d/m/Y H:i') }}</strong>
                                    </div>
                                @endif

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
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="text-muted fw-normal mt-0 text-truncate" title="Kehadiran Bulan Ini">Kehadiran Bulan Ini</h5>
                            <h3 class="my-2 py-1">{{ $monthlyStats['persentase_kehadiran'] }}%</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> {{ $monthlyStats['total_hadir'] }}
                                </span>
                                <span class="text-nowrap">dari {{ $monthlyStats['total_hari_kerja'] }} hari</span>
                            </p>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="mdi mdi-account-check widget-icon bg-success-lighten text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="text-muted fw-normal mt-0 text-truncate" title="Total Terlambat">Total Terlambat</h5>
                            <h3 class="my-2 py-1">{{ $monthlyStats['total_terlambat'] }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-warning me-2">
                                    <i class="mdi mdi-clock-alert"></i>
                                </span>
                                <span class="text-nowrap">Bulan ini</span>
                            </p>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="mdi mdi-clock-alert-outline widget-icon bg-warning-lighten text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="text-muted fw-normal mt-0 text-truncate" title="Pulang Cepat">Pulang Cepat</h5>
                            <h3 class="my-2 py-1">{{ $monthlyStats['total_pulang_cepat'] }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-danger me-2">
                                    <i class="mdi mdi-exit-run"></i>
                                </span>
                                <span class="text-nowrap">Bulan ini</span>
                            </p>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="mdi mdi-exit-run widget-icon bg-danger-lighten text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="text-muted fw-normal mt-0 text-truncate" title="QR Code Status">QR Code Status</h5>
                            <h3 class="my-2 py-1">
                                @if($activeSecureCode)
                                    <span class="text-success">Aktif</span>
                                @else
                                    <span class="text-danger">Tidak Aktif</span>
                                @endif
                            </h3>
                            <p class="mb-0 text-muted">
                                @if($activeSecureCode)
                                    <span class="text-success me-2">
                                        <i class="mdi mdi-check-circle"></i>
                                    </span>
                                    <span class="text-nowrap">Siap digunakan</span>
                                @else
                                    <span class="text-danger me-2">
                                        <i class="mdi mdi-close-circle"></i>
                                    </span>
                                    <span class="text-nowrap">Perlu generate</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="mdi mdi-qrcode widget-icon bg-{{ $activeSecureCode ? 'success' : 'danger' }}-lighten text-{{ $activeSecureCode ? 'success' : 'danger' }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Attendance Chart -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Kehadiran 7 Hari Terakhir</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($weeklyAttendance as $day)
                            <div class="col text-center">
                                <div class="mb-2">
                                    <small class="text-muted">{{ $day['hari'] }}</small><br>
                                    <strong>{{ $day['tanggal'] }}</strong>
                                </div>
                                <div class="mb-2">
                                    @if($day['status'] === 'hadir')
                                        <i class="mdi mdi-check-circle text-success fs-3"></i>
                                    @elseif($day['status'] === 'terlambat')
                                        <i class="mdi mdi-clock-alert text-warning fs-3"></i>
                                    @elseif($day['status'] === 'pulang_cepat')
                                        <i class="mdi mdi-exit-run text-danger fs-3"></i>
                                    @else
                                        <i class="mdi mdi-close-circle text-muted fs-3"></i>
                                    @endif
                                </div>
                                <div>
                                    @if($day['jam_masuk'])
                                        <small class="text-muted d-block">{{ \Carbon\Carbon::parse($day['jam_masuk'])->format('H:i') }}</small>
                                    @endif
                                    @if($day['jam_keluar'])
                                        <small class="text-muted d-block">{{ \Carbon\Carbon::parse($day['jam_keluar'])->format('H:i') }}</small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(!$activeSecureCode)
                            <button wire:click="generateNewSecureCode" class="btn btn-primary">
                                <i class="mdi mdi-qrcode me-2"></i>
                                Generate QR Code Baru
                            </button>
                        @endif
                        

                        
                        <button wire:click="bukaStatistikModal" class="btn btn-outline-info">
                            <i class="mdi mdi-chart-line me-2"></i>
                            Lihat Statistik Detail
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Presensi History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="header-title mb-0">Riwayat Presensi</h4>
                        </div>
                        <div class="col-auto">
                            <div class="row g-2">
                                <div class="col-auto">
                                    <select wire:model.live="filterBulan" class="form-select form-select-sm">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}">{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <select wire:model.live="filterTahun" class="form-select form-select-sm">
                                        @for($year = now()->year; $year >= now()->year - 2; $year--)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Status</th>
                                    <th>Durasi</th>
                                    <th>Lokasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($presensiData as $presensi)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($presensi->tanggal_presensi)->format('d/m/Y') }}</td>
                                        <td>{{ $presensi->jam_masuk ? \Carbon\Carbon::parse($presensi->jam_masuk)->format('H:i') : '-' }}</td>
                                        <td>{{ $presensi->jam_keluar ? \Carbon\Carbon::parse($presensi->jam_keluar)->format('H:i') : '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $presensi->status_presensi === 'hadir' ? 'success' : ($presensi->status_presensi === 'terlambat' ? 'warning' : 'danger') }}">
                                                {{ ucfirst(str_replace('_', ' ', $presensi->status_presensi)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $durasi = null;
                                                if ($presensi->jam_masuk && $presensi->jam_keluar) {
                                                    $masuk = \Carbon\Carbon::parse($presensi->tanggal_presensi . ' ' . $presensi->jam_masuk);
                                                    $keluar = \Carbon\Carbon::parse($presensi->tanggal_presensi . ' ' . $presensi->jam_keluar);
                                                    $durasi = $masuk->diff($keluar)->format('%H:%I');
                                                }
                                            @endphp
                                            {{ $durasi ?: '-' }}
                                        </td>
                                        <td>{{ $presensi->lokasi_presensi ?: '-' }}</td>
                                        <td>
                                            <button wire:click="lihatDetailPresensi({{ $presensi->id }})" 
                                                    class="btn btn-sm btn-outline-info">
                                                <i class="mdi mdi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Tidak ada data presensi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $presensiData->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Presensi Modal -->
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
                                        <td>{{ \Carbon\Carbon::parse($selectedPresensi['tanggal_presensi'])->format('d F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jam Masuk:</strong></td>
                                        <td>{{ $selectedPresensi['jam_masuk'] ? \Carbon\Carbon::parse($selectedPresensi['jam_masuk'])->format('H:i:s') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jam Keluar:</strong></td>
                                        <td>{{ $selectedPresensi['jam_keluar'] ? \Carbon\Carbon::parse($selectedPresensi['jam_keluar'])->format('H:i:s') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Durasi Kerja:</strong></td>
                                        <td>{{ $selectedPresensi['durasi_kerja'] ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $selectedPresensi['status_presensi'] === 'hadir' ? 'success' : ($selectedPresensi['status_presensi'] === 'terlambat' ? 'warning' : 'danger') }}">
                                                {{ ucfirst(str_replace('_', ' ', $selectedPresensi['status_presensi'])) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Lokasi:</strong></td>
                                        <td>{{ $selectedPresensi['lokasi_presensi'] ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Device:</strong></td>
                                        <td>{{ $selectedPresensi['device_info'] ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>IP Address:</strong></td>
                                        <td>{{ $selectedPresensi['ip_address'] ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Keterangan:</strong></td>
                                        <td>{{ $selectedPresensi['keterangan'] ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Valid:</strong></td>
                                        <td>
                                            @if($selectedPresensi['is_valid'])
                                                <span class="badge bg-success">Ya</span>
                                            @else
                                                <span class="badge bg-danger">Tidak</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        @if(!empty($selectedPresensi['foto_selfie']))
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6>Foto Selfie:</h6>
                                    <img src="{{ Storage::url($selectedPresensi['foto_selfie']) }}" 
                                         class="img-fluid rounded" style="max-height: 300px;">
                                </div>
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

    <!-- Statistik Modal -->
    @if($showStatistikModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Statistik Kehadiran Detail</h5>
                        <button type="button" class="btn-close" wire:click="tutupStatistikModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Statistik Bulanan</h6>
                                <canvas id="monthlyChart" width="400" height="200"></canvas>
                            </div>
                            <div class="col-md-6">
                                <h6>Statistik Tahunan</h6>
                                <canvas id="yearlyChart" width="400" height="200"></canvas>
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

    <!-- Loading Overlay -->
    <div wire:loading wire:target="generateNewSecureCode,lihatDetailPresensi" 
         class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
         style="background: rgba(0,0,0,0.5); z-index: 9999;">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Chart.js for statistics (if needed)
    document.addEventListener('livewire:init', () => {
        // Initialize charts when modal is opened
        Livewire.on('statistik-modal-opened', () => {
            // Add chart initialization code here if using Chart.js
        });
    });
</script>
@endpush