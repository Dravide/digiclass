<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                        <li class="breadcrumb-item active">Sanksi Pelanggaran</li>
                    </ol>
                </div>
                <h4 class="page-title">Manajemen Sanksi Pelanggaran</h4>
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
                                <i class="mdi mdi-plus"></i> Tambah Sanksi Pelanggaran
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
                                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Cari sanksi..." style="width: 250px;">
                                    <i class="mdi mdi-magnify position-absolute top-50 end-0 translate-middle-y me-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select wire:model.live="filterTingkatKelas" class="form-select">
                                <option value="">Semua Tingkat Kelas</option>
                                @foreach($tingkatKelasOptions as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select wire:model.live="filterPenanggungjawab" class="form-select">
                                <option value="">Semua Penanggungjawab</option>
                                @foreach($penanggungjawabOptions as $key => $label)
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
                                    <th width="10%">Tingkat Kelas</th>
                                    <th width="12%">Rentang Poin</th>
                                    <th width="25%">Jenis Sanksi</th>
                                    <th width="25%">Deskripsi</th>
                                    <th width="15%">Penanggungjawab</th>
                                    <th width="8%">Status</th>
                                    <th width="5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sanksiPerPage as $sanksi)
                                    <tr>
                                        <td>
                                            <span class="badge bg-info">{{ $sanksi->tingkat_kelas_label }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $sanksi->badge_color }}">{{ $sanksi->rentang_poin }}</span>
                                        </td>
                                        <td>{{ $sanksi->jenis_sanksi }}</td>
                                        <td>
                                            @if($sanksi->deskripsi_sanksi)
                                                <small>{{ Str::limit($sanksi->deskripsi_sanksi, 60) }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $sanksi->penanggungjawab }}</small>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                       {{ $sanksi->is_active ? 'checked' : '' }}
                                                       wire:click="toggleStatus({{ $sanksi->id }})">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="openEditModal({{ $sanksi->id }})" title="Edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $sanksi->id }})" title="Hapus">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="mdi mdi-database-search mdi-48px text-muted mb-2"></i>
                                                <p class="text-muted mb-0">Tidak ada data sanksi pelanggaran</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($sanksiPerPage->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <p class="text-muted mb-0">
                                    Menampilkan {{ $sanksiPerPage->firstItem() }} sampai {{ $sanksiPerPage->lastItem() }} dari {{ $sanksiPerPage->total() }} data
                                </p>
                            </div>
                            <div>
                                {{ $sanksiPerPage->links() }}
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
                            {{ $editMode ? 'Edit Sanksi Pelanggaran' : 'Tambah Sanksi Pelanggaran' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="tingkat_kelas" class="form-label">Tingkat Kelas <span class="text-danger">*</span></label>
                                        <select class="form-select @error('tingkat_kelas') is-invalid @enderror" 
                                                wire:model="tingkat_kelas" id="tingkat_kelas">
                                            <option value="">Pilih Tingkat Kelas</option>
                                            @foreach($tingkatKelasOptions as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('tingkat_kelas')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="poin_minimum" class="form-label">Poin Minimum <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('poin_minimum') is-invalid @enderror" 
                                               wire:model="poin_minimum" id="poin_minimum" min="1" placeholder="1">
                                        @error('poin_minimum')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="poin_maksimum" class="form-label">Poin Maksimum <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('poin_maksimum') is-invalid @enderror" 
                                               wire:model="poin_maksimum" id="poin_maksimum" min="1" placeholder="50">
                                        @error('poin_maksimum')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Gunakan 999999 untuk poin maksimum tanpa batas</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="jenis_sanksi" class="form-label">Jenis Sanksi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('jenis_sanksi') is-invalid @enderror" 
                                       wire:model="jenis_sanksi" id="jenis_sanksi" placeholder="Contoh: Teguran Lisan">
                                @error('jenis_sanksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi_sanksi" class="form-label">Deskripsi Sanksi</label>
                                <textarea class="form-control @error('deskripsi_sanksi') is-invalid @enderror" 
                                          wire:model="deskripsi_sanksi" id="deskripsi_sanksi" rows="3" 
                                          placeholder="Deskripsi detail sanksi yang akan diberikan (opsional)"></textarea>
                                @error('deskripsi_sanksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="penanggungjawab" class="form-label">Penanggungjawab <span class="text-danger">*</span></label>
                                        <select class="form-select @error('penanggungjawab') is-invalid @enderror" 
                                                wire:model="penanggungjawab" id="penanggungjawab">
                                            <option value="">Pilih Penanggungjawab</option>
                                            @foreach($penanggungjawabOptions as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('penanggungjawab')
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
                        <p>Apakah Anda yakin ingin menghapus sanksi pelanggaran ini?</p>
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