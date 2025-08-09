<div>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Laporan Pelanggaran Siswa
                    </h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('main-page') }}">DigiClass</a></li>
                            <li class="breadcrumb-item active">Laporan Pelanggaran</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($showAccessForm && !$isAuthenticated)
            <!-- Form Kode Akses -->
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-lock me-2"></i>
                                Verifikasi Kode Akses
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <i class="fas fa-shield-alt text-warning" style="font-size: 3rem;"></i>
                                <h6 class="mt-3">Area Terbatas</h6>
                                <p class="text-muted">Masukkan kode akses untuk melaporkan pelanggaran siswa</p>
                            </div>
                            
                            <form wire:submit.prevent="verifyAccessCode">
                                <div class="mb-3">
                                    <label for="accessCode" class="form-label">Kode Akses</label>
                                    <input type="password" 
                                           wire:model="accessCode" 
                                           class="form-control @error('accessCode') is-invalid @enderror" 
                                           id="accessCode" 
                                           placeholder="Masukkan kode akses">
                                    @error('accessCode') 
                                        <div class="invalid-feedback">{{ $message }}</div> 
                                    @enderror
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-key me-2"></i>
                                        Verifikasi Kode
                                    </button>
                                </div>
                            </form>
                            
                            <div class="mt-4 text-center">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Hubungi administrator untuk mendapatkan kode akses
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($isAuthenticated)
            <!-- Header dengan tombol logout -->
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-check-circle me-2"></i>
                            Anda telah terverifikasi dan dapat melaporkan pelanggaran siswa
                        </div>
                        <button wire:click="logout" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-sign-out-alt me-1"></i>
                            Keluar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Pelaporan Pelanggaran -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-file-alt me-2"></i>
                                Form Laporan Pelanggaran
                            </h5>
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="savePelanggaran">
                                <div class="row">
                                    <!-- Pencarian Siswa -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="siswaSearch" class="form-label">Cari Siswa</label>
                                            <div class="position-relative">
                                                <input type="text" 
                                                       wire:model.live="siswaSearch" 
                                                       class="form-control" 
                                                       id="siswaSearch" 
                                                       placeholder="Ketik nama, NIS, atau NISN siswa...">
                                                
                                                @if($filteredSiswaList)
                                                    <div class="dropdown-menu show w-100" style="max-height: 200px; overflow-y: auto;">
                                                        @foreach($filteredSiswaList as $siswa)
                                                            <a href="#" 
                                                               wire:click.prevent="selectSiswaFromSearch({{ json_encode($siswa) }})"
                                                               class="dropdown-item">
                                                                <strong>{{ $siswa['nama_siswa'] }}</strong><br>
                                                                <small class="text-muted">
                                                                    NIS: {{ $siswa['nis'] }} | NISN: {{ $siswa['nisn'] }} | Kelas: {{ $siswa['kelas'] }}
                                                                </small>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            @error('siswa_id') 
                                                <div class="text-danger small mt-1">{{ $message }}</div> 
                                            @enderror
                                        </div>
                                        
                                        @if($selectedSiswaName)
                                            <div class="alert alert-info">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <strong>Siswa Terpilih:</strong><br>
                                                        {{ $selectedSiswaName }}<br>
                                                        <small>
                                                            NIS: {{ $selectedSiswaDetails['nis'] ?? '' }} | 
                                                            NISN: {{ $selectedSiswaDetails['nisn'] ?? '' }} | 
                                                            Kelas: {{ $selectedSiswaDetails['kelas'] ?? '' }}
                                                        </small>
                                                    </div>
                                                    <button type="button" 
                                                            wire:click="clearSiswaSelection" 
                                                            class="btn btn-outline-danger btn-sm">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Tanggal Pelanggaran -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tanggal_pelanggaran" class="form-label">Tanggal Pelanggaran</label>
                                            <input type="date" 
                                                   wire:model="tanggal_pelanggaran" 
                                                   class="form-control @error('tanggal_pelanggaran') is-invalid @enderror" 
                                                   id="tanggal_pelanggaran">
                                            @error('tanggal_pelanggaran') 
                                                <div class="invalid-feedback">{{ $message }}</div> 
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <!-- Kategori Pelanggaran -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="kategori_pelanggaran_id" class="form-label">Kategori Pelanggaran</label>
                                            <select wire:model.live="kategori_pelanggaran_id" 
                                                    class="form-select @error('kategori_pelanggaran_id') is-invalid @enderror" 
                                                    id="kategori_pelanggaran_id">
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach($kategoriPelanggarans as $kategori)
                                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                                @endforeach
                                            </select>
                                            @error('kategori_pelanggaran_id') 
                                                <div class="invalid-feedback">{{ $message }}</div> 
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <!-- Jenis Pelanggaran -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="jenis_pelanggaran_id" class="form-label">Jenis Pelanggaran</label>
                                            <select wire:model="jenis_pelanggaran_id" 
                                                    class="form-select @error('jenis_pelanggaran_id') is-invalid @enderror" 
                                                    id="jenis_pelanggaran_id"
                                                    @if(!$kategori_pelanggaran_id) disabled @endif>
                                                <option value="">-- Pilih Jenis Pelanggaran --</option>
                                                @foreach($jenisPelanggarans as $jenis)
                                                    <option value="{{ $jenis->id }}">
                                                        {{ $jenis->nama_pelanggaran }} ({{ $jenis->poin_pelanggaran }} poin)
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('jenis_pelanggaran_id') 
                                                <div class="invalid-feedback">{{ $message }}</div> 
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Deskripsi Pelanggaran -->
                                <div class="mb-3">
                                    <label for="deskripsi_pelanggaran" class="form-label">Deskripsi Pelanggaran</label>
                                    <textarea wire:model="deskripsi_pelanggaran" 
                                              class="form-control @error('deskripsi_pelanggaran') is-invalid @enderror" 
                                              id="deskripsi_pelanggaran" 
                                              rows="4" 
                                              placeholder="Jelaskan detail pelanggaran yang terjadi..."></textarea>
                                    @error('deskripsi_pelanggaran') 
                                        <div class="invalid-feedback">{{ $message }}</div> 
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <!-- Pelapor -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="pelapor" class="form-label">Nama Pelapor</label>
                                            <input type="text" 
                                                   wire:model="pelapor" 
                                                   class="form-control @error('pelapor') is-invalid @enderror" 
                                                   id="pelapor" 
                                                   placeholder="Nama lengkap pelapor">
                                            @error('pelapor') 
                                                <div class="invalid-feedback">{{ $message }}</div> 
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <!-- Status Penanganan (Otomatis) -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status_penanganan" class="form-label">Status Penanganan</label>
                                            <input type="text" 
                                                   value="Belum Ditangani" 
                                                   class="form-control" 
                                                   id="status_penanganan" 
                                                   readonly 
                                                   style="background-color: #f8f9fa;">
                                            <small class="text-muted">Status otomatis diatur sebagai "Belum Ditangani"</small>
                                            <!-- Hidden field untuk menyimpan nilai -->
                                            <input type="hidden" wire:model="status_penanganan" value="belum_ditangani">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Tindak Lanjut -->
                                <div class="mb-3">
                                    <label for="tindak_lanjut" class="form-label">Tindak Lanjut (Opsional)</label>
                                    <textarea wire:model="tindak_lanjut" 
                                              class="form-control @error('tindak_lanjut') is-invalid @enderror" 
                                              id="tindak_lanjut" 
                                              rows="3" 
                                              placeholder="Tindak lanjut yang akan atau telah dilakukan..."></textarea>
                                    @error('tindak_lanjut') 
                                        <div class="invalid-feedback">{{ $message }}</div> 
                                    @enderror
                                </div>
                                
                                <!-- Catatan -->
                                <div class="mb-3">
                                    <label for="catatan" class="form-label">Catatan Tambahan (Opsional)</label>
                                    <textarea wire:model="catatan" 
                                              class="form-control @error('catatan') is-invalid @enderror" 
                                              id="catatan" 
                                              rows="3" 
                                              placeholder="Catatan tambahan mengenai pelanggaran..."></textarea>
                                    @error('catatan') 
                                        <div class="invalid-feedback">{{ $message }}</div> 
                                    @enderror
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <button type="button" 
                                            wire:click="resetForm" 
                                            class="btn btn-secondary">
                                        <i class="fas fa-undo me-2"></i>
                                        Reset Form
                                    </button>
                                    
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Kirim Laporan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .dropdown-menu.show {
        position: absolute;
        z-index: 1000;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    
    .position-relative {
        position: relative;
    }
</style>
@endpush