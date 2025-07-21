<div>
    @section('title', 'Manajemen Nilai')
    
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Manajemen Nilai</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Manajemen Nilai</li>
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
                                    <h4 class="card-title">Daftar Nilai</h4>
                                </div>
                                <div class="col-md-6 text-end">
                                    <button type="button" class="btn btn-primary" wire:click="create">
                                        <i class="mdi mdi-plus"></i> Tambah Nilai
                                    </button>
                                </div>
                            </div>

                            <!-- Filter dan Search -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" placeholder="Cari siswa/tugas..." wire:model.live="search">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" wire:model.live="filterKelas">
                                        <option value="">Semua Kelas</option>
                                        @foreach($kelas ?? [] as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" wire:model.live="filterMataPelajaran">
                                        <option value="">Semua Mapel</option>
                                        @foreach($mataPelajaran ?? [] as $mp)
                                            <option value="{{ $mp->id }}">{{ $mp->nama_mapel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" wire:model.live="filterTugas">
                                        <option value="">Semua Tugas</option>
                                        @foreach($tugas ?? [] as $t)
                                            <option value="{{ $t->id }}">{{ $t->judul }} ({{ $t->kelas->nama_kelas }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" wire:model.live="filterStatus">
                                        <option value="">Semua Status</option>
                                        <option value="belum_mengumpulkan">Belum Mengumpulkan</option>
                                        <option value="tepat_waktu">Tepat Waktu</option>
                                        <option value="terlambat">Terlambat</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h6 class="mb-2"><i class="mdi mdi-information"></i> Input Nilai Cepat</h6>
                                        <p class="mb-2">Pilih tugas untuk input nilai secara batch:</p>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach(($tugas ?? collect())->take(5) as $t)
                                                <button type="button" class="btn btn-outline-primary btn-sm" 
                                                        wire:click="openBulkInput({{ $t->id }})">
                                                    {{ $t->judul }} ({{ $t->kelas->nama_kelas }})
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabel Nilai -->
                            <div class="table-responsive">
                                <table class="table table-hover table-nowrap align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Siswa</th>
                                            <th>Tugas</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Kelas</th>
                                            <th>Nilai</th>
                                            <th>Grade</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(($nilai ?? []) as $n)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-1">{{ $n->siswa->nama_siswa }}</h6>
                                                        <p class="text-muted mb-0 small">NIS: {{ $n->siswa->nis }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-1">{{ $n->tugas->judul }}</h6>
                                                        <span class="badge {{ $n->tugas->jenis_badge_class }}">{{ $n->tugas->jenis_label }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ $n->tugas->mataPelajaran->nama_mapel }}</td>
                                <td>{{ $n->tugas->kelas->nama_kelas }}</td>
                                                <td>
                                                    <div class="text-center">
                                                        @if($n->nilai)
                                                            <h5 class="mb-0">{{ $n->formatted_nilai }}</h5>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light {{ $n->grade_color }} font-size-14">{{ $n->grade }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $n->status_badge_class }}">{{ $n->status_label }}</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-outline-primary btn-sm" wire:click="edit({{ $n->id }})">
                                                            <i class="mdi mdi-pencil"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                                                onclick="confirm('Yakin ingin menghapus nilai ini?') || event.stopImmediatePropagation()" 
                                                                wire:click="delete({{ $n->id }})">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="mdi mdi-chart-line font-size-48 d-block mb-2"></i>
                                                        Belum ada nilai
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-3">
                                @if($nilai && method_exists($nilai, 'links'))
                                    {{ $nilai->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form Individual -->
    @if($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editMode ? 'Edit Nilai' : 'Tambah Nilai' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save">
                            <div class="mb-3">
                                <label class="form-label">Tugas <span class="text-danger">*</span></label>
                                <select class="form-select @error('tugas_id') is-invalid @enderror" wire:model="tugas_id">
                                    <option value="">Pilih Tugas</option>
                                    @foreach($tugas ?? [] as $t)
                                         <option value="{{ $t->id }}">{{ $t->judul }} ({{ $t->kelas->nama_kelas }} - {{ $t->mataPelajaran->nama_mapel }})</option>
                                     @endforeach
                                </select>
                                @error('tugas_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Siswa <span class="text-danger">*</span></label>
                                <select class="form-select @error('siswa_id') is-invalid @enderror" wire:model="siswa_id">
                                    <option value="">Pilih Siswa</option>
                                    @foreach($siswa ?? [] as $s)
                                         <option value="{{ $s->id }}">{{ $s->nama_siswa }} ({{ $s->nis }})</option>
                                     @endforeach
                                </select>
                                @error('siswa_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nilai (0-100)</label>
                                <input type="number" class="form-control @error('nilaiForm') is-invalid @enderror" wire:model="nilaiForm" min="0" max="100" step="0.1">
                                @error('nilaiForm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Status Pengumpulan <span class="text-danger">*</span></label>
                                <select class="form-select @error('status_pengumpulan') is-invalid @enderror" wire:model="status_pengumpulan">
                                    <option value="belum_mengumpulkan">Belum Mengumpulkan</option>
                                    <option value="tepat_waktu">Tepat Waktu</option>
                                    <option value="terlambat">Terlambat</option>
                                </select>
                                @error('status_pengumpulan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tanggal Pengumpulan</label>
                                <input type="datetime-local" class="form-control @error('tanggal_pengumpulan') is-invalid @enderror" wire:model="tanggal_pengumpulan">
                                @error('tanggal_pengumpulan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Catatan Guru</label>
                                <textarea class="form-control @error('catatan_guru') is-invalid @enderror" rows="2" wire:model="catatan_guru"></textarea>
                                @error('catatan_guru') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Catatan Siswa</label>
                                <textarea class="form-control @error('catatan_siswa') is-invalid @enderror" rows="2" wire:model="catatan_siswa"></textarea>
                                @error('catatan_siswa') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

    <!-- Modal Bulk Input -->
    @if($showBulkModal && $selectedTugas)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Input Nilai: {{ $selectedTugas->judul }}</h5>
                        <button type="button" class="btn-close" wire:click="closeBulkModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Mata Pelajaran:</strong> {{ $selectedTugas->mataPelajaran->nama_mapel }}</p>
                            <p><strong>Kelas:</strong> {{ $selectedTugas->kelas->nama_kelas }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Jenis:</strong> {{ $selectedTugas->jenis_label }}</p>
                                    <p><strong>Deadline:</strong> {{ $selectedTugas->tanggal_deadline->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-bordered table-sm">
                                <thead class="bg-light sticky-top">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="25%">Nama Siswa</th>
                                        <th width="15%">NIS</th>
                                        <th width="15%">Nilai</th>
                                        <th width="20%">Status</th>
                                        <th width="20%">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(($selectedTugas->kelas->kelasSiswa ?? []) as $index => $kelasSiswa)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $kelasSiswa->siswa->nama_siswa }}</td>
                                            <td>{{ $kelasSiswa->siswa->nis }}</td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm" 
                                                       wire:model="nilaiSiswa.{{ $kelasSiswa->siswa_id }}.nilai" 
                                                       min="0" max="100" step="0.1" placeholder="0-100">
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm" 
                                                        wire:model="nilaiSiswa.{{ $kelasSiswa->siswa_id }}.status_pengumpulan">
                                                    <option value="belum_mengumpulkan">Belum</option>
                                                    <option value="tepat_waktu">Tepat Waktu</option>
                                                    <option value="terlambat">Terlambat</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" 
                                                       wire:model="nilaiSiswa.{{ $kelasSiswa->siswa_id }}.catatan_guru" 
                                                       placeholder="Catatan...">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeBulkModal">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="saveBulkNilai">Simpan Semua Nilai</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>