<div>
    <!-- Session Messages -->
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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Hadir Hari Ini</h6>
                            <h3 class="mb-0">{{ $todayStats['total_hadir'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Sudah Keluar</h6>
                            <h3 class="mb-0">{{ $todayStats['total_keluar'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-sign-out-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Izin</h6>
                            <h3 class="mb-0">{{ $todayStats['total_izin'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-times fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Hari Ini</h6>
                            <h3 class="mb-0">{{ $todayStats['total_hadir'] + $todayStats['total_keluar'] + $todayStats['total_izin'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manajemen Kehadiran Perpustakaan</h5>
            <button type="button" class="btn btn-primary" wire:click="openModal">
                <i class="fas fa-plus"></i> Tambah Kehadiran
            </button>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Cari siswa atau keperluan..." wire:model.live="search">
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="hadir">Hadir</option>
                        <option value="keluar">Keluar</option>
                        <option value="izin">Izin</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" wire:model.live="filterDate">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" placeholder="Dari tanggal" wire:model.live="startDate">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" placeholder="Sampai tanggal" wire:model.live="endDate">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-secondary" wire:click="$set('search', '')" wire:click="$set('filterStatus', '')" wire:click="$set('startDate', '')" wire:click="$set('endDate', '')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Siswa</th>
                            <th>NIS</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Keperluan</th>
                            <th>Status</th>
                            <th>Durasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->tanggal->format('d/m/Y') }}</td>
                                <td>{{ $attendance->siswa->nama }}</td>
                                <td>{{ $attendance->siswa->nis }}</td>
                                <td>{{ $attendance->jam_masuk ? $attendance->jam_masuk->format('H:i') : '-' }}</td>
                                <td>{{ $attendance->jam_keluar ? $attendance->jam_keluar->format('H:i') : '-' }}</td>
                                <td>{{ $attendance->keperluan }}</td>
                                <td>
                                    @if($attendance->status == 'hadir')
                                        <span class="badge bg-primary">Hadir</span>
                                    @elseif($attendance->status == 'keluar')
                                        <span class="badge bg-success">Keluar</span>
                                    @else
                                        <span class="badge bg-warning">Izin</span>
                                    @endif
                                </td>
                                <td>{{ $attendance->duration ?? '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($attendance->status == 'hadir' && !$attendance->jam_keluar)
                                            <button type="button" class="btn btn-sm btn-success" wire:click="checkOut({{ $attendance->id }})" title="Check Out">
                                                <i class="fas fa-sign-out-alt"></i>
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-warning" wire:click="editAttendance({{ $attendance->id }})" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" wire:click="deleteAttendance({{ $attendance->id }})" onclick="return confirm('Yakin ingin menghapus data kehadiran ini?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data kehadiran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editMode ? 'Edit Kehadiran' : 'Tambah Kehadiran' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Siswa <span class="text-danger">*</span></label>
                                        <select class="form-select @error('siswa_id') is-invalid @enderror" wire:model="siswa_id">
                                            <option value="">Pilih Siswa</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}">{{ $student->nama }} ({{ $student->nis }})</option>
                                            @endforeach
                                        </select>
                                        @error('siswa_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" wire:model="tanggal">
                                        @error('tanggal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jam Masuk <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control @error('jam_masuk') is-invalid @enderror" wire:model="jam_masuk">
                                        @error('jam_masuk')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jam Keluar</label>
                                        <input type="time" class="form-control @error('jam_keluar') is-invalid @enderror" wire:model="jam_keluar">
                                        @error('jam_keluar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Keperluan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('keperluan') is-invalid @enderror" wire:model="keperluan" placeholder="Membaca, Belajar, Mengerjakan tugas, dll">
                                        @error('keperluan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" wire:model="status">
                                            <option value="hadir">Hadir</option>
                                            <option value="keluar">Keluar</option>
                                            <option value="izin">Izin</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control @error('catatan') is-invalid @enderror" wire:model="catatan" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                                @error('catatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                            <button type="submit" class="btn btn-primary">{{ $editMode ? 'Update' : 'Simpan' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
