<!-- Header -->
<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('dashboard') }}" wire:navigate class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo-sm-dark" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-sm-dark.png') }}" alt="logo-dark" height="25">
                    </span>
                </a>

                <a href="{{ route('dashboard') }}" wire:navigate class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="logo-sm-light" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-sm-light.png') }}" alt="logo-light" height="25">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn" id="vertical-menu-btn">
                <i class="ri-menu-2-line align-middle"></i>
            </button>

            <!-- start page title -->
            <div class="page-title-box align-self-center d-none d-md-block">
                <h4 class="page-title mb-0">@yield('page-title', 'Dashboard')</h4>
            </div>
            <!-- end page title -->
        </div>

        <div class="d-flex">
            <!-- App Search-->
            <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="Cari...">
                    <span class="ri-search-line"></span>
                </div>
            </form>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                    <i class="ri-settings-2-line"></i>
                </button>
            </div>
        </div>
    </div>
</header>