<div>
    @section('title', 'Manajemen Tugas')
    
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Manajemen Tugas</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Manajemen Tugas</li>
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

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Header dengan tombol tambah -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h4 class="card-title">Daftar Tugas</h4>
                                </div>
                                <div class="col-md-6 text-end">
                                    <button type="button" class="btn btn-primary" wire:click="create">
                                        <i class="mdi mdi-plus"></i> Tambah Tugas
                                    </button>
                                </div>
                            </div>

                            <!-- Filter dan Search -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" placeholder="Cari tugas..." wire:model.live="search">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" wire:model.live="filterKelas">
                                        <option value="">Semua Kelas</option>
                                        @foreach($kelas as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" wire:model.live="filterMataPelajaran">
                                        <option value="">Semua Mapel</option>
                                        @foreach($mataPelajaran as $mp)
                                            <option value="{{ $mp->id }}">{{ $mp->nama_mapel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" wire:model.live="filterJenis">
                                        <option value="">Semua Jenis</option>
                                        <option value="tugas_harian">Tugas Harian</option>
                                        <option value="ulangan_harian">Ulangan Harian</option>
                                        <option value="uts">UTS</option>
                                        <option value="uas">UAS</option>
                                        <option value="praktikum">Praktikum</option>
                                        <option value="project">Project</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" wire:model.live="filterStatus">
                                        <option value="">Semua Status</option>
                                        <option value="draft">Draft</option>
                                        <option value="aktif">Aktif</option>
                                        <option value="selesai">Selesai</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Tabel Tugas -->
                            <div class="table-responsive">
                                <table class="table table-hover table-nowrap align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Judul</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Kelas</th>
                                            <th>Jenis</th>
                                            <th>Deadline</th>
                                            <th>Status</th>
                                            <th>Progress</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tugas as $t)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-1">{{ $t->judul }}</h6>
                                                        @if($t->deskripsi)
                                                            <p class="text-muted mb-0 small">{{ Str::limit($t->deskripsi, 50) }}</p>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>{{ $t->mataPelajaran->nama_mapel }}</td>
                                <td>{{ $t->kelas->nama_kelas }}</td>
                                                <td>
                                                    <span class="badge {{ $t->jenis_badge_class }}">{{ $t->jenis_label }}</span>
                                                </td>
                                                <td>
                                                    <div>
                                                        {{ $t->tanggal_deadline->format('d/m/Y') }}
                                                        @if($t->is_overdue)
                                                            <span class="badge bg-danger ms-1">Terlewat</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $t->status_badge_class }}">{{ ucfirst($t->status) }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <div class="progress progress-sm">
                                                                @php
                                                                    $progress = $t->total_siswa > 0 ? ($t->jumlah_sudah_mengumpulkan / $t->total_siswa) * 100 : 0;
                                                                @endphp
                                                                <div class="progress-bar" style="width: {{ $progress }}%"></div>
                                                            </div>
                                                        </div>
                                                        <div class="ms-2">
                                                            <span class="text-muted small">{{ $t->jumlah_sudah_mengumpulkan }}/{{ $t->total_siswa }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-outline-primary btn-sm" wire:click="edit({{ $t->id }})">
                                                            <i class="mdi mdi-pencil"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                                                onclick="confirm('Yakin ingin menghapus tugas ini?') || event.stopImmediatePropagation()" 
                                                                wire:click="delete({{ $t->id }})">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="mdi mdi-book-open-page-variant font-size-48 d-block mb-2"></i>
                                                        Belum ada tugas
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-3">
                                {{ $tugas->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editMode ? 'Edit Tugas' : 'Tambah Tugas' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror" wire:model="judul">
                                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" rows="3" wire:model="deskripsi"></textarea>
                                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                                    <select class="form-select @error('mata_pelajaran_id') is-invalid @enderror" wire:model="mata_pelajaran_id">
                                        <option value="">Pilih Mata Pelajaran</option>
                                        @foreach($mataPelajaran as $mp)
                                            <option value="{{ $mp->id }}">{{ $mp->nama_mapel }}</option>
                                        @endforeach
                                    </select>
                                    @error('mata_pelajaran_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kelas <span class="text-danger">*</span></label>
                                    <select class="form-select @error('kelas_id') is-invalid @enderror" wire:model="kelas_id">
                                        <option value="">Pilih Kelas</option>
                                        @foreach($kelas as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Guru <span class="text-danger">*</span></label>
                                    <select class="form-select @error('guru_id') is-invalid @enderror" wire:model="guru_id">
                                        <option value="">Pilih Guru</option>
                                        @foreach($guru as $g)
                                            <option value="{{ $g->id }}">{{ $g->nama_guru }}</option>
                                        @endforeach
                                    </select>
                                    @error('guru_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jenis Tugas <span class="text-danger">*</span></label>
                                    <select class="form-select @error('jenis') is-invalid @enderror" wire:model="jenis">
                                        <option value="tugas_harian">Tugas Harian</option>
                                        <option value="ulangan_harian">Ulangan Harian</option>
                                        <option value="uts">UTS</option>
                                        <option value="uas">UAS</option>
                                        <option value="praktikum">Praktikum</option>
                                        <option value="project">Project</option>
                                    </select>
                                    @error('jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Pemberian <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_pemberian') is-invalid @enderror" wire:model="tanggal_pemberian">
                                    @error('tanggal_pemberian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Deadline <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_deadline') is-invalid @enderror" wire:model="tanggal_deadline">
                                    @error('tanggal_deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Bobot Nilai <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('bobot') is-invalid @enderror" wire:model="bobot" min="1" max="100">
                                    @error('bobot') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" wire:model="status">
                                        <option value="draft">Draft</option>
                                        <option value="aktif">Aktif</option>
                                        <option value="selesai">Selesai</option>
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Catatan</label>
                                    <textarea class="form-control @error('catatan') is-invalid @enderror" rows="2" wire:model="catatan"></textarea>
                                    @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="save">{{ $editMode ? 'Update' : 'Simpan' }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>