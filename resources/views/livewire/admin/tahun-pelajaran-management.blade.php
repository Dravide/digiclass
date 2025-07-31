<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-calendar-alt me-2 text-white"></i>Manajemen Tahun Pelajaran
                    </h5>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#tahunPelajaranModal" wire:click="resetForm">
                        <i class="fas fa-plus me-1"></i>Tambah Tahun Pelajaran
                    </button>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Cari tahun pelajaran..." wire:model.live="search">
                            </div>
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
                                    <th wire:click="sortBy('nama_tahun_pelajaran')" style="cursor: pointer;">
                                        Nama Tahun Pelajaran
                                        @if($sortField === 'nama_tahun_pelajaran')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </th>
                                    <th wire:click="sortBy('tanggal_mulai')" style="cursor: pointer;">
                                        Periode
                                        @if($sortField === 'tanggal_mulai')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </th>
                                    <th>Status</th>
                                    <th>Jumlah Kelas</th>
                                    <th>Keterangan</th>
                                    <th width="200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tahunPelajarans as $tahunPelajaran)
                                    <tr>
                                        <td>
                                            <strong>{{ $tahunPelajaran->nama_tahun_pelajaran }}</strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $tahunPelajaran->tanggal_mulai->format('d M Y') }} - 
                                                {{ $tahunPelajaran->tanggal_selesai->format('d M Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge {{ $tahunPelajaran->badge_class }}">
                                                {{ $tahunPelajaran->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $tahunPelajaran->kelas_count ?? $tahunPelajaran->kelas()->count() }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $tahunPelajaran->keterangan ?: '-' }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if(!$tahunPelajaran->is_active)
                                                    <button class="btn btn-success btn-sm" 
                                                            onclick="confirmActivate({{ $tahunPelajaran->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                <button class="btn btn-warning btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#tahunPelajaranModal"
                                                        wire:click="edit({{ $tahunPelajaran->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm" 
                                                        onclick="confirmDelete({{ $tahunPelajaran->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                                                <p>Belum ada data tahun pelajaran</p>
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
                            Menampilkan {{ $tahunPelajarans->firstItem() ?? 0 }} - {{ $tahunPelajarans->lastItem() ?? 0 }} 
                            dari {{ $tahunPelajarans->total() }} data
                        </div>
                        {{ $tahunPelajarans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="tahunPelajaranModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $isEditing ? 'Edit Tahun Pelajaran' : 'Tambah Tahun Pelajaran' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form wire:submit="{{ $isEditing ? 'update' : 'store' }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Tahun Pelajaran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_tahun_pelajaran') is-invalid @enderror" 
                                   wire:model="nama_tahun_pelajaran" placeholder="Contoh: 2024/2025">
                            @error('nama_tahun_pelajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                           wire:model="tanggal_mulai">
                                    @error('tanggal_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                           wire:model="tanggal_selesai">
                                    @error('tanggal_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                      wire:model="keterangan" rows="3" 
                                      placeholder="Keterangan tambahan (opsional)"></textarea>
                            @error('keterangan')
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
        Livewire.on('tahun-pelajaran-created', () => {
            bootstrap.Modal.getInstance(document.getElementById('tahunPelajaranModal')).hide();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Tahun pelajaran berhasil ditambahkan!',
                timer: 2000,
                showConfirmButton: false
            });
        });
        
        Livewire.on('tahun-pelajaran-updated', () => {
            bootstrap.Modal.getInstance(document.getElementById('tahunPelajaranModal')).hide();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Tahun pelajaran berhasil diupdate!',
                timer: 2000,
                showConfirmButton: false
            });
        });
        
        Livewire.on('tahun-pelajaran-deleted', () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Tahun pelajaran berhasil dihapus!',
                timer: 2000,
                showConfirmButton: false
            });
        });
        
        Livewire.on('tahun-pelajaran-activated', () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Tahun pelajaran berhasil diaktifkan!',
                timer: 2000,
                showConfirmButton: false
            });
        });
        
        Livewire.on('tahun-pelajaran-error', (event) => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: event.message
            });
        });
    });
    
    // Confirmation dialogs
    function confirmDelete(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Tahun pelajaran ini akan dihapus permanen!',
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
    
    function confirmActivate(id) {
        Swal.fire({
            title: 'Aktifkan Tahun Pelajaran?',
            text: 'Tahun pelajaran yang sedang aktif akan dinonaktifkan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Aktifkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('activate', id);
            }
        });
    }
</script>
@endpush
