<div>
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-subtle-danger text-danger rounded fs-2">
                                <i class="ri-user-unfollow-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="text-muted text-truncate font-size-15 mb-2">Total Siswa Tidak Aktif</p>
                            <h3 class="fs-4 flex-grow-1 mb-3">{{ $siswaList->total() }}</h3>
                            <p class="text-muted mb-0 text-truncate">Siswa tidak aktif</p>
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
                                <i class="ri-logout-box-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="text-muted text-truncate font-size-15 mb-2">Keluar</p>
                            <h3 class="fs-4 flex-grow-1 mb-3">
                                {{ $siswaList->filter(function($siswa) { return in_array($siswa->keterangan, ['keluar', 'mengundurkan_diri']); })->count() }}
                            </h3>
                            <p class="text-muted mb-0 text-truncate">Siswa keluar/mengundurkan diri</p>
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
                                <i class="ri-exchange-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="text-muted text-truncate font-size-15 mb-2">Pindahan</p>
                            <h3 class="fs-4 flex-grow-1 mb-3">
                                {{ $siswaList->filter(function($siswa) { return $siswa->keterangan === 'pindahan'; })->count() }}
                            </h3>
                            <p class="text-muted mb-0 text-truncate">Siswa pindahan</p>
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
                                <i class="ri-graduation-cap-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="text-muted text-truncate font-size-15 mb-2">Alumni</p>
                            <h3 class="fs-4 flex-grow-1 mb-3">
                                {{ $siswaList->filter(function($siswa) { return $siswa->keterangan === 'alumni'; })->count() }}
                            </h3>
                            <p class="text-muted mb-0 text-truncate">Siswa lulus</p>
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
                            <h4 class="card-title mb-0">Data Siswa Tidak Aktif</h4>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-outline-primary" wire:click="$refresh">
                                <i class="ri-refresh-line me-1"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="search-box">
                                <div class="position-relative">
                                    <input type="text" class="form-control" placeholder="Cari nama, NISN, atau NIS..." wire:model.live="search">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="selectedTahunPelajaran">
                                <option value="">Semua Tahun Pelajaran</option>
                                @foreach($tahunPelajaranList as $tahun)
                                    <option value="{{ $tahun->id }}">{{ $tahun->nama_tahun_pelajaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="selectedKelas">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="selectedKeterangan">
                                <option value="">Semua Keterangan</option>
                                @foreach($keteranganList as $keterangan)
                                    @php
                                        $keteranganLabels = [
                                            'keluar' => 'Keluar',
                                            'mengundurkan_diri' => 'Mengundurkan Diri',
                                            'pindahan' => 'Pindahan',
                                            'meninggal_dunia' => 'Meninggal Dunia',
                                            'alumni' => 'Alumni'
                                        ];
                                    @endphp
                                    <option value="{{ $keterangan }}">{{ $keteranganLabels[$keterangan] ?? $keterangan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-nowrap align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>JK</th>
                                    <th>NISN</th>
                                    <th>NIS</th>
                                    <th>Kelas Terakhir</th>
                                    <th>Tahun Pelajaran</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswaList as $index => $siswa)
                                    <tr>
                                        <td>{{ $siswaList->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-3 flex-shrink-0">
                                                    <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                                                        {{ strtoupper(substr($siswa->nama_siswa, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">{{ $siswa->nama_siswa }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-{{ $siswa->jk === 'L' ? 'primary' : 'pink' }}">{{ $siswa->jk }}</span></td>
                                        <td>{{ $siswa->nisn }}</td>
                                        <td>{{ $siswa->nis }}</td>
                                        <td>
                                            @php
                                                $lastKelas = $siswa->kelasSiswa->last();
                                            @endphp
                                            <span class="badge bg-soft-info text-info">{{ $lastKelas->kelas->nama_kelas ?? '-' }}</span>
                                        </td>
                                        <td><span class="badge bg-soft-primary text-primary">{{ $siswa->tahunPelajaran->nama_tahun_pelajaran ?? '-' }}</span></td>
                                        <td>
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        </td>
                                        <td>
                                            @php
                                                $keteranganLabels = [
                                                    'keluar' => 'Keluar',
                                                    'mengundurkan_diri' => 'Mengundurkan Diri',
                                                    'pindahan' => 'Pindahan',
                                                    'meninggal_dunia' => 'Meninggal Dunia',
                                                    'alumni' => 'Alumni'
                                                ];
                                                $keteranganColors = [
                                                    'keluar' => 'danger',
                                                    'mengundurkan_diri' => 'warning',
                                                    'pindahan' => 'info',
                                                    'meninggal_dunia' => 'dark',
                                                    'alumni' => 'success'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $keteranganColors[$siswa->keterangan] ?? 'secondary' }}">
                                                {{ $keteranganLabels[$siswa->keterangan] ?? ($siswa->keterangan ?? '-') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="#" wire:click="showDetail({{ $siswa->id }})" data-bs-toggle="modal" data-bs-target="#detailModal">
                                                            <i class="ri-eye-line align-bottom me-2 text-muted"></i> Lihat Detail
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" wire:click="activateStudent({{ $siswa->id }})" onclick="return confirm('Apakah Anda yakin ingin mengaktifkan kembali siswa ini?')">
                                                            <i class="ri-user-add-line align-bottom me-2 text-muted"></i> Aktifkan Kembali
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ri-inbox-line font-size-48 d-block mb-2"></i>
                                                Tidak ada data siswa tidak aktif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($siswaList->hasPages())
                        <div class="row align-items-center mt-4">
                            <div class="col-sm-6">
                                <div class="text-muted">
                                    Menampilkan {{ $siswaList->firstItem() }} sampai {{ $siswaList->lastItem() }} dari {{ $siswaList->total() }} data
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="float-sm-end">
                                    {{ $siswaList->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">
                        <i class="ri-user-line me-2"></i>Detail Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if(isset($selectedSiswa))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nama Siswa</label>
                                    <p class="text-muted mb-0">{{ $selectedSiswa->nama_siswa }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">NISN</label>
                                    <p class="text-muted mb-0">{{ $selectedSiswa->nisn }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">NIS</label>
                                    <p class="text-muted mb-0">{{ $selectedSiswa->nis }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                                    <p class="text-muted mb-0">
                                        <span class="badge bg-{{ $selectedSiswa->jk === 'L' ? 'primary' : 'pink' }}">
                                            {{ $selectedSiswa->jk === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Status</label>
                                    <p class="text-muted mb-0">
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Keterangan</label>
                                    <p class="text-muted mb-0">
                                        @php
                                            $keteranganLabels = [
                                                'keluar' => 'Keluar',
                                                'mengundurkan_diri' => 'Mengundurkan Diri',
                                                'pindahan' => 'Pindahan',
                                                'meninggal_dunia' => 'Meninggal Dunia',
                                                'alumni' => 'Alumni'
                                            ];
                                            $keteranganColors = [
                                                'keluar' => 'danger',
                                                'mengundurkan_diri' => 'warning',
                                                'pindahan' => 'info',
                                                'meninggal_dunia' => 'dark',
                                                'alumni' => 'success'
                                            ];
                                        @endphp
                                        <span class="badge bg-soft-{{ $keteranganColors[$selectedSiswa->keterangan] ?? 'secondary' }} text-{{ $keteranganColors[$selectedSiswa->keterangan] ?? 'secondary' }}">
                                            {{ $keteranganLabels[$selectedSiswa->keterangan] ?? ($selectedSiswa->keterangan ?? '-') }}
                                        </span>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Tahun Pelajaran</label>
                                    <p class="text-muted mb-0">
                                        <span class="badge bg-soft-primary text-primary">
                                            {{ $selectedSiswa->tahunPelajaran->nama_tahun_pelajaran ?? '-' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Kelas Terakhir</label>
                                    <p class="text-muted mb-0">
                                        @php
                                            $lastKelas = $selectedSiswa->kelasSiswa->last();
                                        @endphp
                                        <span class="badge bg-soft-info text-info">
                                            {{ $lastKelas->kelas->nama_kelas ?? '-' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    @if(isset($selectedSiswa))
                        <button type="button" class="btn btn-success" wire:click="activateStudent({{ $selectedSiswa->id }})" onclick="return confirm('Apakah Anda yakin ingin mengaktifkan kembali siswa ini?')">
                            <i class="ri-user-add-line me-1"></i> Aktifkan Kembali
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-check-line me-2"></i>{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>