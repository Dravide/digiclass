<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                        <li class="breadcrumb-item active">Pakta Integritas Management</li>
                    </ol>
                </div>
                <h4 class="page-title">Pakta Integritas Management</h4>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Daftar File Pakta Integritas</h4>
                            <p class="text-muted mb-0">Kelola file pakta integritas yang akan ditampilkan kepada siswa</p>
                        </div>
                        <div class="col-auto">
                            <button wire:click="openModal" class="btn btn-primary">
                                <i class="mdi mdi-plus me-1"></i>Upload File Baru
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Search -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                                <input type="text" class="form-control" placeholder="Cari file..." wire:model.live="search">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" wire:model.live="perPage">
                                <option value="10">10 per halaman</option>
                                <option value="25">25 per halaman</option>
                                <option value="50">50 per halaman</option>
                            </select>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama File</th>
                                    <th>Deskripsi</th>
                                    <th>Ukuran</th>
                                    <th>Diupload Oleh</th>
                                    <th>Status</th>
                                    <th>Tanggal Upload</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paktaIntegritas as $pakta)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <span class="avatar-title bg-primary bg-gradient rounded">
                                                        @if($pakta->file_type == 'pdf')
                                                            <i class="mdi mdi-file-pdf-box text-white"></i>
                                                        @else
                                                            <i class="mdi mdi-file-document text-white"></i>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $pakta->nama_file }}</h6>
                                                    <small class="text-muted">{{ strtoupper($pakta->file_type) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $pakta->deskripsi ?: '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $pakta->formatted_file_size }}</span>
                                        </td>
                                        <td>{{ $pakta->uploaded_by ?: '-' }}</td>
                                        <td>
                                            @if($pakta->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>{{ $pakta->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button wire:click="downloadFile({{ $pakta->id }})" 
                                                        class="btn btn-sm btn-outline-primary" 
                                                        title="Download">
                                                    <i class="mdi mdi-download"></i>
                                                </button>
                                                <button wire:click="edit({{ $pakta->id }})" 
                                                        class="btn btn-sm btn-outline-warning" 
                                                        title="Edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                                <button wire:click="toggleStatus({{ $pakta->id }})" 
                                                        class="btn btn-sm btn-outline-{{ $pakta->is_active ? 'secondary' : 'success' }}" 
                                                        title="{{ $pakta->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="mdi mdi-{{ $pakta->is_active ? 'eye-off' : 'eye' }}"></i>
                                                </button>
                                                <button wire:click="confirmDelete({{ $pakta->id }})" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        title="Hapus">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="mdi mdi-file-document-outline" style="font-size: 3rem;"></i>
                                                <p class="mt-2">Belum ada file pakta integritas yang diupload</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($paktaIntegritas->hasPages())
                        <div class="mt-3">
                            {{ $paktaIntegritas->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Upload/Edit Modal -->
    @if($showModal)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="mdi mdi-{{ $editMode ? 'pencil' : 'upload' }} me-2"></i>
                            {{ $editMode ? 'Edit' : 'Upload' }} Pakta Integritas
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    
                    <form wire:submit.prevent="{{ $editMode ? 'update' : 'save' }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="file" class="form-label">File Pakta Integritas <span class="text-danger">*</span></label>
                                <input type="file" 
                                       class="form-control @error('file') is-invalid @enderror" 
                                       id="file" 
                                       wire:model="file" 
                                       accept=".pdf,.doc,.docx"
                                       {{ $editMode ? '' : 'required' }}>
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Format yang didukung: PDF, DOC, DOCX (Maksimal 10MB)
                                    @if($editMode)
                                        <br><small class="text-info">Kosongkan jika tidak ingin mengubah file</small>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          id="deskripsi" 
                                          wire:model="deskripsi" 
                                          rows="3" 
                                          placeholder="Deskripsi singkat tentang file pakta integritas..."></textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_active" 
                                           wire:model="is_active">
                                    <label class="form-check-label" for="is_active">
                                        Aktifkan file ini
                                    </label>
                                </div>
                                <div class="form-text">
                                    <small class="text-warning">
                                        <i class="mdi mdi-information"></i>
                                        Hanya satu file yang dapat aktif pada satu waktu. Mengaktifkan file ini akan menonaktifkan file lainnya.
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-{{ $editMode ? 'check' : 'upload' }} me-1"></i>
                                {{ $editMode ? 'Perbarui' : 'Upload' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="mdi mdi-delete me-2"></i>Konfirmasi Hapus
                        </h5>
                        <button type="button" class="btn-close" wire:click="$set('showDeleteModal', false)"></button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="mdi mdi-alert-circle text-warning" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Apakah Anda yakin?</h5>
                            <p class="text-muted">File pakta integritas akan dihapus secara permanen dan tidak dapat dikembalikan.</p>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showDeleteModal', false)">Batal</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">
                            <i class="mdi mdi-delete me-1"></i>Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        .hover-card {
            transition: transform 0.2s ease-in-out;
        }
        
        .hover-card:hover {
            transform: translateY(-2px);
        }
        
        .table th {
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .btn-group .btn {
            border-radius: 0.25rem;
            margin-right: 2px;
        }
        
        .avatar-sm {
            height: 2.5rem;
            width: 2.5rem;
        }
        
        .avatar-title {
            align-items: center;
            display: flex;
            font-size: 1rem;
            font-weight: 500;
            height: 100%;
            justify-content: center;
            width: 100%;
        }
    </style>
</div>