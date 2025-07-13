@section('title', 'Import Data Excel')

<div>

    <!-- Statistics Cards -->
    @if($this->tahun_pelajaran_id && !empty($statistics))
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Siswa</p>
                            <h4 class="mb-0">{{ number_format($statistics['total_siswa']) }}</h4>
                        </div>
                        <div class="text-primary">
                            <i class="ri-user-3-line font-size-24"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Total Kelas</p>
                            <h4 class="mb-0">{{ number_format($statistics['total_kelas']) }}</h4>
                        </div>
                        <div class="text-success">
                            <i class="ri-building-line font-size-24"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Perpustakaan Aktif</p>
                            <h4 class="mb-0">{{ number_format($statistics['siswa_perpustakaan_aktif']) }}</h4>
                        </div>
                        <div class="text-info">
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
                            <p class="text-truncate font-size-14 mb-2">Terdaftar di Kelas</p>
                            <h4 class="mb-0">{{ number_format($statistics['siswa_terdaftar_kelas']) }}</h4>
                        </div>
                        <div class="text-warning">
                            <i class="ri-group-line font-size-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Import Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Import Data Siswa</h4>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="importExcel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tahun_pelajaran_id" class="form-label">Tahun Pelajaran <span class="text-danger">*</span></label>
                                    <select class="form-select @error('tahun_pelajaran_id') is-invalid @enderror" 
                                            id="tahun_pelajaran_id" wire:model.live="tahun_pelajaran_id">
                                        <option value="">Pilih Tahun Pelajaran</option>
                                        @foreach($tahunPelajaranOptions as $tahun)
                                            <option value="{{ $tahun->id }}">{{ $tahun->nama_tahun_pelajaran }}</option>
                                        @endforeach
                                    </select>
                                    @error('tahun_pelajaran_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Pilih tahun pelajaran untuk data yang akan diimport</div>
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
                    <p class="text-muted">Download template Excel untuk memastikan format data yang benar.</p>
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
                        <h6 class="font-size-14 mb-2">Data Siswa (Wajib):</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="py-1"><i class="ri-check-line text-success me-2"></i>Nama Siswa</li>
                            <li class="py-1"><i class="ri-check-line text-success me-2"></i>Jenis Kelamin (L/P)</li>
                            <li class="py-1"><i class="ri-check-line text-success me-2"></i>NISN (numerik/string, harus unik)</li>
                            <li class="py-1"><i class="ri-check-line text-success me-2"></i>NIS (numerik/string, harus unik)</li>
                        </ul>
                    </div>
                    <div class="mb-3">
                        <h6 class="font-size-14 mb-2">Data Kelas:</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="py-1"><i class="ri-check-line text-success me-2"></i>Nama Kelas (numerik/string, contoh: 7A, 8B, 9IPA1)</li>
                            <li class="py-1"><i class="ri-info-line text-info me-2"></i>Kelas akan dibuat otomatis jika belum ada</li>
                            <li class="py-1"><i class="ri-info-line text-info me-2"></i>Tingkat diekstrak dari nama kelas</li>
                        </ul>
                    </div>
                    <div class="mb-3">
                        <h6 class="font-size-14 mb-2">Data Guru (Opsional):</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="py-1"><i class="ri-check-line text-success me-2"></i>Nama Guru (wali kelas)</li>
                            <li class="py-1"><i class="ri-info-line text-info me-2"></i>Guru harus sudah terdaftar di sistem</li>
                        </ul>
                    </div>
                    <div class="mb-0">
                        <h6 class="font-size-14 mb-2">Data Perpustakaan (Opsional):</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="py-1"><i class="ri-check-line text-success me-2"></i>Status: Ya/Tidak/Aktif/True/1</li>
                            <li class="py-1"><i class="ri-info-line text-info me-2"></i>Default: Tidak terpenuhi jika kosong</li>
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
        });
    </script>
</div>