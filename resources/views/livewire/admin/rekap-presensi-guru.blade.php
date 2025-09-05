<div>
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Rekap Presensi Guru</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Rekap Presensi Guru</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">Filter Data</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Filter Type -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tipe Filter</label>
                            <select wire:model.live="filterType" class="form-select">
                                <option value="bulan">Per Bulan</option>
                                <option value="rentang">Rentang Tanggal</option>
                            </select>
                        </div>

                        @if($filterType === 'bulan')
                            <!-- Bulan -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Bulan</label>
                                <select wire:model.live="selectedBulan" class="form-select">
                                    @foreach($bulanList as $key => $bulan)
                                        <option value="{{ $key }}">{{ $bulan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tahun -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tahun</label>
                                <select wire:model.live="selectedTahun" class="form-select">
                                    @foreach($tahunList as $tahun)
                                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <!-- Tanggal Mulai -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" wire:model="tanggalMulai" class="form-control">
                            </div>

                            <!-- Tanggal Selesai -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" wire:model="tanggalSelesai" class="form-control">
                            </div>
                        @endif

                        <!-- Search Guru -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Cari Guru</label>
                            <input type="text" wire:model.live.debounce.500ms="searchGuru" class="form-control" placeholder="Nama atau username guru...">
                        </div>

                        @if($filterType === 'rentang')
                            <!-- Apply Button -->
                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <button wire:click="applyFilter" class="btn btn-primary">
                                    <i class="mdi mdi-filter"></i> Terapkan Filter
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Section -->
    @if(!empty($statistik))
    <div class="row">
        <div class="col-md-2">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-account-multiple widget-icon"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Total Guru">Total Guru</h5>
                    <h3 class="mt-3 mb-3">{{ $statistik['total_guru'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-login widget-icon bg-success-lighten text-success"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Total Presensi Masuk">Total Masuk</h5>
                    <h3 class="mt-3 mb-3">{{ $statistik['total_masuk'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-briefcase-clock widget-icon bg-primary-lighten text-primary"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Total Lembur">Total Lembur</h5>
                    <h3 class="mt-3 mb-3">{{ $statistik['total_lembur'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-clock widget-icon bg-primary-lighten text-primary"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Total Jam Lembur">Jam Lembur</h5>
                    <h3 class="mt-3 mb-3">{{ $statistik['total_jam_lembur'] ?? 0 }} jam</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-clock-alert widget-icon bg-warning-lighten text-warning"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Total Terlambat">Total Terlambat</h5>
                    <h3 class="mt-3 mb-3">{{ $statistik['total_terlambat'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-percent widget-icon bg-info-lighten text-info"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Rata-rata Kehadiran">Rata-rata Kehadiran</h5>
                    <h3 class="mt-3 mb-3">{{ $statistik['rata_rata_kehadiran'] ?? 0 }}%</h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Data Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title mb-0">Data Rekap Presensi Guru</h4>
                    <div>
                        <button wire:click="exportExcel" class="btn btn-success btn-sm me-1">
                            <i class="mdi mdi-file-excel"></i> Excel
                        </button>
                        <button wire:click="exportPDF" class="btn btn-danger btn-sm">
                            <i class="mdi mdi-file-pdf"></i> PDF
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($rekapData) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Guru</th>
                                        <th>Username</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Hari Kerja</th>
                                        <th>Masuk</th>
                                        <th>Pulang</th>
                                        <th>Lembur</th>
                                        <th>Jam Lembur</th>
                                        <th>Terlambat</th>
                                        <th>Alpha</th>
                                        <th>Kehadiran (%)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rekapData as $index => $data)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $data['guru']->user->name ?? '-' }}</td>
                                            <td>{{ $data['guru']->user->username ?? '-' }}</td>
                                            <td>
                                                @if($data['guru']->mataPelajaran)
                                                    <span class="badge bg-primary">{{ $data['guru']->mataPelajaran->nama_mata_pelajaran }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $data['hari_kerja'] }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ $data['masuk'] }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $data['pulang'] }}</span>
                                            </td>
                                            <td>
                                                @if($data['lembur'] > 0)
                                                    <span class="badge bg-primary">{{ $data['lembur'] }}</span>
                                                @else
                                                    <span class="badge bg-light text-dark">0</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($data['total_menit_lembur'] > 0)
                                                    <span class="badge bg-primary">{{ number_format($data['total_menit_lembur'] / 60, 1) }} jam</span>
                                                @else
                                                    <span class="badge bg-light text-dark">0 jam</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($data['terlambat'] > 0)
                                                    <span class="badge bg-warning">{{ $data['terlambat'] }}</span>
                                                @else
                                                    <span class="badge bg-light text-dark">0</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($data['alpha'] > 0)
                                                    <span class="badge bg-danger">{{ $data['alpha'] }}</span>
                                                @else
                                                    <span class="badge bg-light text-dark">0</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $persentase = $data['persentase_kehadiran'];
                                                    $badgeClass = 'bg-danger';
                                                    if ($persentase >= 90) $badgeClass = 'bg-success';
                                                    elseif ($persentase >= 75) $badgeClass = 'bg-warning';
                                                    elseif ($persentase >= 60) $badgeClass = 'bg-info';
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ $persentase }}%</span>
                                            </td>
                                            <td>
                                                @php
                                                    $startDate = $filterType === 'bulan' 
                                                        ? \Carbon\Carbon::createFromDate($selectedTahun, $selectedBulan, 1)->format('Y-m-d')
                                                        : $tanggalMulai;
                                                    $endDate = $filterType === 'bulan' 
                                                        ? \Carbon\Carbon::createFromDate($selectedTahun, $selectedBulan, 1)->endOfMonth()->format('Y-m-d')
                                                        : $tanggalSelesai;
                                                @endphp
                                                <a href="{{ route('admin.detail-presensi-guru', [
                                                    'guruId' => $data['guru']->id,
                                                    'tanggalMulai' => $startDate,
                                                    'tanggalSelesai' => $endDate
                                                ]) }}" 
                                                   class="btn btn-sm btn-primary" 
                                                   title="Lihat Detail Presensi">
                                                    <i class="mdi mdi-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="mdi mdi-information-outline h1 text-muted"></i>
                            <h4 class="text-muted">Tidak ada data</h4>
                            <p class="text-muted">Belum ada data presensi guru untuk periode yang dipilih.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading wire:target="applyFilter,loadRekapData,updatedSelectedBulan,updatedSelectedTahun,updatedFilterType" class="text-center">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 text-muted">Memuat data...</p>
    </div>
</div>