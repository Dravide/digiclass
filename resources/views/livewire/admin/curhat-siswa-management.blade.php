<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                        <li class="breadcrumb-item active">Manajemen Curhat Siswa</li>
                    </ol>
                </div>
                <h4 class="page-title">Manajemen Curhat Siswa</h4>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if($showAlert)
        <div class="alert alert-{{ $alertType }} alert-dismissible fade show" role="alert">
            {{ $alertMessage }}
            <button type="button" class="btn-close" wire:click="hideAlert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-subtle-primary text-primary rounded fs-2">
                                <i class="mdi mdi-message-text"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0">{{ $curhatList->total() }}</h3>
                            <p class="text-muted mb-0 text-truncate">Total Curhat</p>
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
                                <i class="mdi mdi-clock-outline"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0">
                                {{ $curhatList->where('status', 'pending')->count() }}
                            </h3>
                            <p class="text-muted mb-0 text-truncate">Menunggu</p>
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
                                <i class="mdi mdi-progress-clock"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0">
                                {{ $curhatList->where('status', 'diproses')->count() }}
                            </h3>
                            <p class="text-muted mb-0 text-truncate">Diproses</p>
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
                                <i class="mdi mdi-check-circle"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0">
                                {{ $curhatList->where('status', 'selesai')->count() }}
                            </h3>
                            <p class="text-muted mb-0 text-truncate">Selesai</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="mdi mdi-filter me-1"></i> Filter & Pencarian</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="search" class="form-label">Pencarian</label>
                                <input type="text" wire:model.live="search" class="form-control" 
                                       placeholder="Cari judul, isi, atau nama...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="statusFilter" class="form-label">Status</label>
                                <select wire:model.live="statusFilter" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Menunggu</option>
                                    <option value="diproses">Diproses</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="kategoriFilter" class="form-label">Kategori</label>
                                <select wire:model.live="kategoriFilter" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategoriOptions as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="tahunPelajaranFilter" class="form-label">Tahun Pelajaran</label>
                                <select wire:model.live="tahunPelajaranFilter" class="form-select">
                                    <option value="">Semua Tahun</option>
                                    @foreach($tahunPelajaranList as $tahun)
                                        <option value="{{ $tahun->id }}">{{ $tahun->nama_tahun_pelajaran }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="mdi mdi-table me-1"></i> Daftar Curhat Siswa</h5>
                    <div class="text-muted">
                        Menampilkan {{ $curhatList->count() }} dari {{ $curhatList->total() }} data
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Pengirim</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($curhatList as $index => $curhat)
                                    <tr>
                                        <td>{{ $curhatList->firstItem() + $index }}</td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $curhat->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($curhat->is_anonim)
                                                <span class="badge bg-secondary">
                                                    <i class="mdi mdi-incognito me-1"></i> Anonim
                                                </span>
                                            @else
                                                <div>
                                                    <strong>{{ $curhat->nama_pengirim }}</strong><br>
                                                    <small class="text-muted">{{ $curhat->kelas_pengirim }}</small>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;">
                                                {{ $curhat->judul }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $kategoriOptions[$curhat->kategori] ?? $curhat->kategori }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($curhat->status === 'pending')
                                                <span class="badge bg-warning">Menunggu</span>
                                            @elseif($curhat->status === 'diproses')
                                                <span class="badge bg-info">Diproses</span>
                                            @else
                                                <span class="badge bg-success">Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-primary" 
                                                        wire:click="showDetail({{ $curhat->id }})">
                                                    <i class="mdi mdi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        wire:click="deleteCurhat({{ $curhat->id }})"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus curhat ini?')">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="mdi mdi-inbox-outline font-size-48 d-block mb-2"></i>
                                                Tidak ada data curhat siswa
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($curhatList->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $curhatList->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    @if($showDetailModal && $selectedCurhat)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="mdi mdi-message-text me-2"></i>
                            Detail Curhat: {{ $selectedCurhat->judul }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeDetailModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pengirim:</label>
                                    <div>
                                        @if($selectedCurhat->is_anonim)
                                            <span class="badge bg-secondary">
                                                <i class="mdi mdi-incognito me-1"></i> Anonim
                                            </span>
                                        @else
                                            {{ $selectedCurhat->nama_pengirim }} ({{ $selectedCurhat->kelas_pengirim }})
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tanggal:</label>
                                    <div>{{ $selectedCurhat->created_at->format('d F Y, H:i') }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Kategori:</label>
                                    <div>
                                        <span class="badge bg-info">
                                            {{ $kategoriOptions[$selectedCurhat->kategori] ?? $selectedCurhat->kategori }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status Saat Ini:</label>
                                    <div>
                                        @if($selectedCurhat->status === 'pending')
                                            <span class="badge bg-warning">Menunggu</span>
                                        @elseif($selectedCurhat->status === 'diproses')
                                            <span class="badge bg-info">Diproses</span>
                                        @else
                                            <span class="badge bg-success">Selesai</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Isi Curhat:</label>
                            <div class="border rounded p-3 bg-light">
                                {{ $selectedCurhat->isi_curhat }}
                            </div>
                        </div>

                        <hr>

                        <form wire:submit.prevent="updateCurhat">
                            <div class="mb-3">
                                <label for="status" class="form-label fw-bold">Update Status:</label>
                                <select wire:model="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="pending">Menunggu</option>
                                    <option value="diproses">Diproses</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="penanganan" class="form-label fw-bold">Penanganan/Catatan:</label>
                                <textarea wire:model="penanganan" class="form-control @error('penanganan') is-invalid @enderror" 
                                          rows="4" placeholder="Jelaskan penanganan atau catatan untuk curhat ini..."></textarea>
                                @error('penanganan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maksimal 1000 karakter</small>
                            </div>

                            @if($selectedCurhat->penanganan)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Penanganan Sebelumnya:</label>
                                    <div class="border rounded p-3 bg-light">
                                        {{ $selectedCurhat->penanganan }}
                                    </div>
                                    @if($selectedCurhat->tanggal_penanganan)
                                        <small class="text-muted">
                                            Ditangani pada: {{ $selectedCurhat->tanggal_penanganan->format('d F Y, H:i') }}
                                        </small>
                                    @endif
                                </div>
                            @endif

                            <div class="text-end">
                                <button type="button" class="btn btn-secondary me-2" wire:click="closeDetailModal">
                                    <i class="mdi mdi-close me-1"></i> Tutup
                                </button>
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        <i class="mdi mdi-content-save me-1"></i> Simpan Perubahan
                                    </span>
                                    <span wire:loading>
                                        <i class="mdi mdi-loading mdi-spin me-1"></i> Menyimpan...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>