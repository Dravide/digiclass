@section('title', 'Import Data Siswa per Kelas')

<div>

    <!-- Class Information Card -->
    @if($this->kelas_id && !empty($statistics))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Informasi Kelas: {{ $statistics['nama_kelas'] }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm rounded-circle bg-primary d-flex align-items-center justify-content-center me-3">
                                    <i class="ri-building-line font-size-16 text-white"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-1">Tingkat</p>
                                    <h5 class="mb-0">{{ $statistics['tingkat'] }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm rounded-circle bg-success d-flex align-items-center justify-content-center me-3">
                                    <i class="ri-user-3-line font-size-16 text-white"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-1">Siswa di Kelas</p>
                                    <h5 class="mb-0">{{ number_format($statistics['siswa_di_kelas']) }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm rounded-circle bg-info d-flex align-items-center justify-content-center me-3">
                                    <i class="ri-book-line font-size-16 text-white"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-1">Perpustakaan Aktif</p>
                                    <h5 class="mb-0">{{ number_format($statistics['siswa_perpustakaan_aktif']) }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm rounded-circle bg-warning d-flex align-items-center justify-content-center me-3">
                                    <i class="ri-user-star-line font-size-16 text-white"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-1">Wali Kelas</p>
                                    <h6 class="mb-0 text-truncate">{{ $statistics['wali_kelas'] }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tab Navigation -->
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $importType === 'siswa' ? 'active' : '' }}" 
                       wire:click="$set('importType', 'siswa')" 
                       data-bs-toggle="tab" href="#siswa-tab" role="tab">
                        <span class="d-block d-sm-none"><i class="fas fa-users"></i></span>
                        <span class="d-none d-sm-block">Import Data Siswa</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $importType === 'jenis_pelanggaran' ? 'active' : '' }}" 
                       wire:click="$set('importType', 'jenis_pelanggaran')" 
                       data-bs-toggle="tab" href="#jenis-pelanggaran-tab" role="tab">
                        <span class="d-block d-sm-none"><i class="fas fa-exclamation-triangle"></i></span>
                        <span class="d-none d-sm-block">Import Jenis Pelanggaran</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content p-3 text-muted">
        <!-- Siswa Import Tab -->
        <div class="tab-pane {{ $importType === 'siswa' ? 'active' : '' }}" id="siswa-tab" role="tabpanel">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Import Data Siswa ke Kelas</h4>
                            @if($activeTahunPelajaran)
                                <p class="text-muted mb-0">Tahun Pelajaran: {{ $activeTahunPelajaran->nama_tahun_pelajaran }}</p>
                            @endif
                        </div>
                <div class="card-body">
                    <form wire:submit.prevent="importExcel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kelas_id" class="form-label">Pilih Kelas <span class="text-danger">*</span></label>
                                    <select class="form-select @error('kelas_id') is-invalid @enderror" 
                                            id="kelas_id" wire:model.live="kelas_id">
                                        <option value="">Pilih Kelas</option>
                                        @foreach($kelasOptions as $kelas)
                                            <option value="{{ $kelas->id }}">
                                                {{ $kelas->nama_kelas }} 
                                                @if($kelas->guru)
                                                    - {{ $kelas->guru->nama_guru }}
                                                @else
                                                    - Belum ada wali kelas
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Pilih kelas tujuan untuk import data siswa</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="excelFile" class="form-label">File Excel <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('excelFile') is-invalid @enderror" 
                                           id="excelFile" wire:model="excelFile" accept=".xlsx,.xls,.csv">
                                    @error('excelFile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Format yang didukung: .xlsx, .xls, .csv (Maksimal 10MB)</div>
                                </div>
                            </div>
                        </div>

                        @if($importProgress > 0)
                        <div class="mb-3">
                            <label class="form-label">Progress Import</label>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: {{ $importProgress }}%" 
                                     aria-valuenow="{{ $importProgress }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $importProgress }}%
                                </div>
                            </div>
                            @if($importStatus)
                                <small class="text-muted">{{ $importStatus }}</small>
                            @endif
                        </div>
                        @endif

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" @if($isImporting) disabled @endif>
                                @if($isImporting)
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    Mengimport...
                                @else
                                    <i class="ri-upload-cloud-line align-middle me-1"></i>
                                    Import Data
                                @endif
                            </button>
                            <button type="button" class="btn btn-secondary" wire:click="resetForm" @if($isImporting) disabled @endif>
                                <i class="ri-refresh-line align-middle me-1"></i>
                                Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Template Download -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Template Excel</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Download template Excel untuk import data siswa ke kelas.</p>
                    <button type="button" class="btn btn-outline-success w-100" wire:click="downloadTemplate">
                        <i class="ri-download-line align-middle me-1"></i>
                        Download Template
                    </button>
                </div>
            </div>

            <!-- Data Requirements -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Format Data yang Diperlukan</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="font-size-14 mb-2">Kolom Excel (Berurutan):</h6>
                        <ol class="list-unstyled mb-0">
                            <li class="py-1"><span class="badge bg-primary me-2">1</span>Nama Siswa (Wajib)</li>
                            <li class="py-1"><span class="badge bg-primary me-2">2</span>Jenis Kelamin (L/P) (Wajib)</li>
                            <li class="py-1"><span class="badge bg-primary me-2">3</span>NISN (Wajib, harus unik)</li>
                            <li class="py-1"><span class="badge bg-primary me-2">4</span>NIS (Wajib, harus unik)</li>
                            <li class="py-1"><span class="badge bg-secondary me-2">5</span>Status Perpustakaan (Opsional)</li>
                        </ol>
                    </div>
                    <div class="mb-3">
                        <h6 class="font-size-14 mb-2">Ketentuan Data:</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="py-1"><i class="ri-check-line text-success me-2"></i>NISN dan NIS harus unik dalam sistem</li>
                            <li class="py-1"><i class="ri-check-line text-success me-2"></i>Jenis Kelamin: L (Laki-laki) atau P (Perempuan)</li>
                            <li class="py-1"><i class="ri-check-line text-success me-2"></i>Status Perpustakaan: Ya/Aktif/True/1 untuk terpenuhi</li>
                        </ul>
                    </div>
                    <div class="mb-0">
                        <h6 class="font-size-14 mb-2">Proses Import:</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="py-1"><i class="ri-info-line text-info me-2"></i>Siswa akan ditambahkan ke kelas yang dipilih</li>
                            <li class="py-1"><i class="ri-info-line text-info me-2"></i>Relasi KelasSiswa dibuat otomatis</li>
                            <li class="py-1"><i class="ri-info-line text-info me-2"></i>Data Perpustakaan dibuat untuk setiap siswa</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Import Notes -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Catatan Penting</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        <h6 class="alert-heading font-size-14">Perhatian!</h6>
                        <ul class="mb-0 font-size-13">
                            <li>Pastikan tahun pelajaran sudah dipilih sebelum import</li>
                            <li>Data yang diimport akan terkait dengan tahun pelajaran yang dipilih</li>
                            <li>Kelas akan dibuat otomatis jika belum ada untuk tahun pelajaran tersebut</li>
                            <li>NISN dan NIS dapat berupa data numerik atau string, harus unik untuk setiap siswa</li>
                            <li>Guru harus sudah terdaftar di sistem sebelum import</li>
                            <li>Sistem akan membuat relasi KelasSiswa dan Perpustakaan otomatis</li>
                            <li>Backup data sebelum melakukan import</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- End Siswa Import Tab -->
    
    <!-- Jenis Pelanggaran Import Tab -->
    <div class="tab-pane {{ $importType === 'jenis_pelanggaran' ? 'active' : '' }}" id="jenis-pelanggaran-tab" role="tabpanel">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Import Data Jenis Pelanggaran</h4>
                        <p class="text-muted mb-0">Upload file Excel/CSV untuk import data jenis pelanggaran</p>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="importJenisPelanggaran">
                            <div class="mb-3">
                                <label for="jenisPelanggaranFile" class="form-label">File Excel/CSV <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('jenisPelanggaranFile') is-invalid @enderror" 
                                       id="jenisPelanggaranFile" wire:model="jenisPelanggaranFile" accept=".xlsx,.xls,.csv">
                                @error('jenisPelanggaranFile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Format yang didukung: .xlsx, .xls, .csv (Maksimal 2MB)</div>
                            </div>

                            @if($jenisPelanggaranProgress > 0)
                            <div class="mb-3">
                                <label class="form-label">Progress Import</label>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                         role="progressbar" style="width: {{ $jenisPelanggaranProgress }}%" 
                                         aria-valuenow="{{ $jenisPelanggaranProgress }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ $jenisPelanggaranProgress }}%
                                    </div>
                                </div>
                                @if($jenisPelanggaranStatus)
                                    <small class="text-muted">{{ $jenisPelanggaranStatus }}</small>
                                @endif
                            </div>
                            @endif

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" 
                                        {{ $isImportingJenisPelanggaran ? 'disabled' : '' }}>
                                    @if($isImportingJenisPelanggaran)
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Mengimport...
                                    @else
                                        <i class="ri-upload-cloud-line me-1"></i>
                                        Import Data
                                    @endif
                                </button>
                                <button type="button" class="btn btn-outline-secondary" 
                                        wire:click="downloadJenisPelanggaranTemplate">
                                    <i class="ri-download-line me-1"></i>
                                    Download Template
                                </button>
                                <button type="button" class="btn btn-outline-danger" 
                                        wire:click="resetJenisPelanggaranForm" 
                                        {{ $isImportingJenisPelanggaran ? 'disabled' : '' }}>
                                    <i class="ri-refresh-line me-1"></i>
                                    Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Template Information -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Format Template</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="font-size-14 mb-2">Kolom CSV (Berurutan):</h6>
                            <ol class="list-unstyled mb-0">
                                <li class="py-1"><span class="badge bg-primary me-2">1</span>kode_kategori (Wajib)</li>
                                <li class="py-1"><span class="badge bg-primary me-2">2</span>kode_pelanggaran (Wajib)</li>
                                <li class="py-1"><span class="badge bg-primary me-2">3</span>nama_pelanggaran (Wajib)</li>
                                <li class="py-1"><span class="badge bg-secondary me-2">4</span>deskripsi_pelanggaran (Opsional)</li>
                                <li class="py-1"><span class="badge bg-primary me-2">5</span>poin_pelanggaran (Wajib)</li>
                                <li class="py-1"><span class="badge bg-primary me-2">6</span>tingkat_pelanggaran (Wajib: ringan, sedang, berat, sangat_berat)</li>
                                <li class="py-1"><span class="badge bg-secondary me-2">7</span>is_active (Opsional)</li>
                            </ol>
                        </div>
                        <div class="mb-3">
                            <h6 class="font-size-14 mb-2">Ketentuan Data:</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="py-1"><i class="ri-check-line text-success me-2"></i>Tingkat: ringan, sedang, berat</li>
                                <li class="py-1"><i class="ri-check-line text-success me-2"></i>Poin: angka (0 untuk tata tertib positif)</li>
                                <li class="py-1"><i class="ri-check-line text-success me-2"></i>Status Aktif: 1 (aktif) atau 0 (tidak aktif)</li>
                                <li class="py-1"><i class="ri-check-line text-success me-2"></i>Delimiter: semicolon (;)</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Import Notes -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Catatan Penting</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning" role="alert">
                            <h6 class="alert-heading font-size-14">Perhatian!</h6>
                            <ul class="mb-0 font-size-13">
                                <li>Kategori akan dibuat otomatis jika belum ada</li>
                                <li>Data yang sudah ada akan diupdate berdasarkan kode_pelanggaran</li>
                                <li>Gunakan template yang disediakan untuk format yang benar</li>
                                <li>Backup data sebelum melakukan import</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Jenis Pelanggaran Import Tab -->
    
    </div>
    <!-- End Tab Content -->

    <style>
        .progress {
            height: 8px;
        }
        
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        
        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeaa7;
            color: #856404;
        }
        
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toast configuration
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // Listen for Livewire events
            Livewire.on('import-success', (message) => {
                Toast.fire({
                    icon: 'success',
                    title: 'Import Berhasil!',
                    text: message
                });
            });

            Livewire.on('import-warning', (message) => {
                Toast.fire({
                    icon: 'warning',
                    title: 'Import Selesai dengan Peringatan',
                    text: message
                });
            });

            Livewire.on('import-error', (message) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Import Gagal!',
                    text: message
                });
            });

            Livewire.on('template-error', (message) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: message
                });
            });

            Livewire.on('import-started', () => {
                // Optional: Show loading indicator
            });

            Livewire.on('import-completed', () => {
                // Optional: Hide loading indicator
            });
            
            // Jenis Pelanggaran Import Events
            Livewire.on('jenis-pelanggaran-import-success', (message) => {
                Toast.fire({
                    icon: 'success',
                    title: 'Import Berhasil!',
                    text: message
                });
            });

            Livewire.on('jenis-pelanggaran-import-error', (message) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Import Gagal!',
                    text: message
                });
            });

            Livewire.on('jenis-pelanggaran-import-started', () => {
                // Optional: Show loading indicator
            });

            Livewire.on('jenis-pelanggaran-import-completed', () => {
                // Optional: Hide loading indicator
            });
        });
    </script>
</div>