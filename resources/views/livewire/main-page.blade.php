<div>
    <!-- Welcome Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h2 class="text-primary mb-3">Selamat Datang di DigiClass</h2>
            <p class="lead text-secondary">Portal aplikasi digital SMPN 1 Cipanas</p>
        </div>
    </div>

    <!-- Applications Grid -->
    <div class="row g-4">
        <!-- Login -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-4">
                        <i class="mdi mdi-login text-primary" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="card-title fw-bold">Login</h5>
                    <p class="card-text text-muted mb-4">Masuk ke sistem untuk mengakses fitur lengkap</p>
                    <a href="{{ route('login') }}" class="btn btn-primary px-4">Akses</a>
                </div>
            </div>
        </div>

        <!-- Pengumuman -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-4">
                        <i class="mdi mdi-bullhorn text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="card-title fw-bold">Pengumuman Kelas</h5>
                    <p class="card-text text-muted mb-4">Lihat pengumuman kelas terbaru dari sekolah</p>
                    <a href="{{ route('announcement') }}" class="btn btn-warning px-4">Lihat</a>
                </div>
            </div>
        </div>

        <!-- Manajemen Kelas -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-4">
                        <i class="mdi mdi-school text-info" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="card-title fw-bold">Daftar Nilai dan Hadir</h5>
                    <p class="card-text text-muted mb-4">Unduh Daftar Nilai dan Daftar Siswa PDF</p>
                    <a href="{{ route('download') }}" class="btn btn-danger px-4">Unduh</a>
                </div>
            </div>
        </div>

        <!-- Perpustakaan -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-4">
                        <i class="mdi mdi-library text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="card-title fw-bold">Perpustakaan</h5>
                    <p class="card-text text-muted mb-4">Sistem manajemen perpustakaan digital</p>
                    <button class="btn btn-secondary px-4" disabled>Segera Hadir</button>
                </div>
            </div>
        </div>

        <!-- E-Learning -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-4">
                        <i class="mdi mdi-laptop text-primary" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="card-title fw-bold">E-Learning</h5>
                    <p class="card-text text-muted mb-4">Platform pembelajaran online</p>
                    <button class="btn btn-secondary px-4" disabled>Segera Hadir</button>
                </div>
            </div>
        </div>

        <!-- Presensi Siswa -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-4">
                        <i class="mdi mdi-qrcode-scan text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="card-title fw-bold">Presensi Siswa</h5>
                    <p class="card-text text-muted mb-4">Sistem presensi dengan QR Code Scanner</p>
                    <a href="{{ route('presensi') }}" class="btn btn-warning px-4">Akses</a>
                </div>
            </div>
        </div>

        <!-- Nilai & Rapor -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-4">
                        <i class="mdi mdi-file-document text-info" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="card-title fw-bold">Nilai & Rapor</h5>
                    <p class="card-text text-muted mb-4">Sistem penilaian dan rapor digital</p>
                    <button class="btn btn-secondary px-4" disabled>Segera Hadir</button>
                </div>
            </div>
        </div>

        <!-- Komunikasi -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-4">
                        <i class="mdi mdi-message-text text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="card-title fw-bold">Komunikasi</h5>
                    <p class="card-text text-muted mb-4">Platform komunikasi guru, siswa, dan orang tua</p>
                    <button class="btn btn-secondary px-4" disabled>Segera Hadir</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer Info -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <div class="bg-white rounded-3 p-4 shadow-sm border-0">
                <h5 class="text-primary mb-3">Tentang DigiClass</h5>
                <p class="text-muted mb-4">
                    Sistem manajemen kelas digital yang dirancang khusus untuk SMPN 1 Cipanas. 
                    Platform ini menyediakan berbagai fitur untuk mendukung proses pembelajaran dan administrasi sekolah secara digital.
                </p>
                <div class="row g-4">
                    <div class="col-md-4">
                        <i class="mdi mdi-shield-check text-success mb-2" style="font-size: 2.5rem;"></i>
                        <h6 class="fw-bold">Aman & Terpercaya</h6>
                        <p class="text-muted small mb-0">Data terlindungi dengan sistem keamanan terbaik</p>
                    </div>
                    <div class="col-md-4">
                        <i class="mdi mdi-responsive text-primary mb-2" style="font-size: 2.5rem;"></i>
                        <h6 class="fw-bold">Responsif</h6>
                        <p class="text-muted small mb-0">Dapat diakses dari berbagai perangkat</p>
                    </div>
                    <div class="col-md-4">
                        <i class="mdi mdi-clock-fast text-warning mb-2" style="font-size: 2.5rem;"></i>
                        <h6 class="fw-bold">Efisien</h6>
                        <p class="text-muted small mb-0">Mempercepat proses administrasi sekolah</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>