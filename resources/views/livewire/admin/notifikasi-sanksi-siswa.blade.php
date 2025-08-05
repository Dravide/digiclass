<div>
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-alert-circle text-warning me-2"></i>
                        Notifikasi Sanksi Siswa
                    </h4>
                    <p class="text-muted mb-0">Daftar siswa yang poin pelanggarannya mencapai batas sanksi</p>
                </div>
                <div class="col-auto">
                    <span class="badge bg-warning fs-6">
                        Total: {{ $total }} siswa
                    </span>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Filter Controls -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Tahun Pelajaran</label>
                    <select wire:model.live="tahunPelajaranId" class="form-select">
                        @foreach($tahunPelajarans as $tahun)
                            <option value="{{ $tahun->id }}">{{ $tahun->nama_tahun_pelajaran }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tingkat Kelas</label>
                    <select wire:model.live="tingkatKelas" class="form-select">
                        <option value="">Semua Kelas</option>
                        @foreach($tingkatKelasList as $tingkat)
                            <option value="{{ $tingkat }}">Kelas {{ $tingkat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status Penanganan</label>
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="semua">Semua Status</option>
                        <option value="perlu_ditangani">Perlu Ditangani</option>
                        <option value="sudah_ditangani">Sudah Ditangani</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pencarian</label>
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" 
                           placeholder="Cari nama, NIS, atau NISN...">
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th>Total Poin</th>
                            <th>Sanksi</th>
                            <th>Penanggungjawab</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaData as $index => $data)
                            <tr>
                                <td>{{ ($currentPage - 1) * $perPage + $index + 1 }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $data->siswa->nama_siswa }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            NIS: {{ $data->siswa->nis }} | NISN: {{ $data->siswa->nisn }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $data->kelas->nama_kelas }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-danger fs-6">{{ $data->total_poin }} Poin</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $data->sanksi->jenis_sanksi }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $data->sanksi->deskripsi_sanksi }}</small>
                                        <br>
                                        <span class="badge bg-{{ $data->sanksi->badge_color }} mt-1">
                                            {{ $data->sanksi->rentang_poin }} Poin
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $data->sanksi->penanggungjawab }}</span>
                                </td>
                                <td>
                                    @if($data->status_penanganan == 'belum_ditangani')
                                        <span class="badge bg-warning">Belum Ditangani</span>
                                    @elseif($data->status_penanganan == 'dalam_proses')
                                        <span class="badge bg-info">Dalam Proses</span>
                                    @else
                                        <span class="badge bg-success">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            wire:click="showDetailSiswa({{ $data->siswa->id }})">
                                        <i class="mdi mdi-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="mdi mdi-information-outline fs-1 mb-2"></i>
                                        <p class="mb-0">Tidak ada siswa yang perlu ditangani saat ini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($totalPages > 1)
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Menampilkan {{ ($currentPage - 1) * $perPage + 1 }} - 
                        {{ min($currentPage * $perPage, $total) }} dari {{ $total }} data
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            @if($currentPage > 1)
                                <li class="page-item">
                                    <button class="page-link" wire:click="setPage({{ $currentPage - 1 }})">
                                        <i class="mdi mdi-chevron-left"></i>
                                    </button>
                                </li>
                            @endif
                            
                            @for($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                                <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                    <button class="page-link" wire:click="setPage({{ $i }})">{{ $i }}</button>
                                </li>
                            @endfor
                            
                            @if($currentPage < $totalPages)
                                <li class="page-item">
                                    <button class="page-link" wire:click="setPage({{ $currentPage + 1 }})">
                                        <i class="mdi mdi-chevron-right"></i>
                                    </button>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </div>

    <!-- Detail Modal -->
    @if($showModal && $selectedSiswa)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="mdi mdi-account-alert text-warning me-2"></i>
                            Detail Sanksi Siswa
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Informasi Siswa</h6>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td width="40%">Nama</td>
                                        <td>: {{ $selectedSiswa->nama_siswa }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIS</td>
                                        <td>: {{ $selectedSiswa->nis }}</td>
                                    </tr>
                                    <tr>
                                        <td>NISN</td>
                                        <td>: {{ $selectedSiswa->nisn }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kelas</td>
                                        <td>: {{ $selectedSiswa->getCurrentKelas()?->nama_kelas }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Informasi Sanksi</h6>
                                @if($selectedSanksi)
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td width="40%">Jenis Sanksi</td>
                                            <td>: {{ $selectedSanksi->jenis_sanksi }}</td>
                                        </tr>
                                        <tr>
                                            <td>Deskripsi</td>
                                            <td>: {{ $selectedSanksi->deskripsi_sanksi }}</td>
                                        </tr>
                                        <tr>
                                            <td>Rentang Poin</td>
                                            <td>: {{ $selectedSanksi->rentang_poin }}</td>
                                        </tr>
                                        <tr>
                                            <td>Penanggungjawab</td>
                                            <td>: {{ $selectedSanksi->penanggungjawab }}</td>
                                        </tr>
                                    </table>
                                @endif
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-12">
                                <h6 class="fw-bold mb-3">Update Status Penanganan</h6>
                                <div class="mb-3">
                                    <label class="form-label">Catatan Penanganan</label>
                                    <textarea wire:model="catatan" class="form-control" rows="3" 
                                              placeholder="Masukkan catatan penanganan..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Tutup</button>
                        <button type="button" class="btn btn-info" 
                                wire:click="updateStatusPenanganan('dalam_proses')">
                            <i class="mdi mdi-clock-outline"></i> Dalam Proses
                        </button>
                        <button type="button" class="btn btn-success" 
                                wire:click="updateStatusPenanganan('selesai')">
                            <i class="mdi mdi-check-circle"></i> Selesai
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>