<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'DigiClass - Manajemen Kelas Digital')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistem Manajemen Kelas Digital" name="description" />
    <meta content="DigiClass" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Layout Js -->
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    

    
    @stack('styles')
    @livewireStyles
</head>

<body data-sidebar="colored">
    <!-- Begin page -->
    <div id="layout-wrapper">
        
        @include('layouts.partials.header')
        
        @include('layouts.partials.sidebar')

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @include('layouts.partials.breadcrumb')
                    
                    {{ $slot }}
                </div>
            </div>
            
            @include('layouts.partials.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    
    @include('layouts.partials.rightbar')

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
     <!-- SweetAlert2 JS -->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    
    @stack('scripts')
    @livewireScripts
    
    <script>
        // Re-initialize components after Livewire navigation
        document.addEventListener('livewire:navigated', () => {
            initializeComponents();
        });
        
        // Handle livewire:load for compatibility with older Livewire versions
        document.addEventListener('livewire:load', () => {
            initializeComponents();
        });
        
        // Handle Livewire component updates
        document.addEventListener('livewire:updated', () => {
            initializeComponents();
        });
        
        function initializeComponents() {
            // Re-initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Re-initialize Bootstrap popovers
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
            
            // Re-initialize right bar toggle
            $('.right-bar-toggle').off('click').on('click', function(e) {
                $('body').toggleClass('right-bar-enabled');
            });
            
            // Re-initialize vertical menu button
            $('.vertical-menu-btn').off('click').on('click', function(e) {
                e.preventDefault();
                $('body').toggleClass('sidebar-enable');
                if ($(window).width() >= 992) {
                    $('body').toggleClass('vertical-collpsed');
                } else {
                    $('body').removeClass('vertical-collpsed');
                }
            });
            
            // Re-initialize waves effect
            if (typeof Waves !== 'undefined') {
                Waves.init();
            }
            
            // Re-initialize MetisMenu for sidebar
            if (typeof $.fn.metisMenu !== 'undefined') {
                $('#side-menu').metisMenu();
            }
            

            
            // Re-initialize body click handler for right bar
            $(document).off('click', 'body').on('click', 'body', function(e) {
                if ($(e.target).closest('.right-bar-toggle, .right-bar').length === 0) {
                    $('body').removeClass('right-bar-enabled');
                }
            });
        }
    </script>
</body>

</html>