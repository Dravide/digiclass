<div>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-calendar-check"></i> Presensi Saya
            </h1>
        </div>

        @if($siswa)
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Hadir
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $attendanceStats['hadir'] ?? 0 }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Izin
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $attendanceStats['izin'] ?? 0 }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-hand-paper fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Sakit
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $attendanceStats['sakit'] ?? 0 }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-thermometer-half fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Alpha
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $attendanceStats['alpha'] ?? 0 }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-times fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Percentage -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Persentase Kehadiran
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                {{ $attendanceStats['percentage'] ?? 0 }}%
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="progress progress-sm mr-2">
                                                <div class="progress-bar bg-primary" role="progressbar" 
                                                     style="width: {{ $attendanceStats['percentage'] ?? 0 }}%" 
                                                     aria-valuenow="{{ $attendanceStats['percentage'] ?? 0 }}" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-percentage fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Presensi</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filterBulan">Bulan</label>
                                <select class="form-control" id="filterBulan" wire:model.live="filterBulan">
                                    <option value="">Semua Bulan</option>
                                    @foreach($bulanOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Presensi</h6>
                </div>
                <div class="card-body">
                    @if($presensi->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Tahun Pelajaran</th>
                                        <th>Waktu Input</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($presensi as $index => $item)
                                        <tr>
                                            <td>{{ $presensi->firstItem() + $index }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                            <td>
                                                @switch($item->status)
                                                    @case('hadir')
                                                        <span class="badge badge-success">Hadir</span>
                                                        @break
                                                    @case('izin')
                                                        <span class="badge badge-info">Izin</span>
                                                        @break
                                                    @case('sakit')
                                                        <span class="badge badge-warning">Sakit</span>
                                                        @break
                                                    @case('alpha')
                                                        <span class="badge badge-danger">Alpha</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-secondary">{{ ucfirst($item->status) }}</span>
                                                @endswitch
                                            </td>
                                            <td>{{ $item->keterangan ?? '-' }}</td>
                                            <td>{{ $item->tahunPelajaran->nama_tahun_pelajaran ?? '-' }}</td>
                                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $presensi->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-check fa-3x text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Belum ada data presensi yang tersedia.</p>
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