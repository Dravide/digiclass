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

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-medal-fill"></i>
                        <span>Manajemen Kelas</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('class-management') }}">Data Siswa</a></li>
                        <li><a href="{{ route('kelas-management') }}">Kelas</a></li>
                        <li><a href="{{ route('guru-management') }}">Guru</a></li>
                        <li><a href="{{ route('perpustakaan-management') }}">Perpustakaan</a></li>
                    </ul>
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
                    </ul>
                </li>


            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->