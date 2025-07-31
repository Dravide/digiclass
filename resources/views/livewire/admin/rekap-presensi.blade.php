<div>
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Rekap Presensi Siswa</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Rekap Presensi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-filter-line me-2"></i>
                        Filter Rekap Presensi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Tahun Pelajaran -->
                        <div class="col-md-3">
                            <label class="form-label">Tahun Pelajaran</label>
                            <select wire:model.live="selectedTahunPelajaran" class="form-select">
                                <option value="">Pilih Tahun Pelajaran</option>
                                @foreach($tahunPelajaranList as $tahunPelajaran)
                                    <option value="{{ $tahunPelajaran->id }}">{{ $tahunPelajaran->nama_tahun_pelajaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Kelas -->
                        <div class="col-md-3">
                            <label class="form-label">Kelas <span class="text-danger">*</span></label>
                            <select wire:model.live="selectedKelas" class="form-select">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Mata Pelajaran -->
                        <div class="col-md-3">
                            <label class="form-label">Mata Pelajaran</label>
                            <select wire:model.live="selectedMataPelajaran" class="form-select">
                                <option value="">Semua Mata Pelajaran</option>
                                @foreach($mataPelajaranList as $mataPelajaran)
                                    <option value="{{ $mataPelajaran->id }}">{{ $mataPelajaran->nama_mapel }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Filter Type -->
                        <div class="col-md-3">
                            <label class="form-label">Periode</label>
                            <select wire:model.live="filterType" class="form-select">
                                <option value="bulan">Per Bulan</option>
                                <option value="rentang">Rentang Tanggal</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        @if($filterType === 'bulan')
                            <!-- Bulan -->
                            <div class="col-md-3">
                                <label class="form-label">Bulan</label>
                                <select wire:model.live="selectedBulan" class="form-select">
                                    @foreach($bulanList as $key => $bulan)
                                        <option value="{{ $key }}">{{ $bulan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Tahun -->
                            <div class="col-md-3">
                                <label class="form-label">Tahun</label>
                                <select wire:model.live="selectedTahun" class="form-select">
                                    @foreach($tahunList as $tahun)
                                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <!-- Tanggal Mulai -->
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" wire:model="tanggalMulai" class="form-control">
                            </div>
                            
                            <!-- Tanggal Selesai -->
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" wire:model="tanggalSelesai" class="form-control">
                            </div>
                            
                            <!-- Apply Button -->
                            <div class="col-md-3 d-flex align-items-end">
                                <button wire:click="applyFilter" class="btn btn-primary">
                                    <i class="ri-search-line me-1"></i>
                                    Terapkan Filter
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($selectedKelas && !empty($rekapData))
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-primary bg-soft">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="ri-group-line text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-1">{{ $statistik['total_siswa'] ?? 0 }}</h5>
                                <p class="text-muted mb-0 small">Total Siswa</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-success bg-soft">
                                    <span class="avatar-title rounded-circle bg-success">
                                        <i class="ri-check-line text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-1">{{ $statistik['total_hadir'] ?? 0 }}</h5>
                                <p class="text-muted mb-0 small">Total Hadir</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-warning bg-soft">
                                    <span class="avatar-title rounded-circle bg-warning">
                                        <i class="ri-time-line text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-1">{{ $statistik['total_terlambat'] ?? 0 }}</h5>
                                <p class="text-muted mb-0 small">Terlambat</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-info bg-soft">
                                    <span class="avatar-title rounded-circle bg-info">
                                        <i class="ri-information-line text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-1">{{ $statistik['total_izin'] ?? 0 }}</h5>
                                <p class="text-muted mb-0 small">Izin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-secondary bg-soft">
                                    <span class="avatar-title rounded-circle bg-secondary">
                                        <i class="ri-heart-pulse-line text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-1">{{ $statistik['total_sakit'] ?? 0 }}</h5>
                                <p class="text-muted mb-0 small">Sakit</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-danger bg-soft">
                                    <span class="avatar-title rounded-circle bg-danger">
                                        <i class="ri-close-line text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-1">{{ $statistik['total_alpha'] ?? 0 }}</h5>
                                <p class="text-muted mb-0 small">Alpha</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Rekap Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ri-table-line me-2"></i>
                                Rekap Presensi per Siswa
                            </h5>
                            <div class="d-flex gap-2">
                                <button wire:click="exportExcel" class="btn btn-success btn-sm">
                                    <i class="ri-file-excel-line me-1"></i>
                                    Export Excel
                                </button>
                                <button wire:click="exportPDF" class="btn btn-danger btn-sm">
                                    <i class="ri-file-pdf-line me-1"></i>
                                    Export PDF
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th class="text-center">Hadir</th>
                                        <th class="text-center">Terlambat</th>
                                        <th class="text-center">Izin</th>
                                        <th class="text-center">Sakit</th>
                                        <th class="text-center">Alpha</th>
                                        <th class="text-center">Total Pertemuan</th>
                                        <th class="text-center">Persentase Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rekapData as $index => $data)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <span class="fw-medium">{{ $data['siswa']->nis }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar avatar-xs">
                                                            <div class="avatar-initial bg-primary rounded-circle">
                                                                {{ substr($data['siswa']->nama_siswa, 0, 1) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <span class="fw-medium">{{ $data['siswa']->nama_siswa }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success">{{ $data['hadir'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning">{{ $data['terlambat'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $data['izin'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary">{{ $data['sakit'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-danger">{{ $data['alpha'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-medium">{{ $data['total_pertemuan'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $persentase = $data['persentase_kehadiran'];
                                                    $badgeClass = $persentase >= 80 ? 'bg-success' : ($persentase >= 60 ? 'bg-warning' : 'bg-danger');
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ $persentase }}%</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if(!empty($statistik))
                            <div class="mt-3 p-3 bg-light rounded">
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <h6 class="mb-1">Total Pertemuan</h6>
                                        <span class="text-primary fw-bold">{{ $statistik['total_pertemuan'] }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <h6 class="mb-1">Rata-rata Kehadiran</h6>
                                        <span class="text-success fw-bold">{{ $statistik['rata_rata_kehadiran'] }}%</span>
                                    </div>
                                    <div class="col-md-3">
                                        <h6 class="mb-1">Periode</h6>
                                        <span class="text-muted">
                                            @if($filterType === 'bulan')
                                                {{ $bulanList[$selectedBulan] }} {{ $selectedTahun }}
                                            @else
                                                {{ Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($tanggalSelesai)->format('d/m/Y') }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <h6 class="mb-1">Mata Pelajaran</h6>
                                        <span class="text-muted">
                                            @if($selectedMataPelajaran)
                                                {{ $mataPelajaranList->find($selectedMataPelajaran)->nama_mapel ?? 'Semua' }}
                                            @else
                                                Semua Mata Pelajaran
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @elseif($selectedKelas && empty($rekapData))
        <!-- No Data -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="ri-file-list-3-line text-muted" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="text-muted">Tidak Ada Data Presensi</h5>
                        <p class="text-muted mb-0">Belum ada data presensi untuk kelas dan periode yang dipilih.</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Select Class First -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="ri-school-line text-muted" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="text-muted">Pilih Kelas Terlebih Dahulu</h5>
                        <p class="text-muted mb-0">Silakan pilih kelas untuk melihat rekap presensi siswa.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .avatar-xs {
        height: 1.5rem;
        width: 1.5rem;
    }
    
    .avatar-xs .avatar-initial {
        font-size: 0.75rem;
    }
    
    .table th {
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .bg-soft {
        background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
    }
</style>
@endpush