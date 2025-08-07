@section('title', 'Manajemen Pelanggaran Siswa')

<div>
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Pelanggaran</p>
                            <h4 class="mb-0">{{ $this->getTotalPelanggaran() }}</h4>
                        </div>
                        <div class="text-danger">
                            <i class="ri-alert-line font-size-24"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Belum Ditangani</p>
                            <h4 class="mb-0">{{ $this->getBelumDitangani() }}</h4>
                        </div>
                        <div class="text-warning">
                            <i class="ri-time-line font-size-24"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Dalam Proses</p>
                            <h4 class="mb-0">{{ $this->getDalamProses() }}</h4>
                        </div>
                        <div class="text-info">
                            <i class="ri-loader-line font-size-24"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Selesai</p>
                            <h4 class="mb-0">{{ $this->getSelesai() }}</h4>
                        </div>
                        <div class="text-success">
                            <i class="ri-check-line font-size-24"></i>
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
                            <h4 class="card-title mb-0">Data Pelanggaran Siswa</h4>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" wire:click="openModal">
                                <i class="ri-add-line align-middle me-1"></i> Tambah Pelanggaran
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="search-box">
                                <div class="position-relative">
                                    <input type="text" class="form-control" placeholder="Cari siswa..." wire:model.live="search">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" wire:model.live="filterKelas">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" wire:model.live="filterStatus">
                                <option value="">Semua Status</option>
                                @foreach($statusOptions as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" wire:model.live="filterTanggalMulai" placeholder="Dari Tanggal">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" wire:model.live="filterTanggalSelesai" placeholder="Sampai Tanggal">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-secondary w-100" wire:click="resetFilters">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Flash Message -->
                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-nowrap table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Siswa</th>
                                    <th>Kelas</th>
                                    <th>Total Pelanggaran</th>
                                    <th>Total Poin</th>
                                    <th>Status Penanganan</th>
                                    <th>Pelanggaran Terakhir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($groupedSiswa as $siswaData)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-3">
                                                    <span class="avatar-title rounded-circle bg-primary text-white font-size-16">
                                                        {{ strtoupper(substr($siswaData->siswa->nama_siswa, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h5 class="font-size-14 mb-1">{{ $siswaData->siswa->nama_siswa }}</h5>
                                                    <p class="text-muted font-size-13 mb-0">NIS: {{ $siswaData->siswa->nis }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $siswaData->siswa->getCurrentKelas()?->nama_kelas ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-soft-info">{{ $siswaData->total_pelanggaran }} pelanggaran</span>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="badge {{ $siswaData->total_poin >= 100 ? 'badge-soft-danger' : ($siswaData->total_poin >= 50 ? 'badge-soft-warning' : 'badge-soft-success') }}">
                                                    {{ $siswaData->total_poin }} poin
                                                </span>
                                                @if($siswaData->sanksi)
                                                    <p class="text-muted font-size-12 mb-0 mt-1">{{ $siswaData->sanksi->jenis_sanksi }}</p>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                @if($siswaData->status_counts['belum_ditangani'] > 0)
                                                    <span class="badge badge-soft-danger">{{ $siswaData->status_counts['belum_ditangani'] }} Belum</span>
                                                @endif
                                                @if($siswaData->status_counts['dalam_proses'] > 0)
                                                    <span class="badge badge-soft-warning">{{ $siswaData->status_counts['dalam_proses'] }} Proses</span>
                                                @endif
                                                @if($siswaData->status_counts['selesai'] > 0)
                                                    <span class="badge badge-soft-success">{{ $siswaData->status_counts['selesai'] }} Selesai</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="font-size-14 mb-1">{{ $siswaData->latest_pelanggaran->jenis_pelanggaran }}</div>
                                            <p class="text-muted font-size-13 mb-0">{{ $siswaData->latest_pelanggaran->tanggal_pelanggaran_formatted }}</p>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-info" 
                                                        wire:click="showDetail({{ $siswaData->siswa->id }})"
                                                        data-bs-toggle="tooltip" 
                                                        title="Detail Siswa">
                                                    <i class="ri-eye-line"></i>
                                                </button>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ri-inbox-line font-size-48 text-muted mb-3 d-block"></i>
                                                <p class="mb-0">Tidak ada data pelanggaran ditemukan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                                <div class="page-info">
                                    <p class="page-size-info">Menampilkan {{ $groupedSiswa->firstItem() ?? 0 }} sampai {{ $groupedSiswa->lastItem() ?? 0 }} dari {{ $groupedSiswa->total() }} siswa</p>
                                </div>
                                {{ $groupedSiswa->links() }}
                            </div>
                        </div>
                    </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5); z-index: 1060;">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $editMode ? 'Edit Pelanggaran' : 'Tambah Pelanggaran Baru' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">

                    <form wire:submit="savePelanggaran" id="pelanggaranForm">
                        <div class="row">
                            <!-- Siswa -->
                            <div class="col-12 mb-3">
                                <label class="form-label">Siswa *</label>
                                <div class="input-group">
                                    <input type="text" 
                                           wire:model.live.debounce.500ms="siswaSearch" 
                                           class="form-control" 
                                           placeholder="Ketik nama atau NIS siswa..."
                                           list="siswaDatalist"
                                           autocomplete="off">
                                    <button class="btn btn-outline-secondary" type="button" wire:click="clearSiswaSearch">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                                
                                <datalist id="siswaDatalist">
                                    @foreach($filteredSiswaList as $siswa)
                                        <option value="{{ $siswa->nama_siswa }}" 
                                                data-siswa-id="{{ $siswa->id }}"
                                                data-nis="{{ $siswa->nis }}"
                                                data-kelas="{{ $siswa->getCurrentKelas()?->nama_kelas ?? 'Tidak ada kelas' }}">
                                            {{ $siswa->nama_siswa }} ({{ $siswa->nis }}) - {{ $siswa->getCurrentKelas()?->nama_kelas ?? 'Tidak ada kelas' }}
                                        </option>
                                    @endforeach
                                </datalist>
                                
                                @if($selectedSiswaName)
                                    <div class="mt-2">
                                        <div class="alert alert-info py-2 mb-0">
                                            <i class="ri-user-line me-2"></i>
                                            <strong>{{ $selectedSiswaName }}</strong>
                                            @if($selectedSiswaDetails)
                                                <br><small class="text-muted">NIS: {{ $selectedSiswaDetails['nis'] }} | Kelas: {{ $selectedSiswaDetails['kelas'] }}</small>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-danger ms-2" 
                                                    wire:click="clearSiswaSelection">
                                                <i class="ri-close-line"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                
                                @error('siswa_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <!-- Kategori Pelanggaran -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori Pelanggaran *</label>
                                <select wire:model.live="kategori_pelanggaran_id" required class="form-select select2-kategori" id="kategori_pelanggaran_id">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoriPelanggarans as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_pelanggaran_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <!-- Jenis Pelanggaran -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Pelanggaran *</label>
                                <select wire:model="jenis_pelanggaran_id" required class="form-select select2-jenis" id="jenis_pelanggaran_id"
                                        {{ empty($jenisPelanggarans) ? 'disabled' : '' }}>
                                    <option value="">Pilih Jenis Pelanggaran</option>
                                    @foreach($jenisPelanggarans as $jenis)
                                        <option value="{{ $jenis->id }}">
                                            {{ $jenis->nama_pelanggaran }} ({{ $jenis->poin_pelanggaran }} poin)
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_pelanggaran_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <!-- Tanggal Pelanggaran -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Pelanggaran *</label>
                                <input type="date" wire:model="tanggal_pelanggaran" required class="form-control">
                                @error('tanggal_pelanggaran') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <!-- Pelapor -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pelapor *</label>
                                <input type="text" wire:model="pelapor" required class="form-control">
                                @error('pelapor') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <!-- Status Penanganan -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status Penanganan *</label>
                                <select wire:model="status_penanganan" required class="form-select">
                                    @foreach($statusOptions as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status_penanganan') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <!-- Deskripsi Pelanggaran -->
                            <div class="col-12 mb-3">
                                <label class="form-label">Deskripsi Pelanggaran *</label>
                                <textarea wire:model="deskripsi_pelanggaran" rows="3" required class="form-control"
                                          placeholder="Jelaskan detail pelanggaran yang dilakukan..."></textarea>
                                @error('deskripsi_pelanggaran') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <!-- Tindak Lanjut -->
                            <div class="col-12 mb-3">
                                <label class="form-label">Tindak Lanjut</label>
                                <textarea wire:model="tindak_lanjut" rows="2" class="form-control"
                                          placeholder="Tindakan yang telah atau akan dilakukan..."></textarea>
                                @error('tindak_lanjut') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <!-- Catatan -->
                            <div class="col-12 mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea wire:model="catatan" rows="2" class="form-control"
                                          placeholder="Catatan tambahan..."></textarea>
                                @error('catatan') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary" form="pelanggaranForm">
                            {{ $editMode ? 'Perbarui' : 'Simpan' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Detail Siswa -->
    @if($showDetailModal && $selectedSiswa)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5); z-index: 1050;">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Detail Pelanggaran - {{ $selectedSiswa->nama_siswa }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">

                    <!-- Info Siswa -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label text-muted">Nama Siswa</label>
                                    <p class="mb-0 fw-medium">{{ $selectedSiswa->nama_siswa }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted">NIS</label>
                                    <p class="mb-0 fw-medium">{{ $selectedSiswa->nis }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted">Kelas</label>
                                    <p class="mb-0 fw-medium">{{ $selectedSiswa->getCurrentKelas()?->nama_kelas ?? '-' }}</p>
                                </div>
                            </div>
                            
                            @php
                                $totalPoin = $this->getTotalPoinSiswa($selectedSiswa->id);
                                $sanksi = $this->getSanksiSiswa($selectedSiswa->id);
                            @endphp
                            
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Total Poin Pelanggaran</label>
                                    <p class="mb-0">
                                        <span class="badge {{ $totalPoin >= 100 ? 'badge-soft-danger' : ($totalPoin >= 50 ? 'badge-soft-warning' : 'badge-soft-success') }}">
                                            {{ $totalPoin }} poin
                                        </span>
                                    </p>
                                </div>
                                @if($sanksi)
                                    <div class="col-md-6">
                                        <label class="form-label text-muted">Sanksi yang Berlaku</label>
                                        <p class="mb-0 fw-medium">{{ $sanksi->jenis_sanksi }}</p>
                                        <p class="text-muted font-size-12 mb-0">{{ $sanksi->deskripsi_sanksi }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Pelanggaran -->
                    <div>
                        <h5 class="mb-3">Riwayat Pelanggaran</h5>
                        @php
                            $riwayatPelanggarans = $this->getRiwayatPelanggaranSiswa($selectedSiswa->id);
                        @endphp
                        
                        @if($riwayatPelanggarans->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-nowrap table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Pelanggaran</th>
                                            <th>Poin</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($riwayatPelanggarans as $riwayat)
                                            <tr>
                                                <td>{{ $riwayat->tanggal_pelanggaran_formatted }}</td>
                                                <td>
                                                    <div class="font-size-14 mb-1">{{ $riwayat->jenis_pelanggaran }}</div>
                                                    <p class="text-muted font-size-13 mb-0">{{ Str::limit($riwayat->deskripsi_pelanggaran, 50) }}</p>
                                                </td>
                                                <td>
                                                    <span class="badge badge-soft-danger">
                                                        {{ $riwayat->poin_pelanggaran }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $riwayat->status_penanganan === 'selesai' ? 'badge-soft-success' : ($riwayat->status_penanganan === 'dalam_proses' ? 'badge-soft-warning' : 'badge-soft-danger') }}">
                                                        {{ $riwayat->status_label }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                                wire:click="editPelanggaran({{ $riwayat->id }})"
                                                                data-bs-toggle="tooltip" 
                                                                title="Edit Pelanggaran">
                                                            <i class="ri-edit-2-line"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                wire:click="deletePelanggaran({{ $riwayat->id }})"
                                                                wire:confirm="Apakah Anda yakin ingin menghapus pelanggaran ini?"
                                                                data-bs-toggle="tooltip" 
                                                                title="Hapus Pelanggaran">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="text-muted">
                                    <i class="ri-inbox-line font-size-48 text-muted mb-3 d-block"></i>
                                    <p class="mb-0">Tidak ada riwayat pelanggaran.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Wait for Select2 to be available
        function waitForSelect2(callback) {
            if (typeof $.fn.select2 !== 'undefined') {
                callback();
            } else {
                setTimeout(function() {
                    waitForSelect2(callback);
                }, 100);
            }
        }
        
        // Handle datalist selection for siswa
        function handleDatalistSelection() {
            const siswaInput = document.querySelector('input[list="siswaDatalist"]');
            if (siswaInput) {
                siswaInput.addEventListener('input', function(e) {
                    const value = e.target.value;
                    const datalist = document.getElementById('siswaDatalist');
                    const options = datalist.querySelectorAll('option');
                    
                    // Check if the input value matches any option
                    for (let option of options) {
                        if (option.value === value) {
                            const siswaId = option.getAttribute('data-siswa-id');
                            const siswaName = option.value;
                            const siswaData = {
                                id: siswaId,
                                nama_siswa: siswaName,
                                nis: option.getAttribute('data-nis'),
                                kelas: option.getAttribute('data-kelas')
                            };
                            
                            // Trigger Livewire method to select siswa
                            @this.call('selectSiswaFromSearch', siswaData);
                            break;
                        }
                    }
                });
            }
        }
        
        // Initialize Select2 when modal is shown
        Livewire.on('modalOpened', function() {
            waitForSelect2(function() {
                initializeSelect2();
            });
            // Initialize datalist handler when modal opens
            setTimeout(handleDatalistSelection, 100);
        });
        
        function initializeSelect2() {
            // Destroy existing Select2 instances first
            if ($('#kategori_pelanggaran_id').hasClass('select2-hidden-accessible')) {
                $('#kategori_pelanggaran_id').select2('destroy');
            }
            if ($('#jenis_pelanggaran_id').hasClass('select2-hidden-accessible')) {
                $('#jenis_pelanggaran_id').select2('destroy');
            }
            
            // Initialize Select2 for Kategori Pelanggaran
            if ($('#kategori_pelanggaran_id').length) {
                $('#kategori_pelanggaran_id').select2({
                    placeholder: 'Pilih Kategori',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('.modal-content')
                }).on('change', function() {
                    var selectedValue = $(this).val();
                    @this.set('kategori_pelanggaran_id', selectedValue);
                });
            }
            
            // Initialize Select2 for Jenis Pelanggaran
            if ($('#jenis_pelanggaran_id').length) {
                $('#jenis_pelanggaran_id').select2({
                    placeholder: 'Pilih Jenis Pelanggaran',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('.modal-content')
                }).on('change', function() {
                    var selectedValue = $(this).val();
                    @this.set('jenis_pelanggaran_id', selectedValue);
                });
            }
        }
        
        // Destroy Select2 when modal is closed
        Livewire.on('modalClosed', function() {
            if ($('#kategori_pelanggaran_id').hasClass('select2-hidden-accessible')) {
                $('#kategori_pelanggaran_id').select2('destroy');
            }
            if ($('#jenis_pelanggaran_id').hasClass('select2-hidden-accessible')) {
                $('#jenis_pelanggaran_id').select2('destroy');
            }
        });
        
        // Reinitialize Select2 after Livewire updates
        Livewire.hook('message.processed', (message, component) => {
            if ($('#kategori_pelanggaran_id').length) {
                waitForSelect2(function() {
                    setTimeout(initializeSelect2, 300);
                });
            }
            // Reinitialize datalist handler after Livewire updates
            setTimeout(handleDatalistSelection, 100);
        });
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush