@section('title', 'Manajemen Perpustakaan')

<div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Siswa</p>
                            <h4 class="mb-0">{{ $perpustakaan->total() }}</h4>
                        </div>
                        <div class="text-primary">
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
                            <p class="text-truncate font-size-14 mb-2">Terpenuhi</p>
                            <h4 class="mb-0">{{ $perpustakaan->where('terpenuhi', true)->count() }}</h4>
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
                            <p class="text-truncate font-size-14 mb-2">Belum Terpenuhi</p>
                            <h4 class="mb-0">{{ $perpustakaan->where('terpenuhi', false)->count() }}</h4>
                        </div>
                        <div class="text-warning">
                            <i class="ri-close-line font-size-24"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Persentase</p>
                            <h4 class="mb-0">
                                @php
                                    $total = $perpustakaan->total();
                                    $terpenuhi = $perpustakaan->where('terpenuhi', true)->count();
                                    $percentage = $total > 0 ? round(($terpenuhi / $total) * 100, 1) : 0;
                                @endphp
                                {{ $percentage }}%
                            </h4>
                        </div>
                        <div class="text-info">
                            <i class="ri-pie-chart-line font-size-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Siswa Tanpa Data Perpustakaan</p>
                            <h4 class="mb-0">{{ $siswaWithoutPerpustakaan }}</h4>
                        </div>
                        <div class="text-danger">
                            <i class="ri-user-unfollow-line font-size-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-9 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-1">
                            <p class="text-muted mb-2">Aksi Cepat</p>
                            <div class="d-flex gap-2">
                                @if($siswaWithoutPerpustakaan > 0)
                                    <button type="button" class="btn btn-sm btn-success" 
                                            onclick="confirmBulkImportSiswa({{ $siswaWithoutPerpustakaan }})"
                                            data-bs-toggle="tooltip" 
                                            title="Import {{ $siswaWithoutPerpustakaan }} siswa yang belum memiliki data perpustakaan">
                                        <i class="ri-download-line me-1"></i> Import {{ $siswaWithoutPerpustakaan }} Siswa
                                    </button>
                                @endif
                                @if($perpustakaan->where('terpenuhi', false)->count() > 0)
                                    <button type="button" class="btn btn-sm btn-warning" 
                                            onclick="confirmBulkMarkTerpenuhi()"
                                            data-bs-toggle="tooltip" 
                                            title="Tandai {{ $perpustakaan->where('terpenuhi', false)->count() }} data sebagai terpenuhi">
                                        <i class="ri-check-double-line me-1"></i> Tandai {{ $perpustakaan->where('terpenuhi', false)->count() }} Terpenuhi
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="text-primary">
                            <i class="ri-flashlight-line font-size-24"></i>
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
                            <h4 class="card-title mb-0">Data Perpustakaan Siswa</h4>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex gap-2">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-success" 
                                            onclick="confirmBulkImportSiswa()"
                                            data-bs-toggle="tooltip" 
                                            title="Import semua siswa yang belum memiliki data perpustakaan">
                                        <i class="ri-download-line align-middle me-1"></i> Import Siswa
                                    </button>
                                    <button type="button" class="btn btn-warning" 
                                            onclick="confirmBulkMarkTerpenuhi()"
                                            data-bs-toggle="tooltip" 
                                            title="Tandai semua data perpustakaan sebagai terpenuhi">
                                        <i class="ri-check-double-line align-middle me-1"></i> Tandai Semua Terpenuhi
                                    </button>
                                </div>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#perpustakaanModal">
                                    <i class="ri-add-line align-middle me-1"></i> Tambah Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="search-box">
                                <div class="position-relative">
                                    <input type="text" class="form-control" placeholder="Cari siswa..." wire:model.live="search">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="filterTahunPelajaran">
                                <option value="">Semua Tahun Pelajaran</option>
                                @foreach($tahunPelajaranOptions as $tahun)
                                    <option value="{{ $tahun->id }}">{{ $tahun->nama_tahun_pelajaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" wire:model.live="filterTerpenuhi">
                                <option value="">Semua Status</option>
                                <option value="1">Terpenuhi</option>
                                <option value="0">Belum Terpenuhi</option>
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
                                    <th>Siswa</th>
                                    <th>Kelas</th>
                                    <th>Tahun Pelajaran</th>
                                    <th wire:click="sortBy('terpenuhi')" style="cursor: pointer;">
                                        Status
                                        @if($sortField === 'terpenuhi')
                                            <i class="ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-s-line"></i>
                                        @endif
                                    </th>
                                    <th>Keterangan</th>
                                    <th wire:click="sortBy('tanggal_pemenuhan')" style="cursor: pointer;">
                                        Tanggal Pemenuhan
                                        @if($sortField === 'tanggal_pemenuhan')
                                            <i class="ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-s-line"></i>
                                        @endif
                                    </th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perpustakaan as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-3">
                                                    <span class="avatar-title rounded-circle bg-primary text-white font-size-16">
                                                        {{ strtoupper(substr($item->siswa->nama_siswa, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">{{ $item->siswa->nama_siswa }}</h5>
                                                    <p class="text-muted font-size-13 mb-0">NIS: {{ $item->siswa->nis }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $currentKelas = $item->siswa->getCurrentKelas();
                                            @endphp
                                            <span class="badge badge-soft-info">{{ $currentKelas->nama_kelas ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-primary">{{ $item->siswa->tahunPelajaran->nama_tahun_pelajaran ?? '-' }}</span>
                                        </td>
                                        <td>
                                            @if($item->terpenuhi)
                                                <span class="badge badge-soft-success">Terpenuhi</span>
                                            @else
                                                <span class="badge badge-soft-warning">Belum Terpenuhi</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-truncate" style="max-width: 200px; display: inline-block;" title="{{ $item->keterangan }}">
                                                {{ $item->keterangan ?: '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->tanggal_pemenuhan)
                                                <span class="text-muted">{{ $item->tanggal_pemenuhan->format('d/m/Y') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        wire:click="edit({{ $item->id }})" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#perpustakaanModal"
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
                                        <td colspan="7" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ri-inbox-line font-size-48 text-muted mb-2"></i>
                                                <h5 class="text-muted">Tidak ada data perpustakaan</h5>
                                                <p class="text-muted mb-0">Silakan tambahkan data baru</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($perpustakaan->hasPages())
                        <div class="row mt-4">
                            <div class="col-sm-6">
                                <div class="dataTables_info">
                                    Menampilkan {{ $perpustakaan->firstItem() }} sampai {{ $perpustakaan->lastItem() }} dari {{ $perpustakaan->total() }} entri
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dataTables_paginate paging_simple_numbers float-end">
                                    {{ $perpustakaan->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="perpustakaanModal" tabindex="-1" aria-labelledby="perpustakaanModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="perpustakaanModalLabel">
                        {{ $isEditing ? 'Edit Data Perpustakaan' : 'Tambah Data Perpustakaan' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="resetForm"></button>
                </div>
                <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="siswa_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                                    <select class="form-select @error('siswa_id') is-invalid @enderror" 
                                            id="siswa_id" wire:model="siswa_id">
                                        <option value="">Pilih Siswa</option>
                                        @foreach($siswaOptions as $siswa)
                                            @php
                                                $siswaKelas = $siswa->getCurrentKelas();
                                            @endphp
                                            <option value="{{ $siswa->id }}">{{ $siswa->nama_siswa }} ({{ $siswa->nis }}) - {{ $siswaKelas->nama_kelas ?? 'Tanpa Kelas' }}</option>
                                        @endforeach
                                    </select>
                                    @error('siswa_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="terpenuhi" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('terpenuhi') is-invalid @enderror" 
                                            id="terpenuhi" wire:model="terpenuhi">
                                        <option value="">Pilih Status</option>
                                        <option value="1">Terpenuhi</option>
                                        <option value="0">Belum Terpenuhi</option>
                                    </select>
                                    @error('terpenuhi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_pemenuhan" class="form-label">Tanggal Pemenuhan</label>
                                    <input type="date" class="form-control @error('tanggal_pemenuhan') is-invalid @enderror" 
                                           id="tanggal_pemenuhan" wire:model="tanggal_pemenuhan">
                                    @error('tanggal_pemenuhan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Kosongkan jika belum terpenuhi</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                              id="keterangan" wire:model="keterangan" rows="3" 
                                              placeholder="Keterangan tambahan (opsional)"></textarea>
                                    @error('keterangan')
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            Livewire.on('perpustakaan-created', (message) => {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('perpustakaanModal'));
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

            Livewire.on('perpustakaan-updated', (message) => {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('perpustakaanModal'));
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

            Livewire.on('perpustakaan-deleted', (message) => {
                Toast.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: message
                });
            });

            Livewire.on('perpustakaan-error', (message) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: message
                });
            });
        });

        function confirmDelete(perpustakaanId) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus data perpustakaan ini?',
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
                    @this.delete(perpustakaanId);
                }
            });
        }

        function confirmBulkMarkTerpenuhi() {
             Swal.fire({
                 title: 'Konfirmasi Tandai Semua',
                 text: 'Apakah Anda yakin ingin menandai semua data perpustakaan sebagai terpenuhi?',
                 icon: 'question',
                 showCancelButton: true,
                 confirmButtonColor: '#ffc107',
                 cancelButtonColor: '#6c757d',
                 confirmButtonText: 'Ya, Tandai Semua!',
                 cancelButtonText: 'Batal'
             }).then((result) => {
                 if (result.isConfirmed) {
                     @this.call('bulkMarkTerpenuhi');
                 }
             });
         }

         function confirmBulkImportSiswa(count = null) {
             let text = count ? 
                 `Apakah Anda yakin ingin mengimpor ${count} siswa yang belum memiliki data perpustakaan?` :
                 'Apakah Anda yakin ingin mengimpor semua siswa yang belum memiliki data perpustakaan?';
             
             Swal.fire({
                 title: 'Konfirmasi Import Siswa',
                 text: text,
                 icon: 'info',
                 showCancelButton: true,
                 confirmButtonColor: '#198754',
                 cancelButtonColor: '#6c757d',
                 confirmButtonText: 'Ya, Import!',
                 cancelButtonText: 'Batal'
             }).then((result) => {
                 if (result.isConfirmed) {
                     @this.call('bulkImportSiswa');
                 }
             });
         }
    </script>
</div>
