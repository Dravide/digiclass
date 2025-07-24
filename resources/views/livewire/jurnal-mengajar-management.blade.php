@section('title', 'Jurnal Mengajar')

<div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-check-circle-line me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="ri-information-line me-2"></i>
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Jurnal</p>
                            <h4 class="mb-0">{{ $statistics['total_jurnal'] ?? 0 }}</h4>
                        </div>
                        <div class="text-primary">
                            <i class="ri-book-line font-size-24"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Draft</p>
                            <h4 class="mb-0">{{ $statistics['jurnal_draft'] ?? 0 }}</h4>
                        </div>
                        <div class="text-warning">
                            <i class="ri-edit-line font-size-24"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Submitted</p>
                            <h4 class="mb-0">{{ $statistics['jurnal_submitted'] ?? 0 }}</h4>
                        </div>
                        <div class="text-info">
                            <i class="ri-send-plane-line font-size-24"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Approved</p>
                            <h4 class="mb-0">{{ $statistics['jurnal_approved'] ?? 0 }}</h4>
                        </div>
                        <div class="text-success">
                            <i class="ri-check-circle-line font-size-24"></i>
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
                            <h4 class="card-title mb-0">Data Jurnal Mengajar</h4>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex gap-2">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn {{ $viewMode === 'list' ? 'btn-primary' : 'btn-outline-primary' }}" wire:click="switchViewMode('list')">
                                        <i class="ri-list-check me-1"></i> List
                                    </button>
                                    <button type="button" class="btn {{ $viewMode === 'statistics' ? 'btn-primary' : 'btn-outline-primary' }}" wire:click="switchViewMode('statistics')">
                                        <i class="ri-bar-chart-line me-1"></i> Statistik
                                    </button>
                                </div>
                                <button type="button" class="btn btn-primary" wire:click="create">
                                    <i class="ri-add-line align-middle me-1"></i> Tambah Jurnal
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
                                    <input type="text" class="form-control" placeholder="Cari jurnal..." wire:model.live="search">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" wire:model.live="filterTahunPelajaran">
                                <option value="">Semua Tahun</option>
                                @foreach($tahunPelajaran as $tp)
                                    <option value="{{ $tp->id }}">{{ $tp->nama_tahun_pelajaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" wire:model.live="filterGuru">
                                <option value="">Semua Guru</option>
                                @foreach($guru as $g)
                                    <option value="{{ $g->id }}">{{ $g->nama_guru }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <select class="form-select" wire:model.live="filterKelas">
                                <option value="">Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" wire:model.live="filterMataPelajaran">
                                <option value="">Mata Pelajaran</option>
                                @foreach($mataPelajaran as $mp)
                                    <option value="{{ $mp->id }}">{{ $mp->nama_mapel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <select class="form-select" wire:model.live="filterStatus">
                                <option value="">Status</option>
                                <option value="draft">Draft</option>
                                <option value="submitted">Submitted</option>
                                <option value="approved">Approved</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-secondary w-100" wire:click="resetForm">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                    </div>
                    @if($viewMode === 'list')
                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-nowrap table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th wire:click="sortBy('tanggal')" style="cursor: pointer;">
                                            Tanggal & Waktu
                                            @if($sortField === 'tanggal')
                                                <i class="ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-s-line"></i>
                                            @endif
                                        </th>
                                        <th>Guru & Mata Pelajaran</th>
                                        <th>Kelas</th>
                                        <th>Materi Ajar</th>
                                        <th>Presensi</th>
                                        <th wire:click="sortBy('status')" style="cursor: pointer;">
                                            Status
                                            @if($sortField === 'status')
                                                <i class="ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-s-line"></i>
                                            @endif
                                        </th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($jurnal as $j)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <h5 class="font-size-14 mb-1">{{ $j->tanggal->format('d/m/Y') }}</h5>
                                                        <p class="text-muted font-size-13 mb-0">{{ $j->time_format }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">{{ $j->guru->nama_guru }}</h5>
                                                    <p class="text-muted font-size-13 mb-0">{{ $j->jadwal->mataPelajaran->nama_mapel }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-soft-primary">{{ $j->jadwal->kelas->nama_kelas }}</span>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;" title="{{ $j->materi_ajar }}">
                                                    {{ $j->materi_ajar }}
                                                </div>
                                                @if($j->metode_pembelajaran)
                                                    <p class="text-muted font-size-12 mb-0">{{ $j->metode_pembelajaran }}</p>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="font-size-13">
                                                    <span class="text-success">{{ $j->jumlah_siswa_hadir }}</span> hadir<br>
                                                    <span class="text-danger">{{ $j->jumlah_siswa_tidak_hadir }}</span> tidak hadir<br>
                                                    <small class="text-muted">{{ $j->attendance_percentage }}% kehadiran</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($j->status === 'draft')
                                                    <span class="badge badge-soft-secondary">Draft</span>
                                                @elseif($j->status === 'submitted')
                                                    <span class="badge badge-soft-warning">Submitted</span>
                                                @elseif($j->status === 'approved')
                                                    <span class="badge badge-soft-success">Approved</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            wire:click="edit({{ $j->id }})"
                                                            data-bs-toggle="tooltip" 
                                                            title="Edit">
                                                        <i class="ri-edit-2-line"></i>
                                                    </button>
                                                    
                                                    @if($j->status === 'draft')
                                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                                wire:click="submitJurnal({{ $j->id }})"
                                                                data-bs-toggle="tooltip" 
                                                                title="Submit">
                                                            <i class="ri-send-plane-line"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    @if($j->status === 'submitted')
                                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                                wire:click="approveJurnal({{ $j->id }})"
                                                                data-bs-toggle="tooltip" 
                                                                title="Approve">
                                                            <i class="ri-check-circle-line"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            wire:click="delete({{ $j->id }})"
                                                            wire:confirm="Apakah Anda yakin ingin menghapus jurnal ini?"
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
                                                <div class="text-muted">
                                                    <i class="ri-book-line font-size-48 mb-3 d-block"></i>
                                                    <h5>Belum ada jurnal mengajar</h5>
                                                    <p class="mb-0">Klik "Tambah Jurnal" untuk membuat jurnal baru</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($jurnal->hasPages())
                <div class="card-footer">
                    {{ $jurnal->links() }}
                </div>
            @endif
        </div>
    @endif

    <!-- Modal for Create/Edit -->
    @if($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1" wire:click="$set('showModal', false)">
            <div class="modal-dialog modal-xl" wire:click.stop>
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ $editMode ? 'Edit Jurnal Mengajar' : 'Tambah Jurnal Mengajar' }}
                        </h4>
                        <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form wire:submit="save">
                            <div class="row">
                                <!-- Jadwal -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Jadwal <span class="text-danger">*</span></label>
                                    <select wire:model.live="jadwal_id" wire:change="loadJadwalData" class="form-select">
                                        <option value="">Pilih Jadwal</option>
                                        @foreach($availableJadwal as $jadwal)
                                            <option value="{{ $jadwal->id }}">
                                                {{ $jadwal->mataPelajaran->nama_mapel }} - {{ $jadwal->kelas->nama_kelas }} 
                                                ({{ $jadwal->hari }}, {{ $jadwal->jam_mulai->format('H:i') }}-{{ $jadwal->jam_selesai->format('H:i') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jadwal_id') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <!-- Guru -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Guru <span class="text-danger">*</span></label>
                                    <select wire:model="guru_id" class="form-select">
                                        <option value="">Pilih Guru</option>
                                        @foreach($guru as $g)
                                            <option value="{{ $g->id }}">{{ $g->nama_guru }}</option>
                                        @endforeach
                                    </select>
                                    @error('guru_id') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <!-- Tanggal -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                    <input wire:model="tanggal" type="date" class="form-control">
                                    @error('tanggal') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <!-- Jam Mulai -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                    <input wire:model="jam_mulai" type="time" class="form-control">
                                    @error('jam_mulai') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <!-- Jam Selesai -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                    <input wire:model="jam_selesai" type="time" class="form-control">
                                    @error('jam_selesai') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <!-- Materi Ajar -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Materi Ajar <span class="text-danger">*</span></label>
                                    <input wire:model="materi_ajar" type="text" placeholder="Masukkan materi yang diajarkan" class="form-control">
                                    @error('materi_ajar') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <!-- Kegiatan Pembelajaran -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Kegiatan Pembelajaran</label>
                                    <textarea wire:model="kegiatan_pembelajaran" rows="3" placeholder="Deskripsikan kegiatan pembelajaran yang dilakukan" class="form-control"></textarea>
                                    @error('kegiatan_pembelajaran') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <!-- Metode Pembelajaran -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Metode Pembelajaran</label>
                                    <input wire:model="metode_pembelajaran" type="text" placeholder="Contoh: Ceramah, Diskusi, Praktikum, dll" class="form-control">
                                    @error('metode_pembelajaran') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <!-- Presensi Section -->
                                <div class="col-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Data Presensi</h5>
                                        <button type="button" wire:click="autoFillPresensi" class="btn btn-sm btn-outline-primary">
                                            <i class="ri-refresh-line me-1"></i>Auto Fill dari Presensi
                                        </button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Jumlah Siswa Hadir <span class="text-danger">*</span></label>
                                            <input wire:model="jumlah_siswa_hadir" type="number" min="0" class="form-control">
                                            @error('jumlah_siswa_hadir') <div class="text-danger small">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Jumlah Siswa Tidak Hadir <span class="text-danger">*</span></label>
                                            <input wire:model="jumlah_siswa_tidak_hadir" type="number" min="0" class="form-control">
                                            @error('jumlah_siswa_tidak_hadir') <div class="text-danger small">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Kendala -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Kendala</label>
                                    <textarea wire:model="kendala" rows="2" placeholder="Kendala yang dihadapi selama pembelajaran" class="form-control"></textarea>
                                    @error('kendala') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <!-- Solusi -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Solusi</label>
                                    <textarea wire:model="solusi" rows="2" placeholder="Solusi yang diterapkan untuk mengatasi kendala" class="form-control"></textarea>
                                    @error('solusi') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <!-- Catatan -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Catatan</label>
                                    <textarea wire:model="catatan" rows="2" placeholder="Catatan tambahan" class="form-control"></textarea>
                                    @error('catatan') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <!-- Status -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select wire:model="status" class="form-select">
                                        <option value="draft">Draft</option>
                                        <option value="submitted">Submitted</option>
                                        <option value="approved">Approved</option>
                                    </select>
                                    @error('status') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary" wire:click="save">
                            {{ $editMode ? 'Perbarui' : 'Simpan' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>