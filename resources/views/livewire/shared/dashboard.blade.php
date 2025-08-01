<div>
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary border-0 overflow-hidden">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-sm-8">
                            <div class="text-dark">
                                <h4 class="text-dark mb-1 fw-bold">Selamat Datang, {{ Auth::user()->name }}!</h4>
                                <p class="text-dark mb-0 opacity-75">Kelola sistem pendidikan digital dengan mudah dan efisien</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-sm-end">
                                <div class="avatar-lg mx-auto">
                                    <div class="avatar-title bg-dark bg-opacity-10 rounded-circle">
                                        <i class="ri-dashboard-3-line text-dark fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
     </div>
     
     <!-- Academic Calendar -->
     <div class="row mt-4">
         <div class="col-12">
             @livewire('shared.academic-calendar')
         </div>
     </div>
 </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card hover-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-primary bg-gradient rounded-circle fs-2">
                                <i class="ri-group-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fw-medium">Total Siswa</p>
                            <h3 class="mb-0 text-primary">{{ number_format($totalSiswa) }}</h3>
                            <small class="text-success"><i class="ri-arrow-up-line"></i> Aktif</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card hover-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-success bg-gradient rounded-circle fs-2">
                                <i class="ri-school-line text-white"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fw-medium">Total Kelas</p>
                            <h3 class="mb-0 text-success">{{ number_format($totalKelas) }}</h3>
                            <small class="text-muted">{{ $activeTahunPelajaran ? $activeTahunPelajaran->nama : 'Belum ada' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card hover-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-info bg-gradient rounded-circle fs-2">
                                <i class="ri-user-star-line text-white"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fw-medium">Total Guru</p>
                            <h3 class="mb-0 text-info">{{ number_format($totalGuru) }}</h3>
                            <small class="text-info"><i class="ri-shield-check-line"></i> Terverifikasi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card hover-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-warning bg-gradient rounded-circle fs-2">
                                <i class="ri-book-open-line text-dark"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fw-medium">Aktif Perpustakaan</p>
                            <h3 class="mb-0 text-warning">{{ number_format($totalPerpustakaan) }}</h3>
                            <small class="text-warning">{{ $totalSiswa > 0 ? round(($totalPerpustakaan / $totalSiswa) * 100, 1) : 0 }}% dari total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <!-- Quick Actions & Menu Grid -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <span class="avatar-title bg-primary bg-gradient rounded-circle">
                                <i class="ri-rocket-line text-white"></i>
                            </span>
                        </div>
                        <div>
                            <h4 class="card-title mb-0">Menu Utama</h4>
                            <p class="text-muted mb-0">Akses cepat ke fitur-fitur utama sistem</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @can('manage-siswa')
                        <div class="col-lg-4 col-md-6">
                            <a href="{{ route('class-management') }}" class="text-decoration-none">
                                <div class="card border hover-card h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="avatar-md mx-auto mb-3">
                                            <span class="avatar-title bg-primary bg-gradient rounded-circle">
                                                <i class="ri-group-line fs-4 text-white"></i>
                                            </span>
                                        </div>
                                        <h6 class="mb-1">Data Siswa</h6>
                                        <p class="text-muted mb-0 small">Kelola informasi siswa</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endcan

                        @can('manage-kelas')
                        <div class="col-lg-4 col-md-6">
                            <a href="{{ route('kelas-management') }}" class="text-decoration-none">
                                <div class="card border hover-card h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="avatar-md mx-auto mb-3">
                                            <span class="avatar-title bg-success bg-gradient rounded-circle">
                                                <i class="ri-school-line fs-4 text-white"></i>
                                            </span>
                                        </div>
                                        <h6 class="mb-1">Data Kelas</h6>
                                        <p class="text-muted mb-0 small">Kelola kelas & wali kelas</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endcan

                        @can('manage-guru')
                        <div class="col-lg-4 col-md-6">
                            <a href="{{ route('guru-management') }}" class="text-decoration-none">
                                <div class="card border hover-card h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="avatar-md mx-auto mb-3">
                                            <span class="avatar-title bg-info bg-gradient rounded-circle">
                                                <i class="ri-user-star-line fs-4 text-white"></i>
                                            </span>
                                        </div>
                                        <h6 class="mb-1">Data Guru</h6>
                                        <p class="text-muted mb-0 small">Kelola informasi guru</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endcan

                        @can('manage-presensi')
                        <div class="col-lg-4 col-md-6">
                            <a href="{{ route('presensi') }}" class="text-decoration-none">
                                <div class="card border hover-card h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="avatar-md mx-auto mb-3">
                                            <span class="avatar-title bg-warning bg-gradient rounded-circle">
                                                <i class="ri-user-check-line fs-4 text-dark"></i>
                                            </span>
                                        </div>
                                        <h6 class="mb-1">Presensi</h6>
                                        <p class="text-muted mb-0 small">Kelola kehadiran siswa</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endcan

                        @can('manage-tugas')
                        <div class="col-lg-4 col-md-6">
                            <a href="{{ route('tugas-management') }}" class="text-decoration-none">
                                <div class="card border hover-card h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="avatar-md mx-auto mb-3">
                                            <span class="avatar-title bg-purple bg-gradient rounded-circle">
                                                <i class="ri-task-line fs-4 text-white"></i>
                                            </span>
                                        </div>
                                        <h6 class="mb-1">Tugas</h6>
                                        <p class="text-muted mb-0 small">Kelola tugas siswa</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endcan

                        @can('manage-nilai')
                        <div class="col-lg-4 col-md-6">
                            <a href="{{ route('nilai-management') }}" class="text-decoration-none">
                                <div class="card border hover-card h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="avatar-md mx-auto mb-3">
                                            <span class="avatar-title bg-danger bg-gradient rounded-circle">
                                                <i class="ri-award-line fs-4 text-white"></i>
                                            </span>
                                        </div>
                                        <h6 class="mb-1">Nilai</h6>
                                        <p class="text-muted mb-0 small">Kelola nilai siswa</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endcan

                        @can('manage-pelanggaran')
                        <div class="col-lg-4 col-md-6">
                            <a href="{{ route('pelanggaran-management') }}" class="text-decoration-none">
                                <div class="card border hover-card h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="avatar-md mx-auto mb-3">
                                            <span class="avatar-title bg-dark bg-gradient rounded-circle">
                                                <i class="ri-alert-line fs-4 text-white"></i>
                                            </span>
                                        </div>
                                        <h6 class="mb-1">Pelanggaran</h6>
                                        <p class="text-muted mb-0 small">Data pelanggaran siswa</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endcan

                        @can('manage-curhat')
                        <div class="col-lg-4 col-md-6">
                            <a href="{{ route('guru.curhat-siswa-management') }}" class="text-decoration-none">
                                <div class="card border hover-card h-100">
                                    <div class="card-body text-center p-3">
                                        <div class="avatar-md mx-auto mb-3">
                                            <span class="avatar-title bg-secondary bg-gradient rounded-circle">
                                                <i class="ri-chat-heart-line fs-4"></i>
                                            </span>
                                        </div>
                                        <h6 class="mb-1">Curhat BK</h6>
                                        <p class="text-muted mb-0 small">Penanganan konseling</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- System Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <span class="avatar-title bg-success bg-gradient rounded-circle">
                                <i class="ri-information-line"></i>
                            </span>
                        </div>
                        <h5 class="card-title mb-0">Informasi Sistem</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-gradient-primary text-white rounded-circle fs-2">
                                <i class="ri-graduation-cap-line"></i>
                            </div>
                        </div>
                        <h5 class="mb-1">DigiClass v2.0</h5>
                        <p class="text-muted mb-0">Sistem Manajemen Sekolah Digital</p>
                    </div>
                    
                    <div class="border-top pt-3">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="p-2">
                                    <h6 class="mb-1 text-primary">{{ Auth::user()->roles->first()->name ?? 'User' }}</h6>
                                    <p class="text-muted mb-0 small">Role Aktif</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2">
                                    <h6 class="mb-1 text-success">{{ now()->format('H:i') }}</h6>
                                    <p class="text-muted mb-0 small">Waktu Login</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Tools Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <span class="avatar-title bg-warning bg-gradient rounded-circle">
                                <i class="ri-tools-line"></i>
                            </span>
                        </div>
                        <h5 class="card-title mb-0">Tools Cepat</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @can('import-data')
                        <a href="{{ route('import-management') }}" class="btn btn-outline-primary btn-sm">
                            <i class="ri-file-upload-line me-2"></i>Import Data Excel
                        </a>
                        @endcan
                        
                        @can('view-reports')
                        <a href="{{ route('rekap-presensi') }}" class="btn btn-outline-success btn-sm">
                            <i class="ri-file-chart-line me-2"></i>Rekap Presensi
                        </a>
                        @endcan
                        
                        @can('view-statistics')
                        <a href="{{ route('statistik-management') }}" class="btn btn-outline-info btn-sm">
                            <i class="ri-bar-chart-line me-2"></i>Statistik Sekolah
                        </a>
                        @endcan
                        
                        @can('manage-surat')
                        <a href="{{ route('surat-management') }}" class="btn btn-outline-warning btn-sm">
                            <i class="ri-file-text-line me-2"></i>Surat Otomatis
                        </a>
                        @endcan
                    </div>
                    
                    <div class="border-top mt-3 pt-3">
                        <div class="text-center">
                            <small class="text-muted">
                                <i class="ri-calendar-line me-1"></i>
                                {{ now()->format('d M Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <!-- Quick Tools -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="ri-tools-line me-2"></i>Tools Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @can('import-data')
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center py-3">
                                <i class="ri-file-excel-2-line me-2 fs-5"></i>
                                <span>Import Data Excel</span>
                            </a>
                        </div>
                        @endcan
                        
                        @can('view-presensi')
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center py-3">
                                <i class="ri-file-list-3-line me-2 fs-5"></i>
                                <span>Rekap Presensi</span>
                            </a>
                        </div>
                        @endcan
                        
                        @can('view-reports')
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-info w-100 d-flex align-items-center justify-content-center py-3">
                                <i class="ri-bar-chart-line me-2 fs-5"></i>
                                <span>Statistik Sekolah</span>
                            </a>
                        </div>
                        @endcan
                        
                        @can('manage-surat')
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-warning w-100 d-flex align-items-center justify-content-center py-3">
                                <i class="ri-file-text-line me-2 fs-5"></i>
                                <span>Surat Otomatis</span>
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Notifications, Activities, and Weather -->
     <div class="row mt-4">
         <div class="col-lg-4">
             <div class="card border-0 shadow-sm h-100">
                 <div class="card-header bg-gradient-warning text-white border-0">
                     <h5 class="card-title mb-0">
                         <i class="ri-notification-3-line me-2"></i>Notifikasi
                     </h5>
                 </div>
                 <div class="card-body">
                     @foreach($this->getNotifications() as $notification)
                     <div class="d-flex align-items-start mb-3 p-3 rounded" style="background-color: rgba({{ $notification['type'] === 'info' ? '13, 110, 253' : ($notification['type'] === 'warning' ? '255, 193, 7' : '25, 135, 84') }}, 0.1);">
                         <div class="flex-shrink-0 me-3">
                             <i class="{{ $notification['icon'] }} fs-4 text-{{ $notification['type'] }}"></i>
                         </div>
                         <div class="flex-grow-1">
                             <h6 class="mb-1 fw-bold">{{ $notification['title'] }}</h6>
                             <p class="mb-0 text-muted small">{{ $notification['message'] }}</p>
                         </div>
                     </div>
                     @endforeach
                     
                     @if(empty($this->getNotifications()))
                     <div class="text-center py-4">
                         <i class="ri-notification-off-line fs-1 text-muted"></i>
                         <p class="text-muted mt-2">Tidak ada notifikasi baru</p>
                     </div>
                     @endif
                 </div>
             </div>
         </div>
         
         <div class="col-lg-4">
             <div class="card border-0 shadow-sm h-100">
                 <div class="card-header bg-gradient-info text-white border-0">
                     <h5 class="card-title mb-0">
                         <i class="ri-time-line me-2"></i>Aktivitas Terbaru
                     </h5>
                 </div>
                 <div class="card-body">
                     @foreach($recentActivities as $activity)
                     <div class="d-flex align-items-center mb-3 p-2 rounded hover-bg-light">
                         <div class="flex-shrink-0 me-3">
                             <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                  style="width: 40px; height: 40px; background-color: rgba({{ $activity['type'] === 'success' ? '25, 135, 84' : ($activity['type'] === 'info' ? '13, 110, 253' : ($activity['type'] === 'warning' ? '255, 193, 7' : '108, 117, 125')) }}, 0.2);">
                                 <i class="{{ $activity['icon'] }} text-{{ $activity['type'] }}"></i>
                             </div>
                         </div>
                         <div class="flex-grow-1">
                             <p class="mb-0 fw-medium">{{ $activity['activity'] }}</p>
                             <small class="text-muted">{{ $activity['time'] }}</small>
                         </div>
                     </div>
                     @endforeach
                 </div>
             </div>
         </div>
         
         <div class="col-lg-4">
             @livewire('shared.weather-widget')
         </div>
     </div>
</div>