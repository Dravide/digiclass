<div>
    @section('title', 'Rekap Nilai Siswa')
    
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Rekap Nilai Siswa</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Rekap Nilai</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- Flash Messages -->
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session()->has('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Header dengan tombol export -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h4 class="card-title">Rekap Nilai Siswa</h4>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-success" wire:click="exportExcel">
                                            <i class="mdi mdi-file-excel"></i> Export Excel
                                        </button>
                                        <button type="button" class="btn btn-danger" wire:click="exportPDF">
                                            <i class="mdi mdi-file-pdf"></i> Export PDF
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter dan Search -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" placeholder="Cari nama/NIS siswa..." wire:model.live="search">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" wire:model.live="filterKelas">
                                        <option value="">Semua Kelas</option>
                                        @foreach($kelas ?? [] as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" wire:model.live="filterMataPelajaran">
                                        <option value="">Semua Mata Pelajaran</option>
                                        @foreach($mataPelajaran ?? [] as $mp)
                                            <option value="{{ $mp->id }}">{{ $mp->nama_mapel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" wire:model.live="filterTahunPelajaran">
                                        <option value="">Semua Tahun Pelajaran</option>
                                        @foreach($tahunPelajaran ?? [] as $tp)
                                            <option value="{{ $tp->id }}">{{ $tp->tanggal_mulai->format('Y') }}/{{ $tp->tanggal_selesai->format('Y') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Summary Cards -->
                            <div class="row mb-3">
                                <div class="col-xl-3 col-md-6">
                                    <div class="card card-h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Siswa</span>
                                                    <h4 class="mb-3">
                                                        {{ $siswa->total() ?? 0 }}
                                                    </h4>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-sm rounded-circle bg-primary">
                                                        <span class="avatar-title rounded-circle bg-primary">
                                                            <i class="mdi mdi-account-group font-size-24"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3 col-md-6">
                                    <div class="card card-h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <span class="text-muted mb-3 lh-1 d-block text-truncate">Rata-rata Kelas</span>
                                                    <h4 class="mb-3">
                                                        {{ number_format($siswa->avg('rata_rata') ?? 0, 1) }}
                                                    </h4>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-sm rounded-circle bg-success">
                                                        <span class="avatar-title rounded-circle bg-success">
                                                            <i class="mdi mdi-chart-line font-size-24"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3 col-md-6">
                                    <div class="card card-h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <span class="text-muted mb-3 lh-1 d-block text-truncate">Siswa Lulus (≥75)</span>
                                                    <h4 class="mb-3">
                                                        {{ $siswa->where('rata_rata', '>=', 75)->count() }}
                                                    </h4>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-sm rounded-circle bg-info">
                                                        <span class="avatar-title rounded-circle bg-info">
                                                            <i class="mdi mdi-check-circle font-size-24"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3 col-md-6">
                                    <div class="card card-h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <span class="text-muted mb-3 lh-1 d-block text-truncate">Persentase Kelulusan</span>
                                                    <h4 class="mb-3">
                                                        {{ $siswa->count() > 0 ? number_format(($siswa->where('rata_rata', '>=', 75)->count() / $siswa->count()) * 100, 1) : 0 }}%
                                                    </h4>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-sm rounded-circle bg-warning">
                                                        <span class="avatar-title rounded-circle bg-warning">
                                                            <i class="mdi mdi-percent font-size-24"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabel Rekap Nilai -->
                            <div class="table-responsive">
                                <table class="table table-hover table-nowrap align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Siswa</th>
                                            <th>Kelas</th>
                                            <th>Total Tugas</th>
                                            <th>Rata-rata</th>
                                            <th>Grade</th>
                                            <th>Tertinggi</th>
                                            <th>Terendah</th>
                                            <th>% Lulus</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($siswa ?? [] as $index => $s)
                                            <tr>
                                                <td>{{ ($siswa->currentPage() - 1) * $siswa->perPage() + $index + 1 }}</td>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-1">{{ $s->nama_siswa }}</h6>
                                                        <p class="text-muted mb-0 small">NIS: {{ $s->nis }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($s->kelasSiswa->isNotEmpty())
                                                        {{ $s->kelasSiswa->first()->kelas->nama_kelas }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">{{ $s->total_tugas }}</span>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <h5 class="mb-0 {{ $this->getGradeColor($s->rata_rata) }}">{{ number_format($s->rata_rata, 1) }}</h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light {{ $this->getGradeColor($s->rata_rata) }} font-size-14">{{ $this->getGradeLabel($s->rata_rata) }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-success fw-bold">{{ number_format($s->nilai_tertinggi, 1) }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-danger fw-bold">{{ number_format($s->nilai_terendah, 1) }}</span>
                                                </td>
                                                <td>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar {{ $s->persentase_lulus >= 75 ? 'bg-success' : ($s->persentase_lulus >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                                             style="width: {{ $s->persentase_lulus }}%"></div>
                                                    </div>
                                                    <small class="text-muted">{{ number_format($s->persentase_lulus, 1) }}%</small>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-outline-info btn-sm" wire:click="showDetail({{ $s->id }})" title="Lihat Detail">
                                                            <i class="mdi mdi-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-success btn-sm" wire:click="exportExcel({{ $s->id }})" title="Export Excel">
                                                            <i class="mdi mdi-file-excel"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" wire:click="exportPDF({{ $s->id }})" title="Export PDF">
                                                            <i class="mdi mdi-file-pdf"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="mdi mdi-chart-line font-size-48 d-block mb-2"></i>
                                                        Belum ada data rekap nilai
                                                    </div>
                                                </td>
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

    <!-- Modal Detail Nilai Siswa -->
    @if($showDetailModal && $selectedSiswa)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Nilai: {{ $selectedSiswa->nama_siswa }}</h5>
                        <button type="button" class="btn-close" wire:click="closeDetailModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>NIS:</strong> {{ $selectedSiswa->nis }}</p>
                                <p><strong>Nama:</strong> {{ $selectedSiswa->nama_siswa }}</p>
                                @if($selectedSiswa->kelasSiswa->isNotEmpty())
                                    <p><strong>Kelas:</strong> {{ $selectedSiswa->kelasSiswa->first()->kelas->nama_kelas }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <p><strong>Total Tugas:</strong> {{ $selectedSiswa->nilai->count() }}</p>
                                <p><strong>Rata-rata:</strong> <span class="{{ $this->getGradeColor($selectedSiswa->nilai->avg('nilai')) }}">{{ number_format($selectedSiswa->nilai->avg('nilai') ?? 0, 1) }}</span></p>
                                <p><strong>Grade:</strong> <span class="badge bg-light {{ $this->getGradeColor($selectedSiswa->nilai->avg('nilai')) }}">{{ $this->getGradeLabel($selectedSiswa->nilai->avg('nilai') ?? 0) }}</span></p>
                            </div>
                        </div>
                        
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-bordered table-sm">
                                <thead class="bg-light sticky-top">
                                    <tr>
                                        <th>No</th>
                                        <th>Tugas</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Kelas</th>
                                        <th>Nilai</th>
                                        <th>Grade</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($selectedSiswa->nilai->sortBy('tugas.created_at') as $index => $nilai)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $nilai->tugas->judul }}</td>
                                            <td>{{ $nilai->tugas->mataPelajaran->nama_mapel }}</td>
                                            <td>{{ $nilai->tugas->kelas->nama_kelas }}</td>
                                            <td>
                                                @if($nilai->nilai)
                                                    <span class="fw-bold {{ $this->getGradeColor($nilai->nilai) }}">{{ $nilai->formatted_nilai }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($nilai->nilai)
                                                    <span class="badge bg-light {{ $this->getGradeColor($nilai->nilai) }}">{{ $this->getGradeLabel($nilai->nilai) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $nilai->status_badge_class }}">{{ $nilai->status_label }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" wire:click="exportExcel({{ $selectedSiswa->id }})">
                            <i class="mdi mdi-file-excel"></i> Export Excel
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="exportPDF({{ $selectedSiswa->id }})">
                            <i class="mdi mdi-file-pdf"></i> Export PDF
                        </button>
                        <button type="button" class="btn btn-secondary" wire:click="closeDetailModal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>