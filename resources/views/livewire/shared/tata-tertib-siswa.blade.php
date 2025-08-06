<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Tata Tertib Siswa SMPN 1 Cipanas</h4>
                <p class="text-muted">Silakan baca dengan seksama setiap halaman tata tertib sebelum melanjutkan</p>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold">Progress Membaca:</span>
                        <span class="badge bg-primary">{{ count($checkedPages) }}/{{ $totalPages - 1 }} Halaman</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ count($checkedPages) > 0 ? (count($checkedPages) / ($totalPages - 1)) * 100 : 0 }}%"
                             aria-valuenow="{{ count($checkedPages) }}" 
                             aria-valuemin="0" 
                             aria-valuemax="{{ $totalPages - 1 }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gradient-primary border-0">
                    <h5 class="card-title text-white mb-0">
                        @if($isLastPage)
                            <i class="mdi mdi-file-document-outline me-2"></i>Pakta Integritas
                        @elseif($isInstructionPage)
                            <i class="mdi mdi-information-outline me-2"></i>Instruksi Tata Tertib Siswa
                        @else
                            <i class="mdi mdi-book-open-page-variant me-2"></i>Halaman {{ $currentPage }} dari {{ $totalPages }}
                        @endif
                    </h5>
                </div>
                
                <div class="card-body" style="min-height: 500px;">
                    @if($isLastPage)
                        <!-- Pakta Integritas Page -->
                        <div class="text-center mb-4">
                            <i class="mdi mdi-certificate text-success" style="font-size: 4rem;"></i>
                            <h3 class="text-success mt-3">Selamat!</h3>
                            <p class="lead">Anda telah menyelesaikan membaca seluruh tata tertib siswa.</p>
                        </div>
                        
                        <div class="alert alert-info">
                            <h5><i class="mdi mdi-information me-2"></i>Pakta Integritas</h5>
                            <p class="mb-2">Sebagai bukti bahwa Anda telah membaca dan memahami tata tertib, silakan unduh Pakta Integritas yang harus ditandatangani oleh siswa dan orang tua/wali.</p>
                            
                            @if($paktaIntegritasFiles->isNotEmpty())
                                @foreach($paktaIntegritasFiles as $file)
                                    <div class="d-flex align-items-center mt-3 p-3 bg-light rounded">
                                        <div class="me-3">
                                            @if($file->file_type == 'pdf')
                                                <i class="mdi mdi-file-pdf-box text-danger" style="font-size: 2rem;"></i>
                                            @else
                                                <i class="mdi mdi-file-document text-primary" style="font-size: 2rem;"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $file->nama_file }}</h6>
                                            <small class="text-muted">
                                                {{ strtoupper($file->file_type) }} • {{ $file->formatted_file_size }}
                                                @if($file->deskripsi)
                                                    <br>{{ $file->deskripsi }}
                                                @endif
                                            </small>
                                        </div>
                                        <div class="ms-2">
                                            @if($allPagesChecked)
                                                <button wire:click="downloadPaktaIntegritas({{ $file->id }})" class="btn btn-success btn-sm">
                                                    <i class="mdi mdi-download me-1"></i>Unduh
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    <i class="mdi mdi-lock me-1"></i>Terkunci
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <small class="text-muted">File pakta integritas akan dibuat secara otomatis</small>
                            @endif
                        </div>
                        
                        @if($paktaIntegritasFiles->isEmpty())
                            <div class="text-center">
                                @if($allPagesChecked)
                                    <button wire:click="downloadPaktaIntegritas" class="btn btn-success btn-lg">
                                        <i class="mdi mdi-download me-2"></i>Unduh Pakta Integritas (Default)
                                    </button>
                                @else
                                    <button class="btn btn-secondary btn-lg" disabled>
                                        <i class="mdi mdi-lock me-2"></i>Baca Semua Halaman Terlebih Dahulu
                                    </button>
                                    <p class="text-danger mt-2">Anda harus membaca dan mencentang semua halaman tata tertib</p>
                                @endif
                            </div>
                        @else
                            @if(!$allPagesChecked)
                                <div class="text-center">
                                    <p class="text-danger mt-2">Anda harus membaca dan mencentang semua halaman tata tertib untuk dapat mengunduh file pakta integritas</p>
                                </div>
                            @endif
                        @endif
                    @elseif($isInstructionPage)
                        <!-- Halaman Instruksi -->
                        <div class="text-center mb-4">
                            <i class="mdi mdi-information-outline text-primary" style="font-size: 4rem;"></i>
                            <h3 class="text-primary mt-3">Selamat Datang di Tata Tertib Siswa</h3>
                            <p class="lead">Silakan baca dengan seksama seluruh aturan dan ketentuan berikut</p>
                        </div>
                        
                        <div class="alert alert-info border-start border-primary border-4">
                            <h5><i class="mdi mdi-information me-2"></i>Petunjuk Penggunaan</h5>
                            <ul class="mb-0">
                                <li>Baca setiap halaman dengan teliti dan seksama</li>
                                <li>Centang kotak konfirmasi di bagian bawah setiap halaman setelah selesai membaca</li>
                                <li>Anda harus membaca semua halaman untuk dapat mengunduh Pakta Integritas</li>
                                <li>Gunakan tombol "Selanjutnya" dan "Sebelumnya" untuk navigasi</li>
                            </ul>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card border-start border-4 border-primary">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary"><i class="mdi mdi-book-open-page-variant me-2"></i>Apa yang akan Anda pelajari?</h6>
                                        <ul class="mb-0">
                                            <li>Kategori-kategori pelanggaran siswa</li>
                                            <li>Jenis-jenis pelanggaran dalam setiap kategori</li>
                                            <li>Tingkat pelanggaran dan poin yang diberikan</li>
                                            <li>Sanksi yang berlaku untuk setiap pelanggaran</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border-start border-4 border-success">
                                    <div class="card-body">
                                        <h6 class="card-title text-success"><i class="mdi mdi-target me-2"></i>Tujuan Tata Tertib</h6>
                                        <ul class="mb-0">
                                            <li>Menciptakan lingkungan belajar yang kondusif</li>
                                            <li>Membentuk karakter siswa yang disiplin</li>
                                            <li>Memberikan panduan perilaku yang jelas</li>
                                            <li>Menjaga ketertiban dan keamanan sekolah</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning border-start border-warning border-4">
                             <h6><i class="mdi mdi-alert me-2"></i>Penting untuk Diingat</h6>
                             <p class="mb-0">Setiap siswa wajib mematuhi tata tertib yang berlaku. Pelanggaran terhadap tata tertib akan dikenakan sanksi sesuai dengan tingkat dan jenis pelanggaran yang dilakukan. Pakta Integritas yang akan Anda unduh di akhir merupakan komitmen untuk mematuhi seluruh aturan yang telah ditetapkan.</p>
                         </div>
                         
                         <!-- Checkbox untuk konfirmasi membaca halaman instruksi -->
                         <div class="mt-4 p-3 bg-light rounded">
                             <div class="form-check">
                                 <input class="form-check-input" type="checkbox" 
                                        wire:click="checkPage({{ $currentPage }})" 
                                        @if(in_array($currentPage, $checkedPages)) checked @endif
                                        id="readCheck{{ $currentPage }}">
                                 <label class="form-check-label fw-bold" for="readCheck{{ $currentPage }}">
                                     <i class="mdi mdi-check-circle text-success me-1"></i>
                                     Saya telah membaca dan memahami instruksi tata tertib ini
                                 </label>
                             </div>
                         </div>
                    @else
                        <!-- Tata Tertib Content -->
                        @if($currentKategori)
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="badge bg-primary me-3" style="font-size: 1.2rem;">{{ $currentKategori->kode_kategori }}</div>
                                    <h4 class="mb-0 text-primary">{{ $currentKategori->nama_kategori }}</h4>
                                </div>
                                
                                @if($currentKategori->deskripsi)
                                    <div class="alert alert-light border-start border-primary border-4">
                                        <p class="mb-0">{{ $currentKategori->deskripsi }}</p>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="row">
                                @foreach($currentKategori->jenisPelanggaran as $index => $jenis)
                                    <div class="col-12 mb-3">
                                        <div class="card border-start border-4 
                                @if($jenis->tingkat_pelanggaran == 'ringan') border-success
                                @elseif($jenis->tingkat_pelanggaran == 'sedang') border-warning
                                @elseif($jenis->tingkat_pelanggaran == 'berat') border-danger
                                @elseif($jenis->tingkat_pelanggaran == 'sangat_berat') border-dark
                                @else border-secondary @endif">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <span class="badge 
                                                @if($jenis->tingkat_pelanggaran == 'ringan') bg-success
                                                @elseif($jenis->tingkat_pelanggaran == 'sedang') bg-warning
                                                @elseif($jenis->tingkat_pelanggaran == 'berat') bg-danger
                                                @elseif($jenis->tingkat_pelanggaran == 'sangat_berat') bg-dark
                                                @else bg-secondary @endif me-2">{{ $jenis->kode_pelanggaran }}</span>
                                                            <h6 class="mb-0 fw-bold">{{ $jenis->nama_pelanggaran }}</h6>
                                                        </div>
                                                        
                                                        @if($jenis->deskripsi_pelanggaran)
                                                            <p class="text-muted mb-2">{{ $jenis->deskripsi_pelanggaran }}</p>
                                                        @endif
                                                        
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge bg-secondary me-2">{{ ucfirst($jenis->tingkat_pelanggaran) }}</span>
                                                            <span class="text-danger fw-bold">Poin: {{ $jenis->poin_pelanggaran }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                        <!-- Checkbox untuk konfirmasi membaca -->
                        <div class="mt-4 p-3 bg-light rounded">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       wire:click="checkPage({{ $currentPage }})" 
                                       @if(in_array($currentPage, $checkedPages)) checked @endif
                                       id="readCheck{{ $currentPage }}">
                                <label class="form-check-label fw-bold" for="readCheck{{ $currentPage }}">
                                    <i class="mdi mdi-check-circle text-success me-1"></i>
                                    Saya telah membaca dan memahami tata tertib pada halaman ini
                                </label>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Navigation Footer -->
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <button wire:click="prevPage" class="btn btn-outline-secondary" 
                                @if($currentPage <= 1) disabled @endif>
                            <i class="mdi mdi-chevron-left me-1"></i>Sebelumnya
                        </button>
                        
                        <div class="text-center">
                            @if(!$isLastPage)
                                <span class="text-muted">Halaman {{ $currentPage }} dari {{ $totalPages }}</span>
                            @else
                                <span class="text-success fw-bold">Halaman Terakhir</span>
                            @endif
                        </div>
                        
                        @if(!$isLastPage)
                            <button wire:click="nextPage" class="btn btn-primary" 
                                    @if(!in_array($currentPage, $checkedPages)) disabled @endif>
                                Selanjutnya<i class="mdi mdi-chevron-right ms-1"></i>
                            </button>
                        @else
                            <button onclick="window.history.back()" class="btn btn-outline-primary">
                                <i class="mdi mdi-home me-1"></i>Kembali ke Beranda
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Flash Messages -->
    @if (session()->has('error'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    <style>
.hover-card {
    transition: transform 0.2s ease-in-out;
}

.hover-card:hover {
    transform: translateY(-5px);
}

.progress {
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
</style>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('scroll-to-top', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
</script>
</div>

