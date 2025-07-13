<div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-subtle-primary text-primary rounded fs-2">
                                <i class="ri-school-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="text-muted text-truncate font-size-15 mb-2">Total Siswa</p>
                            <h3 class="fs-4 flex-grow-1 mb-3">{{ $siswaList->total() }}</h3>
                            <p class="text-muted mb-0 text-truncate">Siswa terdaftar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-subtle-success text-success rounded fs-2">
                                <i class="ri-check-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="text-muted text-truncate font-size-15 mb-2">Perpustakaan Terpenuhi</p>
                            <h3 class="fs-4 flex-grow-1 mb-3">
                                {{ $siswaList->filter(function($siswa) { return $siswa->perpustakaan && $siswa->perpustakaan->terpenuhi; })->count() }}
                            </h3>
                            <p class="text-muted mb-0 text-truncate">Siswa dengan akses penuh</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-subtle-warning text-warning rounded fs-2">
                                <i class="ri-error-warning-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="text-muted text-truncate font-size-15 mb-2">Perpustakaan Belum</p>
                            <h3 class="fs-4 flex-grow-1 mb-3">
                                {{ $siswaList->filter(function($siswa) { return !$siswa->perpustakaan || !$siswa->perpustakaan->terpenuhi; })->count() }}
                            </h3>
                            <p class="text-muted mb-0 text-truncate">Akses terbatas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-subtle-info text-info rounded fs-2">
                                <i class="ri-stack-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="text-muted text-truncate font-size-15 mb-2">Total Kelas</p>
                            <h3 class="fs-4 flex-grow-1 mb-3">{{ $kelasList->count() }}</h3>
                            <p class="text-muted mb-0 text-truncate">Kelas tersedia</p>
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
                            <h4 class="card-title mb-0">Data Siswa</h4>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" wire:click="openCreateModal">
                                <i class="ri-add-line me-1"></i> Tambah Siswa
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="search-box">
                                <div class="position-relative">
                                    <input type="text" class="form-control" placeholder="Cari nama, NISN, atau NIS..." wire:model.live="search">
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
                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="filterKelas">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="filterPerpustakaan">
                                <option value="">Semua Status Perpustakaan</option>
                                <option value="1">Terpenuhi</option>
                                <option value="0">Belum Terpenuhi</option>
                            </select>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-nowrap align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>JK</th>
                                    <th>NISN</th>
                                    <th>NIS</th>
                                    <th>Kelas</th>
                                    <th>Wali Kelas</th>
                                    <th>Tahun Pelajaran</th>
                                    <th>Perpustakaan</th>
                                    <th>Link WA</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswaList as $index => $siswa)
                                    <tr>
                                        <td>{{ $siswaList->firstItem() + $index }}</td>
                                        <td>
                                            @if($editingSiswa === $siswa->id)
                                                <input type="text" class="form-control form-control-sm" wire:model="editForm.nama_siswa">
                                                @error('editForm.nama_siswa') <small class="text-danger">{{ $message }}</small> @enderror
                                            @else
                                                {{ $siswa->nama_siswa }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($editingSiswa === $siswa->id)
                                                <select class="form-select form-select-sm" wire:model="editForm.jk">
                                                    <option value="">Pilih</option>
                                                    <option value="L">L</option>
                                                    <option value="P">P</option>
                                                </select>
                                                @error('editForm.jk') <small class="text-danger">{{ $message }}</small> @enderror
                                            @else
                                                <span class="badge bg-{{ $siswa->jk === 'L' ? 'primary' : 'pink' }}">{{ $siswa->jk }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($editingSiswa === $siswa->id)
                                                <input type="text" class="form-control form-control-sm @error('editForm.nisn') is-invalid @enderror" wire:model="editForm.nisn" placeholder="Masukkan NISN">
                                                @error('editForm.nisn') 
                                                    <div class="invalid-feedback d-block">
                                                        <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                                    </div>
                                                @enderror
                                            @else
                                                {{ $siswa->nisn }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($editingSiswa === $siswa->id)
                                                <input type="text" class="form-control form-control-sm @error('editForm.nis') is-invalid @enderror" wire:model="editForm.nis" placeholder="Masukkan NIS">
                                                @error('editForm.nis') 
                                                    <div class="invalid-feedback d-block">
                                                        <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                                    </div>
                                                @enderror
                                            @else
                                                {{ $siswa->nis }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($editingSiswa === $siswa->id)
                                                <select class="form-select form-select-sm" wire:model="editForm.kelas_id">
                                                    <option value="">Pilih Kelas</option>
                                                    @foreach($allKelasList as $kelas)
                                                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                                    @endforeach
                                                </select>
                                                @error('editForm.kelas_id') <small class="text-danger">{{ $message }}</small> @enderror
                                            @else
                                                @php
                                                    $currentKelas = $siswa->getCurrentKelas();
                                                @endphp
                                                <span class="badge bg-info">{{ $currentKelas->nama_kelas ?? '-' }}</span>
                                            @endif
                                        </td>

                                        <td>
                                            @php
                                                $currentGuru = $siswa->getCurrentGuru();
                                            @endphp
                                            {{ $currentGuru->nama_guru ?? '-' }}
                                        </td>
                                        <td>
                                            @if($editingSiswa === $siswa->id)
                                                <select class="form-select form-select-sm" wire:model="editForm.tahun_pelajaran_id">
                                                    <option value="">Pilih Tahun Pelajaran</option>
                                                    @foreach($tahunPelajaranOptions as $tahun)
                                                        <option value="{{ $tahun->id }}">{{ $tahun->nama_tahun_pelajaran }}</option>
                                                    @endforeach
                                                </select>
                                                @error('editForm.tahun_pelajaran_id') <small class="text-danger">{{ $message }}</small> @enderror
                                            @else
                                                <span class="badge bg-primary">{{ $siswa->tahunPelajaran->nama_tahun_pelajaran ?? '-' }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($editingSiswa === $siswa->id)
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" wire:model="editForm.perpustakaan_terpenuhi">
                                                    <label class="form-check-label">Terpenuhi</label>
                                                </div>
                                            @else
                                                @if($siswa->status_perpustakaan === 'aktif')
                                                    <span class="badge bg-success">Terpenuhi</span>
                                                @else
                                                    <span class="badge bg-warning">Belum</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $currentKelas = $siswa->getCurrentKelas();
                                                $kelasLinkWa = $currentKelas ? $currentKelas->link_wa : null;
                                            @endphp
                                            @if($siswa->can_access_link_wa && $kelasLinkWa)
                                                <a href="{{ $kelasLinkWa }}" target="_blank" class="btn btn-sm btn-success" title="Grup WhatsApp Kelas">
                                                    <i class="ri-whatsapp-line"></i>
                                                </a>
                                            @elseif($kelasLinkWa)
                                                <span class="badge bg-secondary" title="Akses terbatas - Status perpustakaan tidak aktif">Akses Terbatas</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($editingSiswa === $siswa->id)
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-success" onclick="confirmUpdate()" wire:loading.attr="disabled">
                                                        <span wire:loading.remove wire:target="updateSiswa">
                                                            <i class="ri-save-line me-1"></i> Simpan
                                                        </span>
                                                        <span wire:loading wire:target="updateSiswa">
                                                            <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                                            Menyimpan...
                                                        </span>
                                                    </button>
                                                    <button type="button" class="btn btn-secondary" wire:click="cancelEdit">
                                                        <i class="ri-close-line"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" wire:click="editSiswa({{ $siswa->id }})">
                                                        <i class="ri-edit-line"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" onclick="confirmDelete({{ $siswa->id }}, '{{ addslashes($siswa->nama_siswa) }}')">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ri-inbox-line font-size-48 d-block mb-2"></i>
                                                Tidak ada data siswa
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    {{ $siswaList->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Class Data by Grade Level -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Data Kelas per Tingkatan</h4>
                </div>
                <div class="card-body">
                    @if(isset($kelasPerTingkat) && $kelasPerTingkat->count() > 0)
                        <div class="row">
                            @foreach($kelasPerTingkat as $tingkat => $kelasList)
                                <div class="col-md-4 mb-4">
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="card-title mb-0">
                                                <i class="ri-stack-line me-2"></i>
                                                Kelas {{ $tingkat }}
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            @if($kelasList && $kelasList->count() > 0)
                                                @foreach($kelasList as $kelas)
                                                    <div class="border rounded p-3 mb-3 bg-light">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h6 class="mb-1 text-primary">{{ $kelas->nama_kelas }}</h6>
                                                            <span class="badge bg-info">{{ $kelas->siswa_count }} siswa</span>
                                                        </div>
                                                        
                                                        @if($kelas->guru)
                                                            <div class="mb-2">
                                                                <small class="text-muted d-block">
                                                                    <i class="ri-user-line me-1"></i>
                                                                    Wali Kelas: {{ $kelas->guru->nama_guru }}
                                                                </small>
                                                            </div>
                                                        @endif
                                                        
                                                        @if($kelas->siswa_list && $kelas->siswa_list->count() > 0)
                                                            <div class="mt-2">
                                                                <small class="text-muted d-block mb-1">Daftar Siswa:</small>
                                                                <div class="student-list" style="max-height: 150px; overflow-y: auto;">
                                                                    @foreach($kelas->siswa_list as $siswa)
                                                                        <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                                            <small class="text-dark">{{ $siswa->nama_siswa }}</small>
                                                                            <div>
                                                                                @if($siswa->status_perpustakaan === 'aktif')
                                                                                    <span class="badge bg-success badge-sm">Perpus ✓</span>
                                                                                @else
                                                                                    <span class="badge bg-warning badge-sm">Perpus ✗</span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="text-center text-muted py-2">
                                                                <small>Belum ada siswa</small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-center text-muted py-4">
                                                    <i class="ri-error-warning-line font-size-24 d-block mb-2"></i>
                                                    <small>Belum ada kelas untuk tingkat {{ $tingkat }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="ri-inbox-line font-size-48 d-block mb-3"></i>
                            <h5>Belum Ada Data Kelas</h5>
                            <p class="mb-0">Data kelas untuk tingkatan 7, 8, dan 9 belum tersedia.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>



    <style>
        .animate__animated {
            animation-duration: 0.5s;
        }
        
        .swal2-popup {
            border-radius: 15px !important;
        }
        
        .swal2-title {
            font-size: 1.5rem !important;
        }
        
        /* Enhanced validation styling */
        .is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
            animation: shake 0.5s ease-in-out;
        }
        
        .invalid-feedback {
            display: block !important;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #dc3545;
            animation: fadeInUp 0.3s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Loading state for inputs */
        .input-loading {
            position: relative;
        }
        
        .input-loading::after {
            content: '';
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: translateY(-50%) rotate(0deg); }
            100% { transform: translateY(-50%) rotate(360deg); }
        }
        
        /* Success state for inputs */
        .is-valid {
            border-color: #28a745 !important;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
        }
        
        .valid-feedback {
            display: block !important;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #28a745;
            animation: fadeInUp 0.3s ease-in-out;
        }
    </style>

    <!-- Modal Tambah Siswa -->
    @if($showCreateModal)
    <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-user-add-line me-2"></i>Tambah Siswa Baru
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeCreateModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="createSiswa">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="createNamaSiswa" class="form-label">Nama Siswa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('createForm.nama_siswa') is-invalid @enderror" 
                                           id="createNamaSiswa" wire:model="createForm.nama_siswa" 
                                           placeholder="Masukkan nama lengkap siswa">
                                    @error('createForm.nama_siswa')
                                        <div class="invalid-feedback">
                                            <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="createJk" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-select @error('createForm.jk') is-invalid @enderror" 
                                            id="createJk" wire:model="createForm.jk">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    @error('createForm.jk')
                                        <div class="invalid-feedback">
                                            <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="createNisn" class="form-label">NISN <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('createForm.nisn') is-invalid @enderror" 
                                           id="createNisn" wire:model="createForm.nisn" 
                                           placeholder="Masukkan NISN">
                                    @error('createForm.nisn')
                                        <div class="invalid-feedback">
                                            <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="createNis" class="form-label">NIS <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('createForm.nis') is-invalid @enderror" 
                                           id="createNis" wire:model="createForm.nis" 
                                           placeholder="Masukkan NIS">
                                    @error('createForm.nis')
                                        <div class="invalid-feedback">
                                            <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="createKelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                                    <select class="form-select @error('createForm.kelas_id') is-invalid @enderror" 
                                            id="createKelas" wire:model="createForm.kelas_id">
                                        <option value="">Pilih Kelas</option>
                                        @foreach($allKelasList as $kelas)
                                            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                    @error('createForm.kelas_id')
                                        <div class="invalid-feedback">
                                            <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="createTahunPelajaran" class="form-label">Tahun Pelajaran <span class="text-danger">*</span></label>
                                    <select class="form-select @error('createForm.tahun_pelajaran_id') is-invalid @enderror" 
                                            id="createTahunPelajaran" wire:model="createForm.tahun_pelajaran_id">
                                        <option value="">Pilih Tahun Pelajaran</option>
                                        @foreach($tahunPelajaranOptions as $tahun)
                                            <option value="{{ $tahun->id }}">{{ $tahun->nama_tahun_pelajaran }}</option>
                                        @endforeach
                                    </select>
                                    @error('createForm.tahun_pelajaran_id')
                                        <div class="invalid-feedback">
                                            <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="createPerpustakaan" wire:model="createForm.perpustakaan_terpenuhi">
                                        <label class="form-check-label" for="createPerpustakaan">
                                            <i class="ri-book-line me-1"></i>Status Perpustakaan Terpenuhi
                                        </label>
                                    </div>
                                    <small class="text-muted">Centang jika siswa telah memenuhi persyaratan perpustakaan</small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeCreateModal">
                        <i class="ri-close-line me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="createSiswa" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="createSiswa">
                            <i class="ri-save-line me-1"></i>Simpan Data
                        </span>
                        <span wire:loading wire:target="createSiswa">
                            <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @push('scripts')
    <script>
        // SweetAlert2 Configuration
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

            
            Livewire.on('siswa-updated', (message) => {
                // Close any existing SweetAlert (loading modal)
                Swal.close();
                
                // Clear all validation states using utility function
                ClassManagementUtils.clearValidationStates();
                
                // Show simple toast notification like delete
                Toast.fire({
                    icon: 'success',
                    title: 'Data siswa berhasil diperbarui!',
                    text: message
                });
            });
            
            Livewire.on('siswa-deleted', (message) => {
                Toast.fire({
                    icon: 'success',
                    title: 'Data siswa berhasil dihapus!',
                    text: message
                });
            });
            
            Livewire.on('update-error', (message) => {
                // Check if it's a validation error
                if (message.includes('Validasi gagal:')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Data Gagal!',
                        html: message.replace('Validasi gagal: ', '').replace(/,/g, '<br>• '),
                        confirmButtonText: 'Perbaiki Data',
                        confirmButtonColor: '#dc3545',
                        showClass: {
                            popup: 'animate__animated animate__shakeX'
                        },
                        customClass: {
                            htmlContainer: 'text-start'
                        }
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Gagal memperbarui data siswa!',
                        text: message
                    });
                }
            });
            
            Livewire.on('delete-error', (message) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal menghapus data siswa!',
                    text: message
                });
            });
        });
        

        
        // Confirm update function
         function confirmUpdate() {
             // Check for validation errors first
             const invalidFields = document.querySelectorAll('.is-invalid');
             if (invalidFields.length > 0) {
                 Swal.fire({
                     icon: 'warning',
                     title: 'Data Belum Valid!',
                     html: 'Masih ada field yang belum valid. Silakan perbaiki terlebih dahulu:<br><br>' +
                           '<div class="text-start">• Periksa field yang ditandai merah<br>• Pastikan NISN dan NIS unik</div>',
                     confirmButtonText: 'OK',
                     confirmButtonColor: '#ffc107',
                     showClass: {
                         popup: 'animate__animated animate__shakeX'
                     }
                 });
                 
                 // Focus on first invalid field
                 invalidFields[0].focus();
                 return;
             }
             
             Swal.fire({
                 title: 'Konfirmasi Perubahan Data',
                 html: 'Apakah Anda yakin ingin menyimpan perubahan data siswa ini?<br><br><small class="text-muted">Pastikan semua data sudah benar sebelum menyimpan.</small>',
                 icon: 'question',
                 showCancelButton: true,
                 confirmButtonColor: '#28a745',
                 cancelButtonColor: '#6c757d',
                 confirmButtonText: '<i class="ri-save-line me-1"></i> Ya, Simpan!',
                 cancelButtonText: '<i class="ri-close-line me-1"></i> Batal',
                 reverseButtons: true,
                 focusCancel: true,
                 showClass: {
                     popup: 'animate__animated animate__fadeInDown'
                 },
                 hideClass: {
                     popup: 'animate__animated animate__fadeOutUp'
                 }
             }).then((result) => {
                 if (result.isConfirmed) {
                     // Show loading state
                     Swal.fire({
                         title: 'Menyimpan Data...',
                         text: 'Mohon tunggu sebentar',
                         icon: 'info',
                         allowOutsideClick: false,
                         allowEscapeKey: false,
                         showConfirmButton: false,
                         didOpen: () => {
                             Swal.showLoading();
                         },
                         willClose: () => {
                             // Clear any loading states when modal closes using utility function
                             ClassManagementUtils.clearValidationStates();
                         }
                     });
                     
                     @this.call('updateSiswa');
                 }
             });
         }
        
        // Confirm delete function
        function confirmDelete(siswaId, siswaName) {
            Swal.fire({
                title: 'Konfirmasi Hapus Data',
                html: `Apakah Anda yakin ingin menghapus data siswa <br><strong>"${siswaName}"</strong>?<br><br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan!</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="ri-delete-bin-line me-1"></i> Ya, Hapus!',
                cancelButtonText: '<i class="ri-close-line me-1"></i> Batal',
                reverseButtons: true,
                focusCancel: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Menghapus Data...',
                        text: 'Mohon tunggu sebentar',
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    Livewire.dispatch('deleteSiswa', { siswaId: siswaId });
                }
            });
        }
        
        // Utility functions for better code organization
         const ClassManagementUtils = {
             // Debounce function for performance optimization
             debounce: function(func, wait) {
                 let timeout;
                 return function executedFunction(...args) {
                     const later = () => {
                         clearTimeout(timeout);
                         func(...args);
                     };
                     clearTimeout(timeout);
                     timeout = setTimeout(later, wait);
                 };
             },
             
             // Safe element selector with error handling
             safeQuerySelector: function(selector) {
                 try {
                     return document.querySelector(selector);
                 } catch (error) {
                     console.warn(`Error selecting element: ${selector}`, error);
                     return null;
                 }
             },
             
             // Clear all validation states
             clearValidationStates: function() {
                 document.querySelectorAll('.is-invalid, .is-valid, .input-loading').forEach(el => {
                     el.classList.remove('is-invalid', 'is-valid', 'input-loading');
                 });
             },
             
             // Add loading state to element
             addLoadingState: function(element) {
                 if (element) {
                     element.classList.remove('is-invalid', 'is-valid');
                     element.classList.add('input-loading');
                 }
             },
             
             // Remove loading state from element
             removeLoadingState: function(element) {
                 if (element) {
                     element.classList.remove('input-loading');
                 }
             }
         };
         
         // Global flag to track if events are already initialized
         let eventsInitialized = false;
         
         // Initialize tooltips and other UI enhancements
         document.addEventListener('DOMContentLoaded', function() {
             // Prevent duplicate initialization
             if (eventsInitialized) {
                 console.warn('Events already initialized, skipping duplicate setup');
                 return;
             }
             
             eventsInitialized = true;
             
             // Initialize Bootstrap tooltips if available
             if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                 var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                 var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                     return new bootstrap.Tooltip(tooltipTriggerEl);
                 });
             }
             
             // Add real-time validation feedback
             setupRealTimeValidation();
         });
        
        // Setup real-time validation for NISN and NIS fields
         function setupRealTimeValidation() {
             // Re-setup validation when DOM changes (for dynamic content)
             const observer = new MutationObserver(function(mutations) {
                 mutations.forEach(function(mutation) {
                     if (mutation.type === 'childList') {
                         setupFieldValidation();
                     }
                 });
             });
             
             observer.observe(document.body, {
                 childList: true,
                 subtree: true
             });
             
             setupFieldValidation();
         }
         
         function setupFieldValidation() {
              const nisnField = ClassManagementUtils.safeQuerySelector('input[wire\\:model="editForm.nisn"]');
              const nisField = ClassManagementUtils.safeQuerySelector('input[wire\\:model="editForm.nis"]');
              
              if (nisnField && !nisnField.hasAttribute('data-validation-setup')) {
                  nisnField.setAttribute('data-validation-setup', 'true');
                  
                  const debouncedNisnValidation = ClassManagementUtils.debounce(() => {
                      ClassManagementUtils.removeLoadingState(nisnField);
                      @this.validateOnly('editForm.nisn');
                  }, 800);
                  
                  nisnField.addEventListener('input', function() {
                      ClassManagementUtils.addLoadingState(this);
                      debouncedNisnValidation();
                  });
              }
              
              if (nisField && !nisField.hasAttribute('data-validation-setup')) {
                  nisField.setAttribute('data-validation-setup', 'true');
                  
                  const debouncedNisValidation = ClassManagementUtils.debounce(() => {
                      ClassManagementUtils.removeLoadingState(nisField);
                      @this.validateOnly('editForm.nis');
                  }, 800);
                  
                  nisField.addEventListener('input', function() {
                      ClassManagementUtils.addLoadingState(this);
                      debouncedNisValidation();
                  });
              }
          }
        
        // Listen for validation results with enhanced error handling
          Livewire.on('validation-success', (field) => {
              try {
                  const fieldElement = ClassManagementUtils.safeQuerySelector(`input[wire\\:model="${field}"]`);
                  if (fieldElement) {
                      ClassManagementUtils.removeLoadingState(fieldElement);
                      fieldElement.classList.remove('is-invalid');
                      fieldElement.classList.add('is-valid');
                      
                      // Add success animation with better performance
                      requestAnimationFrame(() => {
                          fieldElement.style.animation = 'none';
                          fieldElement.offsetHeight; // Trigger reflow
                          fieldElement.style.animation = 'fadeInUp 0.3s ease-in-out';
                      });
                  }
              } catch (error) {
                  console.warn('Error handling validation success:', error);
              }
          });
          
          Livewire.on('validation-error', (field) => {
              try {
                  const fieldElement = ClassManagementUtils.safeQuerySelector(`input[wire\\:model="${field}"]`);
                  if (fieldElement) {
                      ClassManagementUtils.removeLoadingState(fieldElement);
                      fieldElement.classList.remove('is-valid');
                      fieldElement.classList.add('is-invalid');
                      
                      // Add error animation with better performance
                      requestAnimationFrame(() => {
                          fieldElement.style.animation = 'none';
                          fieldElement.offsetHeight; // Trigger reflow
                          fieldElement.style.animation = 'shake 0.5s ease-in-out';
                          
                          // Focus on field after animation with debouncing
                          const debouncedFocus = ClassManagementUtils.debounce(() => {
                              if (fieldElement && typeof fieldElement.focus === 'function') {
                                  fieldElement.focus();
                              }
                          }, 500);
                          
                          debouncedFocus();
                      });
                  }
              } catch (error) {
                  console.warn('Error handling validation error:', error);
              }
          });
          
          // Event listeners untuk create modal
          Livewire.on('siswa-created', (message) => {
              Swal.fire({
                  title: 'Berhasil!',
                  text: message,
                  icon: 'success',
                  confirmButtonText: 'OK',
                  confirmButtonColor: '#3085d6'
              });
          });
          
          Livewire.on('create-error', (message) => {
              Swal.fire({
                  title: 'Error!',
                  text: message,
                  icon: 'error',
                  confirmButtonText: 'OK',
                  confirmButtonColor: '#d33'
              });
          });
    </script>
    @endpush
</div>
