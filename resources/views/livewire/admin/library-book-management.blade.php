<div>
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Manajemen Buku Perpustakaan</h5>
            <button type="button" class="btn btn-primary" wire:click="openModal">
                <i class="fas fa-plus"></i> Tambah Buku
            </button>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Cari buku..." wire:model.live="search">
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="filterKategori">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="filterKondisi">
                        <option value="">Semua Kondisi</option>
                        <option value="baik">Baik</option>
                        <option value="rusak_ringan">Rusak Ringan</option>
                        <option value="rusak_berat">Rusak Berat</option>
                    </select>
                </div>
            </div>

            <!-- Books Table -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Kode Buku</th>
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Kondisi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $book)
                            <tr>
                                <td>{{ $book->kode_buku }}</td>
                                <td>
                                    <strong>{{ $book->judul_buku }}</strong><br>
                                    <small class="text-muted">{{ $book->penerbit }} ({{ $book->tahun_terbit }})</small>
                                </td>
                                <td>{{ $book->pengarang }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $book->kategori }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $book->jumlah_tersedia > 0 ? 'success' : 'danger' }}">
                                        {{ $book->jumlah_tersedia }}/{{ $book->jumlah_total }}
                                    </span>
                                </td>
                                <td>
                                    @if($book->kondisi == 'baik')
                                        <span class="badge bg-success">Baik</span>
                                    @elseif($book->kondisi == 'rusak_ringan')
                                        <span class="badge bg-warning">Rusak Ringan</span>
                                    @else
                                        <span class="badge bg-danger">Rusak Berat</span>
                                    @endif
                                </td>
                                <td>
                                    @if($book->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                wire:click="editBook({{ $book->id }})" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-{{ $book->is_active ? 'warning' : 'success' }}" 
                                                wire:click="toggleStatus({{ $book->id }})" 
                                                title="{{ $book->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="fas fa-{{ $book->is_active ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                wire:click="deleteBook({{ $book->id }})" 
                                                onclick="return confirm('Yakin ingin menghapus buku ini?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data buku</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan {{ $books->firstItem() ?? 0 }} sampai {{ $books->lastItem() ?? 0 }} 
                    dari {{ $books->total() }} buku
                </div>
                <div>
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editMode ? 'Edit Buku' : 'Tambah Buku' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Buku</label>
                                        <input type="text" class="form-control @error('kode_buku') is-invalid @enderror" 
                                               wire:model="kode_buku" placeholder="Otomatis jika kosong">
                                        @error('kode_buku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">ISBN</label>
                                        <input type="text" class="form-control @error('isbn') is-invalid @enderror" 
                                               wire:model="isbn">
                                        @error('isbn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Judul Buku *</label>
                                <input type="text" class="form-control @error('judul_buku') is-invalid @enderror" 
                                       wire:model="judul_buku">
                                @error('judul_buku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Pengarang *</label>
                                        <input type="text" class="form-control @error('pengarang') is-invalid @enderror" 
                                               wire:model="pengarang">
                                        @error('pengarang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Penerbit *</label>
                                        <input type="text" class="form-control @error('penerbit') is-invalid @enderror" 
                                               wire:model="penerbit">
                                        @error('penerbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Tahun Terbit *</label>
                                        <input type="number" class="form-control @error('tahun_terbit') is-invalid @enderror" 
                                               wire:model="tahun_terbit" min="1900" max="2025">
                                        @error('tahun_terbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Kategori *</label>
                                        <input type="text" class="form-control @error('kategori') is-invalid @enderror" 
                                               wire:model="kategori" placeholder="Fiksi, Non-Fiksi, dll">
                                        @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Lokasi Rak</label>
                                        <input type="text" class="form-control @error('lokasi_rak') is-invalid @enderror" 
                                               wire:model="lokasi_rak" placeholder="A1, B2, dll">
                                        @error('lokasi_rak') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Total *</label>
                                        <input type="number" class="form-control @error('jumlah_total') is-invalid @enderror" 
                                               wire:model="jumlah_total" min="1">
                                        @error('jumlah_total') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Tersedia *</label>
                                        <input type="number" class="form-control @error('jumlah_tersedia') is-invalid @enderror" 
                                               wire:model="jumlah_tersedia" min="0">
                                        @error('jumlah_tersedia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Kondisi *</label>
                                        <select class="form-select @error('kondisi') is-invalid @enderror" wire:model="kondisi">
                                            <option value="baik">Baik</option>
                                            <option value="rusak_ringan">Rusak Ringan</option>
                                            <option value="rusak_berat">Rusak Berat</option>
                                        </select>
                                        @error('kondisi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          wire:model="deskripsi" rows="3"></textarea>
                                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="is_active" id="is_active">
                                    <label class="form-check-label" for="is_active">
                                        Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                {{ $editMode ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
