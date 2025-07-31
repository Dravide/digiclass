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
            @php
                $menuItems = \App\Helpers\MenuHelper::getMenuItems();
            @endphp
            
            <ul class="metismenu list-unstyled" id="side-menu">
                @foreach($menuItems as $section => $items)
                    <li class="menu-title">{{ $section }}</li>
                    
                    @foreach($items as $item)
                        @if(isset($item['submenu']))
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="{{ $item['icon'] }}"></i>
                                    <span>{{ $item['title'] }}</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @foreach($item['submenu'] as $subitem)
                                        <li><a href="{{ isset($subitem['route']) ? route($subitem['route']) : '#' }}">{{ $subitem['title'] }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li>
                                <a href="{{ route($item['route']) }}" class="waves-effect">
                                    <i class="{{ $item['icon'] }}"></i>
                                    <span>{{ $item['title'] }}</span>
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endforeach


            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->