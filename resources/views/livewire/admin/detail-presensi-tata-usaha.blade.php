<div>
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Detail Presensi Tata Usaha</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('rekap-presensi-tata-usaha') }}">Rekap Presensi Tata Usaha</a></li>
                        <li class="breadcrumb-item active">Detail Presensi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Tata Usaha Info -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Informasi Tata Usaha</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="150"><strong>Nama</strong></td>
                                    <td>: {{ $tataUsaha->user->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Username</strong></td>
                                    <td>: {{ $tataUsaha->user->username ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>NIP</strong></td>
                                    <td>: {{ $tataUsaha->nip ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="150"><strong>Email</strong></td>
                                    <td>: {{ $tataUsaha->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jabatan</strong></td>
                                    <td>: {{ $tataUsaha->jabatan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Periode</strong></td>
                                    <td>: {{ Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($tanggalSelesai)->format('d/m/Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Detail -->
    @if(!empty($statistikDetail))
    <div class="row">
        <div class="col-md-2">
            <div class="card widget-flat">
                <div class="card-body text-center">
                    <div class="float-end">
                        <i class="mdi mdi-calendar-check widget-icon bg-success-lighten text-success"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Hari Kerja</h5>
                    <h3 class="mt-3 mb-3 text-success">{{ $statistikDetail['total_hari_kerja'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card widget-flat">
                <div class="card-body text-center">
                    <div class="float-end">
                        <i class="mdi mdi-login widget-icon bg-primary-lighten text-primary"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Total Masuk</h5>
                    <h3 class="mt-3 mb-3 text-primary">{{ $statistikDetail['total_masuk'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card widget-flat">
                <div class="card-body text-center">
                    <div class="float-end">
                        <i class="mdi mdi-logout widget-icon bg-info-lighten text-info"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Total Pulang</h5>
                    <h3 class="mt-3 mb-3 text-info">{{ $statistikDetail['total_pulang'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card widget-flat">
                <div class="card-body text-center">
                    <div class="float-end">
                        <i class="mdi mdi-clock-alert widget-icon bg-warning-lighten text-warning"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Terlambat</h5>
                    <h3 class="mt-3 mb-3 text-warning">{{ $statistikDetail['total_terlambat'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card widget-flat">
                <div class="card-body text-center">
                    <div class="float-end">
                        <i class="mdi mdi-close-circle widget-icon bg-danger-lighten text-danger"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Alpha</h5>
                    <h3 class="mt-3 mb-3 text-danger">{{ $statistikDetail['total_alpha'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card widget-flat">
                <div class="card-body text-center">
                    <div class="float-end">
                        <i class="mdi mdi-percent widget-icon bg-secondary-lighten text-secondary"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Kehadiran</h5>
                    <h3 class="mt-3 mb-3 text-secondary">{{ $statistikDetail['persentase_kehadiran'] ?? 0 }}%</h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filter & Data Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Data Presensi Detail</h4>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" wire:model="tanggalMulai" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" wire:model="tanggalSelesai" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Jenis Presensi</label>
                            <select wire:model.live="jenisPresensi" class="form-select">
                                <option value="semua">Semua</option>
                                <option value="masuk">Masuk</option>
                                <option value="pulang">Pulang</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select wire:model.live="statusKeterlambatan" class="form-select">
                                <option value="semua">Semua</option>
                                <option value="tepat_waktu">Tepat Waktu</option>
                                <option value="terlambat">Terlambat</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Cari Tanggal</label>
                            <input type="text" wire:model.live.debounce.500ms="searchTanggal" class="form-control" placeholder="YYYY-MM-DD">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button wire:click="applyDateFilter" class="btn btn-primary">
                                <i class="mdi mdi-filter"></i> Filter
                            </button>
                        </div>
                    </div>

                    <!-- Data Table -->
                    @if($presensiData->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Hari</th>
                                        <th>Waktu Masuk</th>
                                        <th>Status Masuk</th>
                                        <th>Waktu Pulang</th>
                                        <th>Lokasi</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($presensiData as $index => $data)
                                        @php
                                            $tanggal = Carbon\Carbon::parse($data->tanggal);
                                            $masuk = $data->masuk;
                                            $pulang = $data->pulang;
                                            $isLate = $masuk && Carbon\Carbon::parse($masuk->waktu_presensi)->format('H:i') > '07:30';
                                        @endphp
                                        <tr>
                                            <td>{{ $presensiData->firstItem() + $index }}</td>
                                            <td>{{ $tanggal->format('d/m/Y') }}</td>
                                            <td>{{ $tanggal->locale('id')->dayName }}</td>
                                            <td>
                                                @if($masuk)
                                                    <strong class="text-success">{{ Carbon\Carbon::parse($masuk->waktu_presensi)->format('H:i:s') }}</strong>
                                                @else
                                                    <span class="text-danger">Tidak Masuk</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($masuk)
                                                    @if($isLate)
                                                        <span class="badge bg-warning">Terlambat</span>
                                                    @else
                                                        <span class="badge bg-success">Tepat Waktu</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-danger">Alpha</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($pulang)
                                                    <strong class="text-info">{{ Carbon\Carbon::parse($pulang->waktu_presensi)->format('H:i:s') }}</strong>
                                                @else
                                                    <span class="text-muted">Belum Pulang</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($masuk && $masuk->lokasi)
                                                    <small class="text-muted">{{ Str::limit($masuk->lokasi, 30) }}</small>
                                                @elseif($pulang && $pulang->lokasi)
                                                    <small class="text-muted">{{ Str::limit($pulang->lokasi, 30) }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($masuk && $masuk->keterangan)
                                                    <small>{{ Str::limit($masuk->keterangan, 50) }}</small>
                                                @elseif($pulang && $pulang->keterangan)
                                                    <small>{{ Str::limit($pulang->keterangan, 50) }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <small class="text-muted">
                                    Menampilkan {{ $presensiData->firstItem() }} sampai {{ $presensiData->lastItem() }} 
                                    dari {{ $presensiData->total() }} data
                                </small>
                            </div>
                            <div>
                                {{ $presensiData->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="mdi mdi-information-outline h1 text-muted"></i>
                            <h4 class="text-muted">Tidak ada data</h4>
                            <p class="text-muted">Belum ada data presensi untuk filter yang dipilih.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading wire:target="applyDateFilter,updatedJenisPresensi,updatedStatusKeterlambatan,updatedTanggalMulai,updatedTanggalSelesai" class="text-center">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 text-muted">Memuat data...</p>
    </div>
</div>