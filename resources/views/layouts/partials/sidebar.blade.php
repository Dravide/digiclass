<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ route('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo-sm-dark.png') }}" alt="logo-sm-dark" height="24">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo-dark" height="22">
            </span>
        </a>

        <a href="{{ route('dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo-sm-light.png') }}" alt="logo-sm-light" height="24">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/logo-light.png') }}" alt="logo-light" height="22">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn" id="vertical-menu-btn">
        <i class="ri-menu-2-line align-middle"></i>
    </button>

    <div data-simplebar class="vertical-scroll">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="menu-title">Pengaturan Dasar</li>

                <li>
                    <a href="{{ route('tahun-pelajaran-management') }}" class="waves-effect">
                        <i class="ri-calendar-line"></i>
                        <span>Tahun Pelajaran</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('mata-pelajaran-management') }}" class="waves-effect">
                        <i class="ri-book-line"></i>
                        <span>Mata Pelajaran</span>
                    </a>
                </li>

                <li class="menu-title">Manajemen Data</li>

                <li>
                    <a href="{{ route('guru-management') }}" class="waves-effect">
                        <i class="ri-user-3-line"></i>
                        <span>Data Guru</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('kelas-management') }}" class="waves-effect">
                        <i class="ri-building-line"></i>
                        <span>Data Kelas</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('class-management') }}" class="waves-effect">
                        <i class="ri-group-line"></i>
                        <span>Data Siswa</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('perpustakaan-management') }}" class="waves-effect">
                        <i class="ri-book-open-line"></i>
                        <span>Data Perpustakaan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('jadwal-management') }}" class="waves-effect">
                        <i class="ri-calendar-2-line"></i>
                        <span>Jadwal Guru</span>
                    </a>
                </li>

                <li class="menu-title">Operasional</li>

                <li>
                    <a href="{{ route('presensi') }}" class="waves-effect">
                        <i class="ri-user-check-line"></i>
                        <span>Presensi Siswa</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('rekap-presensi') }}" class="waves-effect">
                        <i class="ri-file-list-3-line"></i>
                        <span>Rekap Presensi</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('tugas-management') }}" class="waves-effect">
                        <i class="ri-task-line"></i>
                        <span>Manajemen Tugas</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('nilai-management') }}" class="waves-effect">
                        <i class="ri-award-line"></i>
                        <span>Manajemen Nilai</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('rekap-nilai') }}" class="waves-effect">
                        <i class="ri-file-chart-line"></i>
                        <span>Rekap Nilai Siswa</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('import-management') }}" class="waves-effect">
                        <i class="ri-file-upload-line"></i>
                        <span>Import Data Excel</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('statistik-management') }}" class="waves-effect">
                        <i class="ri-bar-chart-line"></i>
                        <span>Statistik Sekolah</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-pie-chart-line"></i>
                        <span>Laporan</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="#">Laporan Siswa</a></li>
                        <li><a href="#">Laporan Perpustakaan</a></li>
                        <li><a href="#">Laporan Kelas</a></li>
                        <li><a href="{{ route('rekap-presensi') }}">Laporan Presensi</a></li>
                    </ul>
                </li>


            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->