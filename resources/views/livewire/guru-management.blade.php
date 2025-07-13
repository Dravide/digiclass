@section('title', 'Manajemen Guru')

<div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Guru</p>
                            <h4 class="mb-0">{{ $gurus->total() }}</h4>
                        </div>
                        <div class="text-primary">
                            <i class="ri-user-star-line font-size-24"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Wali Kelas</p>
                            <h4 class="mb-0">{{ $gurus->where('is_wali_kelas', true)->count() }}</h4>
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
                            <p class="text-truncate font-size-14 mb-2">Siswa Dibimbing</p>
                            <h4 class="mb-0">{{ $gurus->where('is_wali_kelas', true)->sum('siswa_count') }}</h4>
                        </div>
                        <div class="text-info">
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
                            <p class="text-truncate font-size-14 mb-2">Mata Pelajaran</p>
                            <h4 class="mb-0">{{ $gurus->pluck('mata_pelajaran')->filter()->unique()->count() }}</h4>
                        </div>
                        <div class="text-warning">
                            <i class="ri-pause-line font-size-24"></i>
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
                            <h4 class="card-title mb-0">Data Guru</h4>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success" wire:click="openImportModal">
                                    <i class="ri-file-excel-2-line align-middle me-1"></i> Import Data
                                </button>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#guruModal">
                                    <i class="ri-add-line align-middle me-1"></i> Tambah Guru
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
                                    <input type="text" class="form-control" placeholder="Cari guru..." wire:model.live="search">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" wire:model.live="filterWaliKelas">
                                <option value="">Semua Guru</option>
                                <option value="1">Wali Kelas</option>
                                <option value="0">Bukan Wali Kelas</option>
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
                                    <th wire:click="sortBy('nama_guru')" style="cursor: pointer;">
                                        Nama Guru
                                        @if($sortField === 'nama_guru')
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
                                    <th>Mata Pelajaran</th>
                                    <th>Wali Kelas</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($gurus as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-3">
                                                    <span class="avatar-title rounded-circle bg-primary text-white font-size-16">
                                                        {{ strtoupper(substr($item->nama_guru, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">{{ $item->nama_guru }}</h5>
                                                    <p class="text-muted font-size-13 mb-0">ID: {{ $item->id }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-info">{{ $item->nip }}</span>
                                        </td>
                                        <td>{{ $item->email ?: '-' }}</td>
                                        <td>{{ $item->telepon ?: '-' }}</td>
                                        <td>{{ $item->mataPelajaran ? $item->mataPelajaran->nama_mapel : '-' }}</td>
                                        <td>
                                            @if($item->is_wali_kelas)
                                                <span class="badge badge-soft-success">Ya</span>
                                            @else
                                                <span class="badge badge-soft-secondary">Tidak</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->is_wali_kelas)
                                                <span class="badge badge-soft-primary">{{ $item->siswa_count }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        wire:click="edit({{ $item->id }})" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#guruModal"
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
                                        <td colspan="8" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ri-inbox-line font-size-48 text-muted mb-2"></i>
                                                <h5 class="text-muted">Tidak ada data guru</h5>
                                                <p class="text-muted mb-0">Silakan tambahkan guru baru</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($gurus->hasPages())
                        <div class="row mt-4">
                            <div class="col-sm-6">
                                <div class="dataTables_info">
                                    Menampilkan {{ $gurus->firstItem() }} sampai {{ $gurus->lastItem() }} dari {{ $gurus->total() }} entri
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dataTables_paginate paging_simple_numbers float-end">
                                    {{ $gurus->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="guruModal" tabindex="-1" aria-labelledby="guruModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="guruModalLabel">
                        {{ $isEditing ? 'Edit Guru' : 'Tambah Guru' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="resetForm"></button>
                </div>
                <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_guru" class="form-label">Nama Guru <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_guru') is-invalid @enderror" 
                                           id="nama_guru" wire:model="nama_guru" placeholder="Nama lengkap guru">
                                    @error('nama_guru')
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
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" wire:model="email" placeholder="email@example.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telepon" class="form-label">Telepon</label>
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
                                    <label for="mata_pelajaran_id" class="form-label">Mata Pelajaran</label>
                                    <select class="form-select @error('mata_pelajaran_id') is-invalid @enderror" 
                                            id="mata_pelajaran_id" wire:model="mata_pelajaran_id">
                                        <option value="">Pilih Mata Pelajaran</option>
                                        @foreach($mataPelajaranList as $mapel)
                                            <option value="{{ $mapel->id }}">{{ $mapel->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                    @error('mata_pelajaran_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_wali_kelas" class="form-label">Wali Kelas</label>
                                    <select class="form-select @error('is_wali_kelas') is-invalid @enderror" 
                                            id="is_wali_kelas" wire:model="is_wali_kelas">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                    @error('is_wali_kelas')
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

    <!-- Import Modal -->
    @if($showImportModal)
    <div class="modal fade show" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true" style="display: block; background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">
                        <i class="ri-file-excel-2-line me-2"></i>Import Data Guru
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeImportModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($importProgress == 0)
                        <!-- File Upload Section -->
                        <div class="mb-4">
                            <div class="alert alert-info">
                                <h6 class="alert-heading"><i class="ri-information-line me-2"></i>Informasi Import</h6>
                                <p class="mb-2">File yang akan diimport harus memiliki kolom berikut:</p>
                                <ul class="mb-2">
                                     <li><strong>nama_guru</strong> - Nama lengkap guru</li>
                                     <li><strong>nip</strong> - Nomor Induk Pegawai (harus berupa angka)</li>
                                     <li><strong>email</strong> - Alamat email guru</li>
                                     <li><strong>telepon</strong> - Nomor telepon guru (harus berupa angka, tanpa tanda +)</li>
                                 </ul>
                                <p class="mb-0"><strong>Catatan:</strong> Mata pelajaran dan status wali kelas akan diatur secara manual setelah import.</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="importFile" class="form-label">Pilih File Excel/CSV</label>
                            <input type="file" class="form-control @error('importFile') is-invalid @enderror" 
                                   id="importFile" wire:model="importFile" 
                                   accept=".xlsx,.xls,.csv">
                            @error('importFile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Format yang didukung: .xlsx, .xls, .csv (Maksimal 2MB)</div>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-primary" wire:click="downloadTemplate">
                                <i class="ri-download-line me-1"></i>Download Template
                            </button>
                        </div>
                    @else
                        <!-- Progress Section -->
                        <div class="text-center">
                            <div class="mb-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <h6>{{ $importStatus }}</h6>
                            <div class="progress mb-3">
                                <div class="progress-bar" role="progressbar" style="width: {{ $importProgress }}%" 
                                     aria-valuenow="{{ $importProgress }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $importProgress }}%
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($importProgress == 100 && $importedCount > 0)
                        <div class="alert alert-success">
                            <h6 class="alert-heading"><i class="ri-check-line me-2"></i>Import Berhasil!</h6>
                            <p class="mb-0">{{ $importedCount }} data guru berhasil diimport.</p>
                        </div>
                    @endif

                    @if(!empty($importErrors))
                        <div class="alert alert-warning">
                            <h6 class="alert-heading"><i class="ri-alert-line me-2"></i>Peringatan</h6>
                            <p>Beberapa data mengalami masalah:</p>
                            <ul class="mb-0">
                                @foreach($importErrors as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    @if($importProgress == 0)
                        <button type="button" class="btn btn-secondary" wire:click="closeImportModal">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="importData" 
                                @if(!$importFile) disabled @endif>
                            <i class="ri-upload-line me-1"></i>Import Data
                        </button>
                    @elseif($importProgress == 100)
                        <button type="button" class="btn btn-primary" wire:click="closeImportModal">
                            <i class="ri-check-line me-1"></i>Selesai
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

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
        
        .badge-soft-secondary {
            color: #74788d;
            background-color: rgba(116, 120, 141, 0.1);
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
            Livewire.on('guru-created', (message) => {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('guruModal'));
                if (modal) {
                    modal.hide();
                }
                
                // Show toast
                Toast.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: message
                });
            });

            Livewire.on('guru-updated', (message) => {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('guruModal'));
                if (modal) {
                    modal.hide();
                }
                
                // Show toast
                Toast.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: message
                });
            });

            Livewire.on('guru-deleted', (message) => {
                Toast.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: message
                });
            });

            Livewire.on('guru-error', (message) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: message
                });
            });

            Livewire.on('guru-imported', (message) => {
                Toast.fire({
                    icon: 'success',
                    title: 'Import Berhasil!',
                    text: message
                });
            });

            Livewire.on('close-import-modal-delayed', () => {
                setTimeout(() => {
                    @this.closeImportModal();
                }, 2000);
            });
        });

        function confirmDelete(guruId) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus guru ini?',
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
                    @this.delete(guruId);
                }
            });
        }
    </script>
</div>
