<div>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-line"></i> Nilai Saya
            </h1>
        </div>

        @if($siswa)
            <!-- Statistics Cards -->
            <div class="row mb-4">
                @foreach($averageGrades as $avgGrade)
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            {{ $avgGrade->mataPelajaran->nama_mapel }}
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ number_format($avgGrade->avg_nilai, 1) }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-star fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Filters -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Nilai</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="search">Cari Mata Pelajaran</label>
                                <input type="text" class="form-control" id="search" wire:model.live="search" placeholder="Masukkan nama mata pelajaran...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="filterTahunPelajaran">Tahun Pelajaran</label>
                                <select class="form-control" id="filterTahunPelajaran" wire:model.live="filterTahunPelajaran">
                                    <option value="">Semua Tahun Pelajaran</option>
                                    @foreach($tahunPelajaranOptions as $tahun)
                                        <option value="{{ $tahun->id }}">{{ $tahun->nama_tahun_pelajaran }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="filterMataPelajaran">Mata Pelajaran</label>
                                <select class="form-control" id="filterMataPelajaran" wire:model.live="filterMataPelajaran">
                                    <option value="">Semua Mata Pelajaran</option>
                                    @foreach($mataPelajaranOptions as $mapel)
                                        <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grades Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Nilai</h6>
                </div>
                <div class="card-body">
                    @if($nilai->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Jenis Nilai</th>
                                        <th>Nilai</th>
                                        <th>Keterangan</th>
                                        <th>Tahun Pelajaran</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($nilai as $index => $item)
                                        <tr>
                                            <td>{{ $nilai->firstItem() + $index }}</td>
                                            <td>{{ $item->mataPelajaran->nama_mapel ?? '-' }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ ucfirst($item->jenis_nilai ?? 'Umum') }}</span>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($item->nilai >= 80) badge-success
                                                    @elseif($item->nilai >= 70) badge-warning
                                                    @else badge-danger
                                                    @endif">
                                                    {{ $item->nilai }}
                                                </span>
                                            </td>
                                            <td>{{ $item->keterangan ?? '-' }}</td>
                                            <td>{{ $item->tahunPelajaran->nama_tahun_pelajaran ?? '-' }}</td>
                                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $nilai->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Belum ada data nilai yang tersedia.</p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="card shadow mb-4">
                <div class="card-body text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5 class="text-gray-700">Data Siswa Tidak Ditemukan</h5>
                    <p class="text-gray-500">Silakan hubungi administrator untuk mengatur data siswa Anda.</p>
                </div>
            </div>
        @endif
    </div>
</div>