<div>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tasks"></i> Tugas Saya
            </h1>
        </div>

        @if($siswa)
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Belum Dikerjakan
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $assignmentStats['pending'] ?? 0 }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Sudah Dikumpulkan
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $assignmentStats['submitted'] ?? 0 }}
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
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Terlambat
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $assignmentStats['overdue'] ?? 0 }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                                        Total Tugas
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $assignmentStats['total'] ?? 0 }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Tugas</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search">Cari Tugas</label>
                                <input type="text" class="form-control" id="search" wire:model.live="search" placeholder="Masukkan judul tugas...">
                            </div>
                        </div>
                        <div class="col-md-3">
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
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filterStatus">Status</label>
                                <select class="form-control" id="filterStatus" wire:model.live="filterStatus">
                                    <option value="">Semua Status</option>
                                    @foreach($statusOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignments Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Tugas</h6>
                </div>
                <div class="card-body">
                    @if($tugas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Tugas</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Guru</th>
                                        <th>Kelas</th>
                                        <th>Deadline</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tugas as $index => $item)
                                        <tr>
                                            <td>{{ $tugas->firstItem() + $index }}</td>
                                            <td>
                                                <strong>{{ $item->judul_tugas }}</strong>
                                                @if($item->deskripsi)
                                                    <br><small class="text-muted">{{ Str::limit($item->deskripsi, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $item->mataPelajaran->nama_mapel ?? '-' }}</td>
                                            <td>{{ $item->guru->nama ?? '-' }}</td>
                                            <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($item->deadline)->format('d/m/Y H:i') }}
                                                @if(\Carbon\Carbon::parse($item->deadline)->isPast())
                                                    <br><small class="text-danger">Sudah lewat</small>
                                                @else
                                                    <br><small class="text-success">{{ \Carbon\Carbon::parse($item->deadline)->diffForHumans() }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @switch($item->status)
                                                    @case('pending')
                                                        <span class="badge badge-warning">Belum Dikerjakan</span>
                                                        @break
                                                    @case('submitted')
                                                        <span class="badge badge-success">Sudah Dikumpulkan</span>
                                                        @break
                                                    @case('overdue')
                                                        <span class="badge badge-danger">Terlambat</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-secondary">{{ ucfirst($item->status) }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailModal{{ $item->id }}">
                                                    <i class="fas fa-eye"></i> Detail
                                                </button>
                                                @if($item->status === 'pending')
                                                    <button class="btn btn-sm btn-primary ml-1">
                                                        <i class="fas fa-upload"></i> Kumpulkan
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Detail Modal -->
                                        <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Detail Tugas: {{ $item->judul_tugas }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <strong>Mata Pelajaran:</strong><br>
                                                                {{ $item->mataPelajaran->nama_mapel ?? '-' }}
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Guru:</strong><br>
                                                                {{ $item->guru->nama ?? '-' }}
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <strong>Kelas:</strong><br>
                                                                {{ $item->kelas->nama_kelas ?? '-' }}
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Deadline:</strong><br>
                                                                {{ \Carbon\Carbon::parse($item->deadline)->format('d/m/Y H:i') }}
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <strong>Deskripsi:</strong><br>
                                                        <p>{{ $item->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                                                        
                                                        @if($item->file_tugas)
                                                            <hr>
                                                            <strong>File Tugas:</strong><br>
                                                            <a href="{{ asset('storage/' . $item->file_tugas) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                                <i class="fas fa-download"></i> Download File
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                        @if($item->status === 'pending')
                                                            <button type="button" class="btn btn-primary">
                                                                <i class="fas fa-upload"></i> Kumpulkan Tugas
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $tugas->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-tasks fa-3x text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Belum ada tugas yang tersedia.</p>
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