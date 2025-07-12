<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-book me-2"></i>Manajemen Mata Pelajaran
                    </h5>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#mataPelajaranModal" wire:click="resetForm">
                        <i class="fas fa-plus me-1"></i>Tambah Mata Pelajaran
                    </button>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Cari mata pelajaran..." wire:model.live="search">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="filterKategori">
                                <option value="">Semua Kategori</option>
                                <option value="wajib">Wajib</option>
                                <option value="pilihan">Pilihan</option>
                                <option value="muatan_lokal">Muatan Lokal</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" wire:model.live="filterStatus">
                                <option value="">Semua Status</option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="perPage">
                                <option value="5">5 per halaman</option>
                                <option value="10">10 per halaman</option>
                                <option value="25">25 per halaman</option>
                                <option value="50">50 per halaman</option>
                            </select>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th wire:click="sortBy('kode_mapel')" style="cursor: pointer;">
                                        Kode
                                        @if($sortField === 'kode_mapel')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </th>
                                    <th wire:click="sortBy('nama_mapel')" style="cursor: pointer;">
                                        Nama Mata Pelajaran
                                        @if($sortField === 'nama_mapel')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </th>
                                    <th wire:click="sortBy('kategori')" style="cursor: pointer;">
                                        Kategori
                                        @if($sortField === 'kategori')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </th>
                                    <th wire:click="sortBy('jam_pelajaran')" style="cursor: pointer;">
                                        Jam Pelajaran
                                        @if($sortField === 'jam_pelajaran')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </th>
                                    <th>Status</th>
                                    <th>Deskripsi</th>
                                    <th width="200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mataPelajarans as $mataPelajaran)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">{{ $mataPelajaran->kode_mapel }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $mataPelajaran->nama_mapel }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge {{ $mataPelajaran->kategori === 'wajib' ? 'bg-primary' : ($mataPelajaran->kategori === 'pilihan' ? 'bg-info' : 'bg-warning') }}">
                                                {{ $mataPelajaran->kategori_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $mataPelajaran->jam_pelajaran }} JP</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $mataPelajaran->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $mataPelajaran->status_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($mataPelajaran->deskripsi, 50) ?: '-' }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-{{ $mataPelajaran->is_active ? 'warning' : 'success' }} btn-sm" 
                                                        onclick="confirmToggleStatus({{ $mataPelajaran->id }}, '{{ $mataPelajaran->is_active ? 'nonaktifkan' : 'aktifkan' }}')">
                                                    <i class="fas fa-{{ $mataPelajaran->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                </button>
                                                <button class="btn btn-primary btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#mataPelajaranModal"
                                                        wire:click="edit({{ $mataPelajaran->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm" 
                                                        onclick="confirmDelete({{ $mataPelajaran->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-book-open fa-3x mb-3"></i>
                                                <p>Belum ada data mata pelajaran</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Menampilkan {{ $mataPelajarans->firstItem() ?? 0 }} - {{ $mataPelajarans->lastItem() ?? 0 }} 
                            dari {{ $mataPelajarans->total() }} data
                        </div>
                        {{ $mataPelajarans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="mataPelajaranModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $isEditing ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form wire:submit="{{ $isEditing ? 'update' : 'store' }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kode Mata Pelajaran <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kode_mapel') is-invalid @enderror" 
                                           wire:model="kode_mapel" placeholder="Contoh: MTK" maxlength="10">
                                    @error('kode_mapel')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jam Pelajaran <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('jam_pelajaran') is-invalid @enderror" 
                                           wire:model="jam_pelajaran" min="1" max="10">
                                    @error('jam_pelajaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_mapel') is-invalid @enderror" 
                                   wire:model="nama_mapel" placeholder="Contoh: Matematika">
                            @error('nama_mapel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <select class="form-select @error('kategori') is-invalid @enderror" wire:model="kategori">
                                        <option value="wajib">Wajib</option>
                                        <option value="pilihan">Pilihan</option>
                                        <option value="muatan_lokal">Muatan Lokal</option>
                                    </select>
                                    @error('kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model="is_active" id="statusSwitch">
                                        <label class="form-check-label" for="statusSwitch">
                                            {{ $is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      wire:model="deskripsi" rows="3" 
                                      placeholder="Deskripsi mata pelajaran (opsional)"></textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            {{ $isEditing ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // SweetAlert2 notifications
    document.addEventListener('livewire:init', () => {
        Livewire.on('mata-pelajaran-created', (event) => {
            bootstrap.Modal.getInstance(document.getElementById('mataPelajaranModal')).hide();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: event[0],
                timer: 2000,
                showConfirmButton: false
            });
        });
        
        Livewire.on('mata-pelajaran-updated', (event) => {
            bootstrap.Modal.getInstance(document.getElementById('mataPelajaranModal')).hide();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: event[0],
                timer: 2000,
                showConfirmButton: false
            });
        });
        
        Livewire.on('mata-pelajaran-deleted', (event) => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: event[0],
                timer: 2000,
                showConfirmButton: false
            });
        });
        
        Livewire.on('mata-pelajaran-error', (event) => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: event[0]
            });
        });
    });
    
    // Confirmation dialogs
    function confirmDelete(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Mata pelajaran ini akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('delete', id);
            }
        });
    }
    
    function confirmToggleStatus(id, action) {
        Swal.fire({
            title: `${action.charAt(0).toUpperCase() + action.slice(1)} Mata Pelajaran?`,
            text: `Mata pelajaran akan di${action}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: action === 'aktifkan' ? '#28a745' : '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Ya, ${action.charAt(0).toUpperCase() + action.slice(1)}!`,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('toggleStatus', id);
            }
        });
    }
</script>
@endpush
