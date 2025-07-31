<div>
    @section('title', 'Manajemen Surat Otomatis')
    
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Manajemen Surat Otomatis</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Manajemen Surat</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

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
                            <!-- Header dengan tombol tambah -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="card-title mb-0">Daftar Surat</h4>
                                <button type="button" class="btn btn-primary" wire:click="openModal">
                                    <i class="ri-add-line me-1"></i> Buat Surat Baru
                                </button>
                            </div>

                            <!-- Filter dan Search -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Cari Surat</label>
                                        <input type="text" class="form-control" wire:model.live="search" placeholder="Cari nomor surat, perihal, atau penerima...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" wire:model.live="statusFilter">
                                            <option value="">Semua Status</option>
                                            <option value="draft">Draft</option>
                                            <option value="signed">Ditandatangani</option>
                                            <option value="validated">Divalidasi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Jenis Surat</label>
                                        <select class="form-select" wire:model.live="jenisSuratFilter">
                                            <option value="">Semua Jenis</option>
                                            @foreach($jenisSuratOptions as $jenis)
                                                <option value="{{ $jenis }}">{{ ucfirst($jenis) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-secondary w-100" wire:click="resetFilters">
                                            <i class="ri-refresh-line"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabel Surat -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Nomor Surat</th>
                                            <th>Jenis Surat</th>
                                            <th>Perihal</th>
                                            <th>Penerima</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Dibuat Oleh</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($surat as $index => $item)
                                            <tr>
                                                <td>{{ $surat->firstItem() + $index }}</td>
                                                <td>{{ $item->nomor_surat }}</td>
                                                <td>{{ ucfirst($item->jenis_surat) }}</td>
                                                <td>{{ $item->perihal }}</td>
                                                <td>{{ $item->penerima }}</td>
                                                <td>{{ $item->tanggal_surat->format('d/m/Y') }}</td>
                                                <td>
                                                    @if($item->status === 'draft')
                                                        <span class="badge bg-secondary">Draft</span>
                                                    @elseif($item->status === 'signed')
                                                        <span class="badge bg-warning">Ditandatangani</span>
                                                    @elseif($item->status === 'validated')
                                                        <span class="badge bg-success">Divalidasi</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->creator->name ?? 'Unknown' }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @if($item->status === 'draft')
                                                            <button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $item->id }})" title="Edit">
                                                                <i class="ri-edit-line"></i>
                                                            </button>
                                                            <a href="{{ route('surat-signature', $item->id) }}" class="btn btn-sm btn-outline-success" title="Tanda Tangan">
                                                                <i class="ri-quill-pen-line"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $item->id }})" onclick="return confirm('Yakin ingin menghapus surat ini?')" title="Hapus">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        @else
                                                            <a href="{{ route('surat-signature', $item->id) }}" class="btn btn-sm btn-outline-info" title="Lihat Validasi">
                                                                <i class="ri-eye-line"></i>
                                                            </a>
                                                            @if($item->qr_code_path)
                                                                <a href="{{ Storage::url($item->qr_code_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Lihat QR Code">
                                                                    <i class="ri-qr-code-line"></i>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">Tidak ada data surat.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                {{ $surat->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editMode ? 'Edit Surat' : 'Buat Surat Baru' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="{{ $editMode ? 'update' : 'store' }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('jenis_surat') is-invalid @enderror" wire:model="jenis_surat" placeholder="Contoh: undangan, pemberitahuan, dll">
                                        @error('jenis_surat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror" wire:model="tanggal_surat">
                                        @error('tanggal_surat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Perihal <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('perihal') is-invalid @enderror" wire:model="perihal" placeholder="Masukkan perihal surat">
                                @error('perihal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Penerima <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('penerima') is-invalid @enderror" wire:model="penerima" placeholder="Nama penerima surat">
                                        @error('penerima')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Jabatan Penerima</label>
                                        <input type="text" class="form-control @error('jabatan_penerima') is-invalid @enderror" wire:model="jabatan_penerima" placeholder="Jabatan (opsional)">
                                        @error('jabatan_penerima')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Isi Surat <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('isi_surat') is-invalid @enderror" wire:model="isi_surat" rows="8" placeholder="Masukkan isi surat lengkap..."></textarea>
                                @error('isi_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="{{ $editMode ? 'update' : 'store' }}">
                            {{ $editMode ? 'Perbarui' : 'Simpan' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>