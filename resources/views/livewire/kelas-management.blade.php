@section('title', 'Manajemen Kelas')
@section('breadcrumb')
    <li class="breadcrumb-item active">Manajemen Kelas</li>
@endsection

<div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Kelas</p>
                            <h4 class="mb-0">{{ $kelas->total() }}</h4>
                        </div>
                        <div class="text-primary">
                            <i class="ri-building-line font-size-24"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Total Siswa</p>
                            <h4 class="mb-0">{{ $kelas->sum('siswa_count') }}</h4>
                        </div>
                        <div class="text-success">
                            <i class="ri-group-line font-size-24"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Kapasitas Total</p>
                            <h4 class="mb-0">{{ $kelas->sum('kapasitas') }}</h4>
                        </div>
                        <div class="text-info">
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
                            <p class="text-truncate font-size-14 mb-2">Sisa Kapasitas</p>
                            <h4 class="mb-0">{{ $kelas->sum('kapasitas') - $kelas->sum('siswa_count') }}</h4>
                        </div>
                        <div class="text-warning">
                            <i class="ri-user-add-line font-size-24"></i>
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
                            <h4 class="card-title mb-0">Data Kelas</h4>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kelasModal">
                                <i class="ri-add-line align-middle me-1"></i> Tambah Kelas
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="search-box">
                                <div class="position-relative">
                                    <input type="text" class="form-control" placeholder="Cari kelas..." wire:model.live="search">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" wire:model.live="filterTahunPelajaran">
                                <option value="">Semua Tahun</option>
                                @foreach($tahunPelajaranOptions as $tahun)
                                    <option value="{{ $tahun->id }}">{{ $tahun->nama_tahun_pelajaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <select class="form-select" wire:model.live="filterTingkat">
                                <option value="">Tingkat</option>
                                @foreach($tingkatOptions as $tingkat)
                                    <option value="{{ $tingkat }}">{{ $tingkat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" wire:model.live="filterJurusan">
                                <option value="">Semua Jurusan</option>
                                @foreach($jurusanOptions as $jurusan)
                                    <option value="{{ $jurusan }}">{{ $jurusan }}</option>
                                @endforeach
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
                                    <th wire:click="sortBy('nama_kelas')" style="cursor: pointer;">
                                        Nama Kelas
                                        @if($sortField === 'nama_kelas')
                                            <i class="ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-s-line"></i>
                                        @endif
                                    </th>
                                    <th wire:click="sortBy('tingkat')" style="cursor: pointer;">
                                        Tingkat
                                        @if($sortField === 'tingkat')
                                            <i class="ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-s-line"></i>
                                        @endif
                                    </th>
                                    <th>Jurusan</th>
                                    <th wire:click="sortBy('kapasitas')" style="cursor: pointer;">
                                        Kapasitas
                                        @if($sortField === 'kapasitas')
                                            <i class="ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-s-line"></i>
                                        @endif
                                    </th>
                                    <th>Jumlah Siswa</th>
                                    <th>Sisa Kapasitas</th>
                                    <th>Status</th>
                                    <th>Wali Kelas</th>
                                    <th>Link WA Grup</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kelas as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-3">
                                                    <span class="avatar-title rounded-circle bg-primary text-white font-size-16">
                                                        {{ strtoupper(substr($item->nama_kelas, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">{{ $item->nama_kelas }}</h5>
                                                    <p class="text-muted font-size-13 mb-0">ID: {{ $item->id }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-primary">{{ $item->tingkat }}</span>
                                        </td>
                                        <td>{{ $item->jurusan ?: '-' }}</td>
                                        <td>{{ $item->kapasitas }}</td>
                                        <td>
                                            <span class="badge badge-soft-info">{{ $item->siswa_count }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $sisa = $item->kapasitas - $item->siswa_count;
                                                $percentage = $item->kapasitas > 0 ? ($item->siswa_count / $item->kapasitas) * 100 : 0;
                                            @endphp
                                            <span class="badge badge-soft-{{ $sisa > 5 ? 'success' : ($sisa > 0 ? 'warning' : 'danger') }}">
                                                {{ $sisa }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $percentage = $item->kapasitas > 0 ? ($item->siswa_count / $item->kapasitas) * 100 : 0;
                                            @endphp
                                            @if($percentage >= 100)
                                                <span class="badge badge-soft-danger">Penuh</span>
                                            @elseif($percentage >= 80)
                                                <span class="badge badge-soft-warning">Hampir Penuh</span>
                                            @else
                                                <span class="badge badge-soft-success">Tersedia</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->guru)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs me-2">
                                                        <span class="avatar-title rounded-circle bg-info text-white font-size-12">
                                                            {{ strtoupper(substr($item->guru->nama_guru, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <span class="font-size-13">{{ $item->guru->nama_guru }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->link_wa)
                                                <a href="{{ $item->link_wa }}" target="_blank" class="btn btn-sm btn-success" title="Grup WhatsApp Kelas">
                                                    <i class="ri-whatsapp-line"></i>
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        wire:click="edit({{ $item->id }})" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#kelasModal"
                                                        data-bs-toggle="tooltip" 
                                                        title="Edit">
                                                    <i class="ri-edit-2-line"></i>
                                                </button>
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
                                        <td colspan="10" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ri-inbox-line font-size-48 text-muted mb-2"></i>
                                                <h5 class="text-muted">Tidak ada data kelas</h5>
                                                <p class="text-muted mb-0">Silakan tambahkan kelas baru</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($kelas->hasPages())
                        <div class="row mt-4">
                            <div class="col-sm-6">
                                <div class="dataTables_info">
                                    Menampilkan {{ $kelas->firstItem() }} sampai {{ $kelas->lastItem() }} dari {{ $kelas->total() }} entri
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dataTables_paginate paging_simple_numbers float-end">
                                    {{ $kelas->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="kelasModal" tabindex="-1" aria-labelledby="kelasModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kelasModalLabel">
                        {{ $isEditing ? 'Edit Kelas' : 'Tambah Kelas' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="resetForm"></button>
                </div>
                <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_kelas" class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_kelas') is-invalid @enderror" 
                                           id="nama_kelas" wire:model="nama_kelas" placeholder="Contoh: X-IPA-1">
                                    @error('nama_kelas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tingkat" class="form-label">Tingkat <span class="text-danger">*</span></label>
                                    <select class="form-select @error('tingkat') is-invalid @enderror" 
                                            id="tingkat" wire:model="tingkat">
                                        <option value="">Pilih Tingkat</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                    </select>
                                    @error('tingkat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jurusan" class="form-label">Jurusan</label>
                                    <select class="form-select @error('jurusan') is-invalid @enderror" 
                                            id="jurusan" wire:model="jurusan">
                                        <option value="">Pilih Jurusan (Opsional)</option>
                                        <option value="IPA">IPA</option>
                                        <option value="IPS">IPS</option>
                                        <option value="Bahasa">Bahasa</option>
                                        <option value="Umum">Umum</option>
                                    </select>
                                    @error('jurusan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kapasitas" class="form-label">Kapasitas <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('kapasitas') is-invalid @enderror" 
                                           id="kapasitas" wire:model="kapasitas" min="1" max="50" placeholder="30">
                                    @error('kapasitas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Maksimal 50 siswa per kelas</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tahun_pelajaran_id" class="form-label">Tahun Pelajaran <span class="text-danger">*</span></label>
                                    <select class="form-select @error('tahun_pelajaran_id') is-invalid @enderror" 
                                            id="tahun_pelajaran_id" wire:model="tahun_pelajaran_id">
                                        <option value="">Pilih Tahun Pelajaran</option>
                                        @foreach($tahunPelajaranOptions as $tahun)
                                            <option value="{{ $tahun->id }}">{{ $tahun->nama_tahun_pelajaran }}</option>
                                        @endforeach
                                    </select>
                                    @error('tahun_pelajaran_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="link_wa" class="form-label">Link WhatsApp Grup</label>
                                    <input type="url" class="form-control @error('link_wa') is-invalid @enderror" 
                                           id="link_wa" wire:model="link_wa" placeholder="https://chat.whatsapp.com/...">
                                    @error('link_wa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Link grup WhatsApp untuk kelas (opsional)</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="guru_id" class="form-label">Wali Kelas</label>
                                    <select class="form-select @error('guru_id') is-invalid @enderror" 
                                            id="guru_id" wire:model="guru_id">
                                        <option value="">Pilih Wali Kelas (Opsional)</option>
                                        @foreach($guruList as $guru)
                                            <option value="{{ $guru->id }}">{{ $guru->nama_guru }}</option>
                                        @endforeach
                                    </select>
                                    @error('guru_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Pilih guru yang akan menjadi wali kelas</div>
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

    <!-- Toast Container -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1200">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="ri-notification-line me-2"></i>
                <strong class="me-auto">Notifikasi</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                <!-- Message will be inserted here -->
            </div>
        </div>
    </div>

    <style>
        .search-box .search-icon {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            color: #74788d;
        }
        
        .avatar-title {
            align-items: center;
            display: flex;
            font-weight: 500;
            height: 100%;
            justify-content: center;
            width: 100%;
        }
        
        .avatar-xs {
            height: 2rem;
            width: 2rem;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        
        .badge-soft-primary {
            color: #556ee6;
            background-color: rgba(85, 110, 230, 0.1);
        }
        
        .badge-soft-info {
            color: #50a5f1;
            background-color: rgba(80, 165, 241, 0.1);
        }
        
        .badge-soft-success {
            color: #34c38f;
            background-color: rgba(52, 195, 143, 0.1);
        }
        
        .badge-soft-warning {
            color: #f1b44c;
            background-color: rgba(241, 180, 76, 0.1);
        }
        
        .badge-soft-danger {
            color: #f46a6a;
            background-color: rgba(244, 106, 106, 0.1);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Toast configuration
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // Listen for Livewire events
            document.addEventListener('livewire:init', () => {
                Livewire.on('kelas-created', (event) => {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('kelasModal'));
                    if (modal) {
                        modal.hide();
                    }
                    
                    // Show toast
                    Toast.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: event[0] || 'Kelas berhasil ditambahkan!'
                    });
                });

                Livewire.on('kelas-updated', (event) => {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('kelasModal'));
                    if (modal) {
                        modal.hide();
                    }
                    
                    // Show toast
                    Toast.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: event[0] || 'Kelas berhasil diperbarui!'
                    });
                });

                Livewire.on('kelas-deleted', (event) => {
                    Toast.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: event[0] || 'Kelas berhasil dihapus!'
                    });
                });

                Livewire.on('kelas-error', (event) => {
                    Toast.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: event[0] || 'Terjadi kesalahan!'
                    });
                });
            });
        });

        function confirmDelete(kelasId) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus kelas ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menghapus...',
                        html: '<div class="spinner-border text-danger" role="status"><span class="visually-hidden">Loading...</span></div>',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false
                    });
                    
                    // Call Livewire method
                    @this.delete(kelasId);
                }
            });
        }
    </script>
</div>
