<div>
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-subtle-primary text-primary rounded fs-2">
                                <i class="ri-calendar-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="text-muted text-truncate font-size-15 mb-2">Total Jadwal</p>
                            <h3 class="fs-4 flex-grow-1 mb-3">{{ $jadwals->total() ?? 0 }}</h3>
                            <p class="text-muted mb-0 text-truncate">Jadwal terdaftar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-subtle-success text-success rounded fs-2">
                                <i class="ri-check-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="text-muted text-truncate font-size-15 mb-2">Jadwal Aktif</p>
                            <h3 class="fs-4 flex-grow-1 mb-3">
                                {{ $jadwals->where('is_active', true)->count() ?? 0 }}
                            </h3>
                            <p class="text-muted mb-0 text-truncate">Sedang berlangsung</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-subtle-warning text-warning rounded fs-2">
                                <i class="ri-user-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="text-muted text-truncate font-size-15 mb-2">Total Guru</p>
                            <h3 class="fs-4 flex-grow-1 mb-3">{{ $gurus->count() ?? 0 }}</h3>
                            <p class="text-muted mb-0 text-truncate">Guru terdaftar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-subtle-info text-info rounded fs-2">
                                <i class="ri-book-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="text-muted text-truncate font-size-15 mb-2">Mata Pelajaran</p>
                            <h3 class="fs-4 flex-grow-1 mb-3">{{ $mataPelajarans->count() ?? 0 }}</h3>
                            <p class="text-muted mb-0 text-truncate">Mapel tersedia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-check-circle-line me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Manajemen Jadwal Guru</h4>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" wire:click="create">
                                <i class="ri-add-line me-1"></i> Tambah Jadwal
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="search-box">
                                <div class="position-relative">
                                    <input type="text" class="form-control" placeholder="Cari guru, mata pelajaran, atau kelas..." wire:model.live="search">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" wire:model.live="filterGuru">
                                <option value="">Semua Guru</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->id }}">{{ $guru->nama_guru }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" wire:model.live="filterKelas">
                                <option value="">Semua Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" wire:model.live="filterHari">
                                <option value="">Semua Hari</option>
                                @foreach($hariOptions as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="filterMataPelajaran">
                                <option value="">Semua Mata Pelajaran</option>
                                @foreach($mataPelajarans as $mapel)
                                    <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-nowrap table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" width="5%">#</th>
                                    <th scope="col" width="10%">Hari</th>
                                    <th scope="col" width="8%">Jam Ke</th>
                                    <th scope="col" width="12%">Waktu</th>
                                    <th scope="col" width="15%">Guru</th>
                                    <th scope="col" width="15%">Mata Pelajaran</th>
                                    <th scope="col" width="10%">Kelas</th>
                                    <th scope="col" width="15%">Keterangan</th>
                                    <th scope="col" width="5%">Status</th>
                                    <th scope="col" width="5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jadwals as $index => $jadwal)
                                    <tr>
                                        <td>{{ $jadwals->firstItem() + $index }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $jadwal->hari_indonesia }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $jadwal->jam_ke }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $jadwal->jam_format }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <div class="avatar-initial bg-primary rounded-circle">
                                                        {{ substr($jadwal->guru->nama_guru, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $jadwal->guru->nama_guru }}</div>
                                                    <small class="text-muted">{{ $jadwal->guru->nip }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-bold">{{ $jadwal->mataPelajaran->nama_mapel }}</div>
                                                <small class="text-muted">{{ $jadwal->mataPelajaran->kode_mapel }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $jadwal->kelas->nama_kelas }}</span>
                                        </td>
                                        <td>
                                            <small>{{ $jadwal->keterangan ?: '-' }}</small>
                                        </td>
                                        <td>
                                            @if($jadwal->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button wire:click="edit({{ $jadwal->id }})" 
                                                        class="btn btn-sm btn-soft-primary" title="Edit">
                                                    <i class="ri-pencil-fill"></i>
                                                </button>
                                                <button wire:click="delete({{ $jadwal->id }})" 
                                                        class="btn btn-sm btn-soft-danger" title="Hapus"
                                                        onclick="return confirm('Yakin ingin menghapus jadwal ini?')">
                                                    <i class="ri-delete-bin-fill"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ri-file-list-3-line fs-1 text-muted mb-2"></i>
                                                <h5 class="text-muted">Belum ada jadwal yang tersedia</h5>
                                                <p class="text-muted mb-0">Silakan tambah jadwal baru</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($jadwals->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Menampilkan {{ $jadwals->firstItem() ?? 0 }} sampai {{ $jadwals->lastItem() ?? 0 }} 
                                dari {{ $jadwals->total() }} jadwal
                            </div>
                            {{ $jadwals->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-calendar-plus"></i>
                            {{ $isEdit ? 'Edit Jadwal' : 'Tambah Jadwal Baru' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tahun Pelajaran <span class="text-danger">*</span></label>
                                    <select wire:model="tahun_pelajaran_id" class="form-select @error('tahun_pelajaran_id') is-invalid @enderror">
                                        <option value="">Pilih Tahun Pelajaran</option>
                                        @foreach($tahunPelajarans as $tp)
                                            <option value="{{ $tp->id }}">{{ $tp->nama_tahun_pelajaran }}</option>
                                        @endforeach
                                    </select>
                                    @error('tahun_pelajaran_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Guru <span class="text-danger">*</span></label>
                                    <select wire:model="guru_id" class="form-select @error('guru_id') is-invalid @enderror">
                                        <option value="">Pilih Guru</option>
                                        @foreach($gurus as $guru)
                                            <option value="{{ $guru->id }}">{{ $guru->nama_guru }} ({{ $guru->nip }})</option>
                                        @endforeach
                                    </select>
                                    @error('guru_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                                    <select wire:model="mata_pelajaran_id" class="form-select @error('mata_pelajaran_id') is-invalid @enderror">
                                        <option value="">Pilih Mata Pelajaran</option>
                                        @foreach($mataPelajarans as $mapel)
                                            <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }} ({{ $mapel->kode_mapel }})</option>
                                        @endforeach
                                    </select>
                                    @error('mata_pelajaran_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kelas <span class="text-danger">*</span></label>
                                    <select wire:model="kelas_id" class="form-select @error('kelas_id') is-invalid @enderror">
                                        <option value="">Pilih Kelas</option>
                                        @foreach($kelas as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Hari <span class="text-danger">*</span></label>
                                    <select wire:model="hari" class="form-select @error('hari') is-invalid @enderror">
                                        <option value="">Pilih Hari</option>
                                        @foreach($hariOptions as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('hari')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Jam Ke <span class="text-danger">*</span></label>
                                    <select wire:model="jam_ke" class="form-select @error('jam_ke') is-invalid @enderror">
                                        <option value="">Pilih Jam Ke</option>
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}">Jam ke-{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('jam_ke')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch mt-2">
                                        <input wire:model="is_active" class="form-check-input" type="checkbox" id="is_active">
                                        <label class="form-check-label" for="is_active">
                                            {{ $is_active ? 'Aktif' : 'Nonaktif' }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                    <input type="time" wire:model="jam_mulai" 
                                           class="form-control @error('jam_mulai') is-invalid @enderror">
                                    @error('jam_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                    <input type="time" wire:model="jam_selesai" 
                                           class="form-control @error('jam_selesai') is-invalid @enderror">
                                    @error('jam_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea wire:model="keterangan" class="form-control @error('keterangan') is-invalid @enderror" 
                                          rows="3" placeholder="Keterangan tambahan (opsional)"></textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" wire:click="closeModal">
                            <i class="ri-close-line me-1"></i> Batal
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="save">
                            <i class="ri-save-line me-1"></i> {{ $isEdit ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('styles')
        <style>
.search-box .search-icon {
    position: absolute;
    top: 50%;
    right: 12px;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 16px;
}

.search-box input {
    padding-right: 40px;
}

.avatar {
    width: 32px;
    height: 32px;
}

.avatar-initial {
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: white;
    font-size: 14px;
}

.form-check-input:checked {
    background-color: #4e73df;
    border-color: #4e73df;
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
    color: #5a5c69;
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.75rem;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.btn-soft-primary {
    color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.1);
    border-color: transparent;
}

.btn-soft-primary:hover {
    color: #fff;
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.btn-soft-danger {
    color: #dc3545;
    background-color: rgba(220, 53, 69, 0.1);
    border-color: transparent;
}

.btn-soft-danger:hover {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}
</style>
    @endpush
</div>


