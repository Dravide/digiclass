<div>
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Pengaturan Jam Presensi</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pengaturan Jam Presensi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-check-line me-2"></i>{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters and Actions -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Cari Pengaturan</label>
                            <input type="text" class="form-control" wire:model.live="search" placeholder="Cari berdasarkan hari atau keterangan...">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-primary me-2" wire:click="bukaModalTambah">
                                <i class="ri-add-line me-1"></i>Tambah Pengaturan
                            </button>
                            <button type="button" class="btn btn-success" wire:click="buatPengaturanDefault">
                                <i class="ri-settings-line me-1"></i>Buat Default
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-primary rounded-circle fs-3">
                                <i class="ri-calendar-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Total Hari</p>
                            <h4 class="mb-0">{{ $jamPresensiList->total() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success rounded-circle fs-3">
                                <i class="ri-check-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Aktif</p>
                            <h4 class="mb-0">{{ $jamPresensiList->where('is_active', true)->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning rounded-circle fs-3">
                                <i class="ri-pause-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Nonaktif</p>
                            <h4 class="mb-0">{{ $jamPresensiList->where('is_active', false)->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info rounded-circle fs-3">
                                <i class="ri-time-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Hari Ini</p>
                            <h4 class="mb-0">{{ \App\Models\JamPresensi::getNamaHariIni() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Daftar Pengaturan Jam Presensi</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Jam Lembur</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jamPresensiList as $jamPresensi)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">{{ $jamPresensi->nama_hari }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary">
                                                {{ $jamPresensi->jam_masuk_mulai }} - {{ $jamPresensi->jam_masuk_selesai }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info">
                                                {{ $jamPresensi->jam_pulang_mulai }} - {{ $jamPresensi->jam_pulang_selesai }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($jamPresensi->jam_lembur_mulai && $jamPresensi->jam_lembur_selesai)
                                                <span class="badge bg-warning-subtle text-warning">
                                                    {{ $jamPresensi->jam_lembur_mulai }} - {{ $jamPresensi->jam_lembur_selesai }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($jamPresensi->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $jamPresensi->keterangan ?: '-' }}</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="ri-more-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <button class="dropdown-item" wire:click="editJamPresensi({{ $jamPresensi->id }})">
                                                            <i class="ri-pencil-line align-bottom me-2 text-muted"></i>Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item" wire:click="toggleStatus({{ $jamPresensi->id }})">
                                                            @if($jamPresensi->is_active)
                                                                <i class="ri-pause-line align-bottom me-2 text-muted"></i>Nonaktifkan
                                                            @else
                                                                <i class="ri-play-line align-bottom me-2 text-muted"></i>Aktifkan
                                                            @endif
                                                        </button>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button class="dropdown-item text-danger" wire:click="hapusJamPresensi({{ $jamPresensi->id }})" wire:confirm="Apakah Anda yakin ingin menghapus pengaturan ini?">
                                                            <i class="ri-delete-bin-line align-bottom me-2"></i>Hapus
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ri-time-line fs-1 text-muted mb-2"></i>
                                                <h5 class="text-muted">Belum ada pengaturan jam presensi</h5>
                                                <p class="text-muted mb-3">Klik tombol "Tambah Pengaturan" untuk menambah pengaturan baru</p>
                                                <button type="button" class="btn btn-primary" wire:click="bukaModalTambah">
                                                    <i class="ri-add-line me-1"></i>Tambah Pengaturan
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($jamPresensiList->hasPages())
                        <div class="d-flex justify-content-end mt-3">
                            {{ $jamPresensiList->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $modalTitle }}</h5>
                        <button type="button" class="btn-close" wire:click="tutupModal"></button>
                    </div>
                    <form wire:submit.prevent="simpanJamPresensi">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Hari <span class="text-danger">*</span></label>
                                        <select class="form-select" wire:model="nama_hari">
                                            <option value="">Pilih Hari</option>
                                            @foreach($daftarHari as $hari)
                                                <option value="{{ $hari }}">{{ $hari }}</option>
                                            @endforeach
                                        </select>
                                        @error('nama_hari')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" wire:model="is_active">
                                            <label class="form-check-label">Aktif</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jam Masuk Mulai <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" wire:model="jam_masuk_mulai">
                                        @error('jam_masuk_mulai')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jam Masuk Selesai <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" wire:model="jam_masuk_selesai">
                                        @error('jam_masuk_selesai')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jam Pulang Mulai <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" wire:model="jam_pulang_mulai">
                                        @error('jam_pulang_mulai')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jam Pulang Selesai <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" wire:model="jam_pulang_selesai">
                                        @error('jam_pulang_selesai')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jam Lembur Mulai</label>
                                        <input type="time" class="form-control" wire:model="jam_lembur_mulai">
                                        @error('jam_lembur_mulai')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Kosongkan jika tidak ada jam lembur</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jam Lembur Selesai</label>
                                        <input type="time" class="form-control" wire:model="jam_lembur_selesai">
                                        @error('jam_lembur_selesai')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Kosongkan jika tidak ada jam lembur</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea class="form-control" rows="3" wire:model="keterangan" placeholder="Masukkan keterangan (opsional)"></textarea>
                                @error('keterangan')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="tutupModal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <span wire:loading wire:target="simpanJamPresensi" class="spinner-border spinner-border-sm me-1"></span>
                                {{ $editingId > 0 ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>