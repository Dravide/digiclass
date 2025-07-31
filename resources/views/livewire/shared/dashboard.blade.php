<div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-primary rounded-circle fs-3">
                                <i class="ri-group-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-2">Total Siswa</p>
                            <h4 class="mb-0">{{ number_format($totalSiswa) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success rounded-circle fs-3">
                                <i class="ri-school-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-2">Total Kelas</p>
                            <h4 class="mb-0">{{ number_format($totalKelas) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info rounded-circle fs-3">
                                <i class="ri-user-check-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-2">Total Guru</p>
                            <h4 class="mb-0">{{ number_format($totalGuru) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning rounded-circle fs-3">
                                <i class="ri-book-open-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-2">Aktif Perpustakaan</p>
                            <h4 class="mb-0">{{ number_format($siswaAktifPerpustakaan) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Aksi Cepat</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('class-management') }}" class="btn btn-primary btn-lg w-100">
                                <i class="ri-group-line me-2"></i>
                                Kelola Data Siswa
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-success btn-lg w-100">
                                <i class="ri-file-upload-line me-2"></i>
                                Import Data Excel
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-info btn-lg w-100">
                                <i class="ri-school-line me-2"></i>
                                Kelola Kelas
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-warning btn-lg w-100">
                                <i class="ri-pie-chart-line me-2"></i>
                                Lihat Laporan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Informasi Sistem</h4>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-2">
                                <i class="ri-dashboard-line"></i>
                            </div>
                        </div>
                        <h5>DigiClass v1.0</h5>
                        <p class="text-muted">Sistem Manajemen Kelas Digital</p>
                        <p class="text-muted mb-0">Kelola data siswa, kelas, dan perpustakaan dengan mudah dan efisien.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>