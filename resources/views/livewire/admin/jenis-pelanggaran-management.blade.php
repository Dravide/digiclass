<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                        <li class="breadcrumb-item active">Jenis Pelanggaran</li>
                    </ol>
                </div>
                <h4 class="page-title">Manajemen Jenis Pelanggaran</h4>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Header Actions -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary" wire:click="openCreateModal">
                                <i class="mdi mdi-plus"></i> Tambah Jenis Pelanggaran
                            </button>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end gap-2">
                                <div class="d-flex align-items-center">
                                    <label class="me-2">Show:</label>
                                    <select wire:model.live="perPage" class="form-select form-select-sm" style="width: auto;">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                                <div class="position-relative">
                                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Cari jenis pelanggaran..." style="width: 250px;">
                                    <i class="mdi mdi-magnify position-absolute top-50 end-0 translate-middle-y me-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select wire:model.live="filterKategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select wire:model.live="filterTingkat" class="form-select">
                                <option value="">Semua Tingkat</option>
                                @foreach($tingkatOptions as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select wire:model.live="filterStatus" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-secondary" wire:click="resetFilters">
                                <i class="mdi mdi-refresh"></i> Reset Filter
                            </button>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th width="8%">Kode</th>
                                    <th width="15%">Kategori</th>
                                    <th width="25%">Nama Pelanggaran</th>
                                    <th width="20%">Deskripsi</th>
                                    <th width="8%">Poin</th>
                                    <th width="10%">Tingkat</th>
                                    <th width="8%">Status</th>
                                    <th width="6%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jenisPerPage as $jenis)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $jenis->kode_lengkap }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $jenis->kategoriPelanggaran->nama_kategori }}</small>
                                        </td>
                                        <td>{{ $jenis->nama_pelanggaran }}</td>
                                        <td>
                                            @if($jenis->deskripsi_pelanggaran)
                                                <small>{{ Str::limit($jenis->deskripsi_pelanggaran, 50) }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark">{{ $jenis->poin_pelanggaran }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $jenis->badge_color }}">{{ $jenis->tingkat_label }}</span>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                       {{ $jenis->is_active ? 'checked' : '' }}
                                                       wire:click="toggleStatus({{ $jenis->id }})">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="openEditModal({{ $jenis->id }})" title="Edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $jenis->id }})" title="Hapus">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="mdi mdi-database-search mdi-48px text-muted mb-2"></i>
                                                <p class="text-muted mb-0">Tidak ada data jenis pelanggaran</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($jenisPerPage->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <p class="text-muted mb-0">
                                    Menampilkan {{ $jenisPerPage->firstItem() }} sampai {{ $jenisPerPage->lastItem() }} dari {{ $jenisPerPage->total() }} data
                                </p>
                            </div>
                            <div>
                                {{ $jenisPerPage->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $editMode ? 'Edit Jenis Pelanggaran' : 'Tambah Jenis Pelanggaran' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kategori_pelanggaran_id" class="form-label">Kategori Pelanggaran <span class="text-danger">*</span></label>
                                        <select class="form-select @error('kategori_pelanggaran_id') is-invalid @enderror" 
                                                wire:model="kategori_pelanggaran_id" id="kategori_pelanggaran_id">
                                            <option value="">Pilih Kategori</option>
                                            @foreach($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                            @endforeach
                                        </select>
                                        @error('kategori_pelanggaran_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kode_pelanggaran" class="form-label">Kode Pelanggaran <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('kode_pelanggaran') is-invalid @enderror" 
                                               wire:model="kode_pelanggaran" id="kode_pelanggaran" placeholder="Contoh: P01">
                                        @error('kode_pelanggaran')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="nama_pelanggaran" class="form-label">Nama Pelanggaran <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_pelanggaran') is-invalid @enderror" 
                                       wire:model="nama_pelanggaran" id="nama_pelanggaran" placeholder="Contoh: Terlambat masuk kelas">
                                @error('nama_pelanggaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi_pelanggaran" class="form-label">Deskripsi Pelanggaran</label>
                                <textarea class="form-control @error('deskripsi_pelanggaran') is-invalid @enderror" 
                                          wire:model="deskripsi_pelanggaran" id="deskripsi_pelanggaran" rows="3" 
                                          placeholder="Deskripsi detail pelanggaran (opsional)"></textarea>
                                @error('deskripsi_pelanggaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="poin_pelanggaran" class="form-label">Poin Pelanggaran <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('poin_pelanggaran') is-invalid @enderror" 
                                               wire:model="poin_pelanggaran" id="poin_pelanggaran" min="1" max="500" placeholder="10">
                                        @error('poin_pelanggaran')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="tingkat_pelanggaran" class="form-label">Tingkat Pelanggaran <span class="text-danger">*</span></label>
                                        <select class="form-select @error('tingkat_pelanggaran') is-invalid @enderror" 
                                                wire:model="tingkat_pelanggaran" id="tingkat_pelanggaran">
                                            <option value="">Pilih Tingkat</option>
                                            @foreach($tingkatOptions as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('tingkat_pelanggaran')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" wire:model="is_active" id="is_active">
                                            <label class="form-check-label" for="is_active">
                                                {{ $is_active ? 'Aktif' : 'Tidak Aktif' }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                {{ $editMode ? 'Perbarui' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus jenis pelanggaran ini?</p>
                        <p class="text-muted small">Data yang sudah dihapus tidak dapat dikembalikan.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>