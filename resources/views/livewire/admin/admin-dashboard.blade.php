<div>
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard Admin</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
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

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="text-muted fw-normal mt-0 text-truncate" title="Total Presensi Hari Ini">Presensi Hari Ini</h5>
                            <h3 class="my-2 py-1">{{ $todayStats['total_presensi'] ?? 0 }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> {{ $todayStats['hadir'] ?? 0 }}</span>
                                <span class="text-nowrap">Hadir</span>
                            </p>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <div id="today-chart" data-colors="#00acc1"></div>
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
                            <h5 class="text-muted fw-normal mt-0 text-truncate" title="Terlambat">Terlambat</h5>
                            <h3 class="my-2 py-1">{{ $todayStats['terlambat'] ?? 0 }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-warning me-2"><i class="mdi mdi-arrow-down-bold"></i></span>
                                <span class="text-nowrap">Hari Ini</span>
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
                            <h3 class="my-2 py-1">{{ $todayStats['pulang_cepat'] ?? 0 }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-danger me-2"><i class="mdi mdi-arrow-down-bold"></i></span>
                                <span class="text-nowrap">Hari Ini</span>
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
                            <h5 class="text-muted fw-normal mt-0 text-truncate" title="QR Code Aktif">QR Code Aktif</h5>
                            <h3 class="my-2 py-1">{{ $todayStats['active_codes'] ?? 0 }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i></span>
                                <span class="text-nowrap">Tersedia</span>
                            </p>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="mdi mdi-qrcode widget-icon bg-success-lighten text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <button wire:click="bukaSecureCodeModal" class="btn btn-primary me-2">
                                <i class="mdi mdi-plus"></i> Generate Secure Code
                            </button>
                            <button wire:click="cleanupExpiredCodes" class="btn btn-warning me-2">
                                <i class="mdi mdi-delete-sweep"></i> Cleanup Expired
                            </button>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <button wire:click="bukaExportModal" class="btn btn-success">
                                <i class="mdi mdi-download"></i> Export Laporan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secure Codes Management -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Secure Codes Aktif</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Secure Code</th>
                                    <th>Expires At</th>
                                    <th>Device Info</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($secureCodes as $code)
                                    <tr>
                                        <td>{{ $code->user->name }}</td>
                                        <td>
                                            <code class="text-primary">{{ substr($code->secure_code, 0, 8) }}...</code>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $code->expires_at->isPast() ? 'danger' : ($code->expires_at->diffInHours() < 2 ? 'warning' : 'success') }}">
                                                {{ $code->expires_at->format('d/m/Y H:i') }}
                                            </span>
                                        </td>
                                        <td>{{ $code->device_info ?: '-' }}</td>
                                        <td>
                                            @if($code->is_active && !$code->expires_at->isPast())
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Expired</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($code->is_active && !$code->expires_at->isPast())
                                                <button wire:click="deactivateSecureCode({{ $code->id }})" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Yakin ingin menonaktifkan secure code ini?')">
                                                    <i class="mdi mdi-close"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Tidak ada secure code aktif</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $secureCodes->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Presensi Data -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Data Presensi</h4>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" wire:model.live="filterTanggal" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">User</label>
                            <select wire:model.live="filterUser" class="form-select">
                                <option value="">Semua User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select wire:model.live="filterStatus" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="hadir">Hadir</option>
                                <option value="terlambat">Terlambat</option>
                                <option value="pulang_cepat">Pulang Cepat</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cari Nama</label>
                            <input type="text" wire:model.live.debounce.300ms="searchTerm" 
                                   class="form-control" placeholder="Cari nama user...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Status</th>
                                    <th>Lokasi</th>
                                    <th>Valid</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($presensiData as $presensi)
                                    <tr>
                                        <td>{{ $presensi->user->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($presensi->tanggal_presensi)->format('d/m/Y') }}</td>
                                        <td>{{ $presensi->jam_masuk ? \Carbon\Carbon::parse($presensi->jam_masuk)->format('H:i') : '-' }}</td>
                                        <td>{{ $presensi->jam_keluar ? \Carbon\Carbon::parse($presensi->jam_keluar)->format('H:i') : '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $presensi->status_presensi === 'hadir' ? 'success' : ($presensi->status_presensi === 'terlambat' ? 'warning' : 'danger') }}">
                                                {{ ucfirst(str_replace('_', ' ', $presensi->status_presensi)) }}
                                            </span>
                                        </td>
                                        <td>{{ $presensi->lokasi_presensi ?: '-' }}</td>
                                        <td>
                                            @if($presensi->is_valid)
                                                <i class="mdi mdi-check-circle text-success"></i>
                                            @else
                                                <i class="mdi mdi-close-circle text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <button wire:click="lihatDetailPresensi({{ $presensi->id }})" 
                                                    class="btn btn-sm btn-outline-info">
                                                <i class="mdi mdi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Tidak ada data presensi</td>
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

    <!-- Generate Secure Code Modal -->
    @if($showSecureCodeModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Generate Secure Code</h5>
                        <button type="button" class="btn-close" wire:click="tutupSecureCodeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit="generateSecureCode">
                            <div class="mb-3">
                                <label class="form-label">Pilih User</label>
                                <select wire:model="selectedUserId" class="form-select" required>
                                    <option value="">Pilih User...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                                    @endforeach
                                </select>
                                @error('selectedUserId') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Masa Berlaku (Jam)</label>
                                <input type="number" wire:model="expireHours" class="form-control" 
                                       min="1" max="168" required>
                                <small class="text-muted">Maksimal 168 jam (1 minggu)</small>
                                @error('expireHours') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Device Info (Opsional)</label>
                                <input type="text" wire:model="deviceInfo" class="form-control" 
                                       placeholder="Informasi device...">
                                @error('deviceInfo') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="text-end">
                                <button type="button" class="btn btn-secondary me-2" wire:click="tutupSecureCodeModal">Batal</button>
                                <button type="submit" class="btn btn-primary">
                                    <span wire:loading wire:target="generateSecureCode" class="spinner-border spinner-border-sm me-1"></span>
                                    Generate
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

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
                                        <td><strong>Nama:</strong></td>
                                        <td>{{ $selectedPresensi['user_name'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal:</strong></td>
                                        <td>{{ $selectedPresensi['tanggal_presensi'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jam Masuk:</strong></td>
                                        <td>{{ $selectedPresensi['jam_masuk'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jam Keluar:</strong></td>
                                        <td>{{ $selectedPresensi['jam_keluar'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <span class="badge bg-{{ ($selectedPresensi['status_presensi'] ?? '') === 'hadir' ? 'success' : (($selectedPresensi['status_presensi'] ?? '') === 'terlambat' ? 'warning' : 'danger') }}">
                                                {{ ucfirst(str_replace('_', ' ', $selectedPresensi['status_presensi'] ?? '')) }}
                                            </span>
                                        </td>
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
                                        <td><strong>Device:</strong></td>
                                        <td>{{ $selectedPresensi['device_info'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>IP Address:</strong></td>
                                        <td>{{ $selectedPresensi['ip_address'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Keterangan:</strong></td>
                                        <td>{{ $selectedPresensi['keterangan'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Valid:</strong></td>
                                        <td>
                                            @if($selectedPresensi['is_valid'] ?? false)
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

    <!-- Export Modal -->
    @if($showExportModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Export Laporan Presensi</h5>
                        <button type="button" class="btn-close" wire:click="tutupExportModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit="exportLaporan">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" wire:model="exportStartDate" class="form-control" required>
                                @error('exportStartDate') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" wire:model="exportEndDate" class="form-control" required>
                                @error('exportEndDate') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Format Export</label>
                                <select wire:model="exportFormat" class="form-select" required>
                                    <option value="csv">CSV</option>
                                    <option value="excel">Excel</option>
                                    <option value="pdf">PDF</option>
                                </select>
                                @error('exportFormat') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="text-end">
                                <button type="button" class="btn btn-secondary me-2" wire:click="tutupExportModal">Batal</button>
                                <button type="submit" class="btn btn-success">
                                    <span wire:loading wire:target="exportLaporan" class="spinner-border spinner-border-sm me-1"></span>
                                    <i class="mdi mdi-download"></i> Export
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Loading Overlay -->
    <div wire:loading wire:target="generateSecureCode,deactivateSecureCode,cleanupExpiredCodes,exportLaporan" 
         class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
         style="background: rgba(0,0,0,0.5); z-index: 9999;">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>