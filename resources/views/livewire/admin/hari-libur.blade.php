<div>
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="mdi mdi-calendar-remove"></i> Manajemen Hari Libur
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Hari Libur</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="header-title mb-0">
                            <i class="mdi mdi-calendar-multiple"></i> Daftar Hari Libur
                        </h4>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-info btn-sm" wire:click="sinkronisasiApi" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="sinkronisasiApi">
                                    <i class="mdi mdi-sync"></i> Sinkronisasi API
                                </span>
                                <span wire:loading wire:target="sinkronisasiApi">
                                    <i class="mdi mdi-loading mdi-spin"></i> Sinkronisasi...
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" wire:click="bukaModal">
                                <i class="mdi mdi-plus"></i> Tambah Hari Libur
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Pencarian</label>
                                <input type="text" class="form-control" wire:model.live.debounce.300ms="search" placeholder="Cari keterangan atau tanggal...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Tahun</label>
                                <select class="form-select" wire:model.live="filterTahun">
                                    <option value="">Semua Tahun</option>
                                    @foreach($availableYears as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-select" wire:model.live="filterStatus">
                                    <option value="">Semua Status</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="button" class="btn btn-outline-secondary" wire:click="$set('search', ''); $set('filterTahun', ''); $set('filterStatus', '')">
                                        <i class="mdi mdi-refresh"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hariLibur as $index => $libur)
                                    <tr>
                                        <td>{{ $hariLibur->firstItem() + $index }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $libur->tanggal_display }}</div>
                                            <small class="text-muted">{{ Carbon\Carbon::parse($libur->tanggal)->format('d M Y') }}</small>
                                        </td>
                                        <td>{{ $libur->keterangan }}</td>
                                        <td>
                                            @if($libur->is_cuti)
                                                <span class="badge bg-warning">Cuti Bersama</span>
                                            @else
                                                <span class="badge bg-info">Hari Libur</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($libur->is_aktif)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary" wire:click="editHariLibur({{ $libur->id }})" title="Edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-{{ $libur->is_aktif ? 'warning' : 'success' }}" wire:click="toggleStatus({{ $libur->id }})" title="{{ $libur->is_aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="mdi mdi-{{ $libur->is_aktif ? 'eye-off' : 'eye' }}"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" wire:click="hapusHariLibur({{ $libur->id }})" wire:confirm="Apakah Anda yakin ingin menghapus hari libur ini?" title="Hapus">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="mdi mdi-calendar-remove h1"></i>
                                                <p class="mt-2">Tidak ada data hari libur</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($hariLibur->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $hariLibur->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="mdi mdi-calendar-plus"></i> {{ $modalTitle }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="tutupModal"></button>
                    </div>
                    <form wire:submit="simpanHariLibur">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" wire:model="tanggal">
                                        @error('tanggal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tanggal Display <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tanggal_display') is-invalid @enderror" wire:model="tanggal_display" placeholder="Contoh: 1 Januari 2025">
                                        @error('tanggal_display')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" wire:model="keterangan" rows="3" placeholder="Masukkan keterangan hari libur"></textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" wire:model="is_cuti" id="is_cuti">
                                        <label class="form-check-label" for="is_cuti">
                                            Cuti Bersama
                                        </label>
                                        <small class="form-text text-muted d-block">Centang jika ini adalah cuti bersama</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" wire:model="is_aktif" id="is_aktif">
                                        <label class="form-check-label" for="is_aktif">
                                            Status Aktif
                                        </label>
                                        <small class="form-text text-muted d-block">Centang untuk mengaktifkan hari libur</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="tutupModal">Batal</button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="simpanHariLibur">
                                    <i class="mdi mdi-content-save"></i> Simpan
                                </span>
                                <span wire:loading wire:target="simpanHariLibur">
                                    <i class="mdi mdi-loading mdi-spin"></i> Menyimpan...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>