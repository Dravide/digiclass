<div>
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Statistik Sekolah</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Statistik</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tahun Pelajaran -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <label class="form-label">Filter Tahun Pelajaran</label>
                    <select class="form-select" wire:model.live="selectedTahunPelajaran">
                        <option value="">Semua Tahun Pelajaran</option>
                        @foreach($tahunPelajaranList as $tahun)
                            <option value="{{ $tahun->id }}">
                                {{ $tahun->nama_tahun_pelajaran }}
                                @if($tahun->is_active) (Aktif) @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Keseluruhan -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="ri-bar-chart-line me-2"></i>Rekap Keseluruhan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-2 col-md-4 col-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-group-line font-size-24"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-0 text-white">{{ number_format($statistikKeseluruhan['total_siswa']) }}</h4>
                                            <p class="mb-0 text-white-50">Total Siswa</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-2 col-md-4 col-6">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-men-line font-size-24"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-0 text-white">{{ number_format($statistikKeseluruhan['total_siswa_laki']) }}</h4>
                                            <p class="mb-0 text-white-50">Laki-laki</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-2 col-md-4 col-6">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-women-line font-size-24"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-0 text-white">{{ number_format($statistikKeseluruhan['total_siswa_perempuan']) }}</h4>
                                            <p class="mb-0 text-white-50">Perempuan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-2 col-md-4 col-6">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-school-line font-size-24"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-0 text-white">{{ number_format($statistikKeseluruhan['total_kelas']) }}</h4>
                                            <p class="mb-0 text-white-50">Total Kelas</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-2 col-md-4 col-6">
                            <div class="card bg-secondary text-white">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-user-3-line font-size-24"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-0 text-white">{{ number_format($statistikKeseluruhan['total_guru']) }}</h4>
                                            <p class="mb-0 text-white-50">Total Guru</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-2 col-md-4 col-6">
                            <div class="card bg-dark text-white">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-book-line font-size-24"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-0 text-white">{{ number_format($statistikKeseluruhan['total_perpustakaan_terpenuhi']) }}</h4>
                            <p class="mb-0 text-white-50">Perpus Terpenuhi</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Per Tingkat -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="ri-bar-chart-2-line me-2"></i>Rekap Per Tingkat</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tingkat</th>
                                    <th class="text-center">Kelas</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">L</th>
                                    <th class="text-center">P</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($statistikPerTingkat as $tingkat)
                                <tr>
                                    <td><strong>Kelas {{ $tingkat->tingkat }}</strong></td>
                                    <td class="text-center">{{ $tingkat->total_kelas }}</td>
                                    <td class="text-center"><span class="badge bg-primary">{{ $tingkat->total_siswa }}</span></td>
                                    <td class="text-center"><span class="badge bg-info">{{ $tingkat->total_laki }}</span></td>
                                    <td class="text-center"><span class="badge bg-warning">{{ $tingkat->total_perempuan }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistik Status & Keterangan -->
        <div class="col-lg-6">
            <div class="row">
                <!-- Status Siswa -->
                <div class="col-12 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0"><i class="ri-user-settings-line me-2"></i>Status Siswa</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">L</th>
                                            <th class="text-center">P</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($statistikStatus as $status)
                                        <tr>
                                            <td>
                                                <span class="badge {{ $status->status == 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $status->status)) }}
                                                </span>
                                            </td>
                                            <td class="text-center">{{ $status->total }}</td>
                                            <td class="text-center">{{ $status->total_laki }}</td>
                                            <td class="text-center">{{ $status->total_perempuan }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Tidak ada data</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Keterangan Siswa -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0"><i class="ri-information-line me-2"></i>Keterangan Siswa</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Keterangan</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">L</th>
                                            <th class="text-center">P</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($statistikKeterangan as $keterangan)
                                        <tr>
                                            <td>
                                                @php
                                                    $badgeClass = match($keterangan->keterangan) {
                                                        'siswa_baru' => 'bg-success',
                                                        'pindahan' => 'bg-info',
                                                        'mengundurkan_diri' => 'bg-warning',
                                                        'keluar' => 'bg-danger',
                                                        'meninggal_dunia' => 'bg-dark',
                                                        'alumni' => 'bg-primary',
                                                        default => 'bg-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">
                                                    {{ ucfirst(str_replace('_', ' ', $keterangan->keterangan)) }}
                                                </span>
                                            </td>
                                            <td class="text-center">{{ $keterangan->total }}</td>
                                            <td class="text-center">{{ $keterangan->total_laki }}</td>
                                            <td class="text-center">{{ $keterangan->total_perempuan }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Tidak ada data</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Per Kelas -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="ri-building-line me-2"></i>Rekap Per Kelas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kelas</th>
                                    <th>Tingkat</th>
                                    <th>Jurusan</th>
                                    <th>Wali Kelas</th>
                                    <th>Tahun Pelajaran</th>
                                    <th class="text-center">Kapasitas</th>
                                    <th class="text-center">Total Siswa</th>
                                    <th class="text-center">Laki-laki</th>
                                    <th class="text-center">Perempuan</th>
                                    <th class="text-center">Sisa Kapasitas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($statistikPerKelas as $kelas)
                                <tr>
                                    <td><strong>{{ $kelas->nama_kelas }}</strong></td>
                                    <td><span class="badge bg-secondary">{{ $kelas->tingkat }}</span></td>
                                    <td>{{ $kelas->jurusan ?: '-' }}</td>
                                    <td>{{ $kelas->guru->nama_guru ?? '-' }}</td>
                                    <td>{{ $kelas->tahunPelajaran->nama_tahun_pelajaran ?? '-' }}</td>
                                    <td class="text-center">{{ $kelas->kapasitas }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $kelas->total_siswa }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $kelas->total_laki }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning">{{ $kelas->total_perempuan }}</span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $sisa = $kelas->kapasitas - $kelas->total_siswa;
                                            $badgeClass = $sisa > 5 ? 'bg-success' : ($sisa > 0 ? 'bg-warning' : 'bg-danger');
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $sisa }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">Tidak ada data kelas</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section (Optional) -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="ri-pie-chart-line me-2"></i>Distribusi Jenis Kelamin</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <div class="text-center me-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title bg-info rounded-circle">
                                    <i class="ri-men-line font-size-24"></i>
                                </div>
                            </div>
                            <h4 class="mb-1">{{ number_format($statistikKeseluruhan['total_siswa_laki']) }}</h4>
                            <p class="text-muted mb-0">Laki-laki</p>
                            @if($statistikKeseluruhan['total_siswa'] > 0)
                                <small class="text-muted">
                                    ({{ number_format(($statistikKeseluruhan['total_siswa_laki'] / $statistikKeseluruhan['total_siswa']) * 100, 1) }}%)
                                </small>
                            @endif
                        </div>
                        <div class="text-center">
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title bg-warning rounded-circle">
                                    <i class="ri-women-line font-size-24"></i>
                                </div>
                            </div>
                            <h4 class="mb-1">{{ number_format($statistikKeseluruhan['total_siswa_perempuan']) }}</h4>
                            <p class="text-muted mb-0">Perempuan</p>
                            @if($statistikKeseluruhan['total_siswa'] > 0)
                                <small class="text-muted">
                                    ({{ number_format(($statistikKeseluruhan['total_siswa_perempuan'] / $statistikKeseluruhan['total_siswa']) * 100, 1) }}%)
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="ri-book-open-line me-2"></i>Status Perpustakaan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <div class="text-center me-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title bg-success rounded-circle">
                                    <i class="ri-check-line font-size-24"></i>
                                </div>
                            </div>
                            <h4 class="mb-1">{{ number_format($statistikKeseluruhan['total_perpustakaan_terpenuhi']) }}</h4>
                            <p class="text-muted mb-0">Terpenuhi</p>
                        </div>
                        <div class="text-center">
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title bg-danger rounded-circle">
                                    <i class="ri-close-line font-size-24"></i>
                                </div>
                            </div>
                            <h4 class="mb-1">{{ number_format($statistikKeseluruhan['total_perpustakaan_belum_terpenuhi']) }}</h4>
                            <p class="text-muted mb-0">Belum Terpenuhi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>