@section('title', 'Manajemen Tata Usaha')

<div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Tata Usaha</p>
                            <h4 class="mb-0">{{ $tataUsahas->total() }}</h4>
                        </div>
                        <div class="text-primary">
                            <i class="ri-user-settings-line font-size-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Aktif</p>
                            <h4 class="mb-0">{{ $tataUsahas->where('is_active', true)->count() }}</h4>
                        </div>
                        <div class="text-success">
                            <i class="ri-check-line font-size-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Tidak Aktif</p>
                            <h4 class="mb-0">{{ $tataUsahas->where('is_active', false)->count() }}</h4>
                        </div>
                        <div class="text-warning">
                            <i class="ri-pause-line font-size-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Jabatan Unik</p>
                            <h4 class="mb-0">{{ $tataUsahas->pluck('jabatan')->filter()->unique()->count() }}</h4>
                        </div>
                        <div class="text-info">
                            <i class="ri-briefcase-line font-size-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Data Tata Usaha</h4>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tataUsahaModal">
                                    <i class="ri-add-line align-middle me-1"></i> Tambah Tata Usaha
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="search-box">
                                <div class="position-relative">
                                    <input type="text" class="form-control" placeholder="Cari tata usaha..." wire:model.live="search">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" wire:model.live="filterActive">
                                <option value="">Semua Status</option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" wire:model.live="perPage">
                                <option value="10">10 per halaman</option>
                                <option value="25">25 per halaman</option>
                                <option value="50">50 per halaman</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-secondary w-100" wire:click="resetForm">
                                <i class="ri-refresh-line"></i> Reset
                            </button>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-nowrap table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th wire:click="sortBy('nama_tata_usaha')" style="cursor: pointer;">
                                        Nama
                                        @if($sortField === 'nama_tata_usaha')
                                            <i class="ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-s-line"></i>
                                        @endif
                                    </th>
                                    <th wire:click="sortBy('nip')" style="cursor: pointer;">
                                        NIP
                                        @if($sortField === 'nip')
                                            <i class="ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-s-line"></i>
                                        @endif
                                    </th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th wire:click="sortBy('jabatan')" style="cursor: pointer;">
                                        Jabatan
                                        @if($sortField === 'jabatan')
                                            <i class="ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-s-line"></i>
                                        @endif
                                    </th>
                                    <th>Bidang Tugas</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tataUsahas as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-3">
                                                    <span class="avatar-title rounded-circle bg-primary text-white font-size-16">
                                                        {{ strtoupper(substr($item->nama_tata_usaha, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">{{ $item->nama_tata_usaha }}</h5>
                                                    <p class="text-muted font-size-13 mb-0">ID: {{ $item->id }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-info">{{ $item->nip }}</span>
                                        </td>
                                        <td>{{ $item->email ?: '-' }}</td>
                                        <td>{{ $item->telepon ?: '-' }}</td>
                                        <td>
                                            <span class="badge badge-soft-primary">{{ $item->jabatan }}</span>
                                        </td>
                                        <td>{{ $item->bidang_tugas ?: '-' }}</td>
                                        <td>
                                            @if($item->is_active)
                                                <span class="badge badge-soft-success">Aktif</span>
                                            @else
                                                <span class="badge badge-soft-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        wire:click="edit({{ $item->id }})" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#tataUsahaModal"
                                                        data-bs-toggle="tooltip" 
                                                        title="Edit">
                                                    <i class="ri-edit-2-line"></i>
                                                </button>
                                                @if($item->email && !$item->user)
                                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                                            wire:click="generateAccount({{ $item->id }})"
                                                            data-bs-toggle="tooltip" 
                                                            title="Generate Akun">
                                                        <i class="ri-user-add-line"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="confirmDelete({{ $item->id }})"
                                                        data-bs-toggle="tooltip" 
                                                        title="Hapus">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ri-inbox-line font-size-48 text-muted mb-2"></i>
                                                <h5 class="text-muted">Tidak ada data tata usaha</h5>
                                                <p class="text-muted mb-0">Silakan tambahkan tata usaha baru</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($tataUsahas->hasPages())
                        <div class="row mt-4">
                            <div class="col-sm-6">
                                <div class="dataTables_info">
                                    Menampilkan {{ $tataUsahas->firstItem() }} sampai {{ $tataUsahas->lastItem() }} dari {{ $tataUsahas->total() }} entri
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dataTables_paginate paging_simple_numbers float-end">
                                    {{ $tataUsahas->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="tataUsahaModal" tabindex="-1" aria-labelledby="tataUsahaModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tataUsahaModalLabel">
                        {{ $isEditing ? 'Edit Tata Usaha' : 'Tambah Tata Usaha' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="resetForm"></button>
                </div>
                <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_tata_usaha" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_tata_usaha') is-invalid @enderror" 
                                           id="nama_tata_usaha" wire:model="nama_tata_usaha" placeholder="Nama lengkap tata usaha">
                                    @error('nama_tata_usaha')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nip" class="form-label">NIP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nip') is-invalid @enderror" 
                                           id="nip" wire:model="nip" placeholder="Nomor Induk Pegawai">
                                    @error('nip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" wire:model="email" placeholder="email@example.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telepon" class="form-label">Telepon <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('telepon') is-invalid @enderror" 
                                           id="telepon" wire:model="telepon" placeholder="08xxxxxxxxxx">
                                    @error('telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('jabatan') is-invalid @enderror" 
                                           id="jabatan" wire:model="jabatan" placeholder="Contoh: Kepala Tata Usaha">
                                    @error('jabatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Status</label>
                                    <select class="form-select @error('is_active') is-invalid @enderror" 
                                            id="is_active" wire:model="is_active">
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="bidang_tugas" class="form-label">Bidang Tugas</label>
                                    <textarea class="form-control @error('bidang_tugas') is-invalid @enderror" 
                                              id="bidang_tugas" wire:model="bidang_tugas" rows="3" 
                                              placeholder="Deskripsi bidang tugas dan tanggung jawab"></textarea>
                                    @error('bidang_tugas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetForm">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line align-middle me-1"></i>
                            {{ $isEditing ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // Confirmation for delete
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data tata usaha akan dihapus permanen!",
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

    // Livewire event listeners
    document.addEventListener('livewire:init', function () {
        Livewire.on('tata-usaha-created', function (message) {
            $('#tataUsahaModal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                timer: 3000,
                showConfirmButton: false
            });
        });

        Livewire.on('tata-usaha-updated', function (message) {
            $('#tataUsahaModal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                timer: 3000,
                showConfirmButton: false
            });
        });

        Livewire.on('tata-usaha-deleted', function (message) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                timer: 3000,
                showConfirmButton: false
            });
        });

        Livewire.on('tata-usaha-error', function (message) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message
            });
        });

        Livewire.on('tata-usaha-account-generated', function (message) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                timer: 5000,
                showConfirmButton: false
            });
        });
    });
</script>
@endpush