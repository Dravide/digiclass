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
            <h5 class="card-title mb-0">Manajemen Peminjaman Buku</h5>
            <button type="button" class="btn btn-primary" wire:click="openModal">
                <i class="fas fa-plus"></i> Tambah Peminjaman
            </button>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Cari peminjaman..." wire:model.live="search">
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="dipinjam">Dipinjam</option>
                        <option value="dikembalikan">Dikembalikan</option>
                        <option value="hilang">Hilang</option>
                        <option value="rusak">Rusak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" wire:model.live="filterOverdue" id="filterOverdue">
                        <label class="form-check-label" for="filterOverdue">
                            Terlambat
                        </label>
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" placeholder="Dari tanggal" wire:model.live="startDate">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" placeholder="Sampai tanggal" wire:model.live="endDate">
                </div>
            </div>

            <!-- Borrowings Table -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Siswa</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th>Denda</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $borrowing)
                            <tr class="{{ $borrowing->isOverdue() ? 'table-warning' : '' }}">
                                <td>
                                    <strong>{{ $borrowing->kode_peminjaman }}</strong>
                                    @if($borrowing->isOverdue())
                                        <br><small class="text-danger">Terlambat {{ $borrowing->getDaysOverdue() }} hari</small>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $borrowing->siswa->nama }}</strong><br>
                                    <small class="text-muted">{{ $borrowing->siswa->nis }}</small>
                                </td>
                                <td>
                                    <strong>{{ $borrowing->libraryBook->judul_buku }}</strong><br>
                                    <small class="text-muted">{{ $borrowing->libraryBook->kode_buku }}</small>
                                </td>
                                <td>{{ $borrowing->tanggal_pinjam->format('d/m/Y') }}</td>
                                <td>
                                    <strong>{{ $borrowing->tanggal_kembali_rencana->format('d/m/Y') }}</strong>
                                    @if($borrowing->tanggal_kembali_aktual)
                                        <br><small class="text-success">Aktual: {{ $borrowing->tanggal_kembali_aktual->format('d/m/Y') }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($borrowing->status == 'dipinjam')
                                        <span class="badge bg-warning">Dipinjam</span>
                                    @elseif($borrowing->status == 'dikembalikan')
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @elseif($borrowing->status == 'hilang')
                                        <span class="badge bg-danger">Hilang</span>
                                    @else
                                        <span class="badge bg-secondary">Rusak</span>
                                    @endif
                                </td>
                                <td>
                                    @if($borrowing->denda > 0)
                                        <span class="text-danger">Rp {{ number_format($borrowing->denda, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($borrowing->status == 'dipinjam')
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    wire:click="returnBook({{ $borrowing->id }})" title="Kembalikan">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                wire:click="editBorrowing({{ $borrowing->id }})" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                wire:click="deleteBorrowing({{ $borrowing->id }})" 
                                                onclick="return confirm('Yakin ingin menghapus data peminjaman ini?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data peminjaman</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan {{ $borrowings->firstItem() ?? 0 }} sampai {{ $borrowings->lastItem() ?? 0 }} 
                    dari {{ $borrowings->total() }} peminjaman
                </div>
                <div>
                    {{ $borrowings->links() }}
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
                        <h5 class="modal-title">{{ $editMode ? 'Edit Peminjaman' : 'Tambah Peminjaman' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Peminjaman</label>
                                        <input type="text" class="form-control @error('kode_peminjaman') is-invalid @enderror" 
                                               wire:model="kode_peminjaman" placeholder="Otomatis jika kosong">
                                        @error('kode_peminjaman') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Status *</label>
                                        <select class="form-select @error('status') is-invalid @enderror" wire:model="status">
                                            <option value="dipinjam">Dipinjam</option>
                                            <option value="dikembalikan">Dikembalikan</option>
                                            <option value="hilang">Hilang</option>
                                            <option value="rusak">Rusak</option>
                                        </select>
                                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Siswa *</label>
                                        <select class="form-select @error('siswa_id') is-invalid @enderror" wire:model="siswa_id">
                                            <option value="">Pilih Siswa</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}">{{ $student->nama }} ({{ $student->nis }})</option>
                                            @endforeach
                                        </select>
                                        @error('siswa_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Buku *</label>
                                        <select class="form-select @error('library_book_id') is-invalid @enderror" wire:model="library_book_id">
                                            <option value="">Pilih Buku</option>
                                            @foreach($books as $book)
                                                <option value="{{ $book->id }}">{{ $book->judul_buku }} ({{ $book->kode_buku }}) - Tersedia: {{ $book->jumlah_tersedia }}</option>
                                            @endforeach
                                        </select>
                                        @error('library_book_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Pinjam *</label>
                                        <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror" 
                                               wire:model="tanggal_pinjam">
                                        @error('tanggal_pinjam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Kembali Rencana *</label>
                                        <input type="date" class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror" 
                                               wire:model="tanggal_kembali_rencana">
                                        @error('tanggal_kembali_rencana') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Kembali Aktual</label>
                                        <input type="date" class="form-control @error('tanggal_kembali_aktual') is-invalid @enderror" 
                                               wire:model="tanggal_kembali_aktual">
                                        @error('tanggal_kembali_aktual') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Kondisi Pinjam *</label>
                                        <select class="form-select @error('kondisi_pinjam') is-invalid @enderror" wire:model="kondisi_pinjam">
                                            <option value="baik">Baik</option>
                                            <option value="rusak_ringan">Rusak Ringan</option>
                                            <option value="rusak_berat">Rusak Berat</option>
                                        </select>
                                        @error('kondisi_pinjam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Kondisi Kembali</label>
                                        <select class="form-select @error('kondisi_kembali') is-invalid @enderror" wire:model="kondisi_kembali">
                                            <option value="">Belum dikembalikan</option>
                                            <option value="baik">Baik</option>
                                            <option value="rusak_ringan">Rusak Ringan</option>
                                            <option value="rusak_berat">Rusak Berat</option>
                                        </select>
                                        @error('kondisi_kembali') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Denda (Rp)</label>
                                        <input type="number" class="form-control @error('denda') is-invalid @enderror" 
                                               wire:model="denda" min="0" step="1000">
                                        @error('denda') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                          wire:model="catatan" rows="3" placeholder="Catatan tambahan..."></textarea>
                                @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
