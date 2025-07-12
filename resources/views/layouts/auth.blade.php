<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'DigiClass - Sistem Manajemen Kelas Digital')</title>
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

<body>
    <div class="auth-maintenance d-flex align-items-center min-vh-100">
        <div class="bg-overlay bg-light"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="auth-full-page-content d-flex min-vh-100 py-sm-5 py-4">
                        <div class="w-100">
                            <div class="d-flex flex-column h-100 py-0 py-xl-3">
                                <div class="text-center mb-4">
                                    <a href="{{ route('dashboard') }}" class="">
                                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="22" class="auth-logo logo-dark mx-auto">
                                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="22" class="auth-logo logo-light mx-auto">
                                    </a>
                                    <p class="text-muted mt-2">Sistem Manajemen Kelas Digital</p>
                                </div>

                                {{ $slot }}
                                
                                <div class="mt-5 text-center">
                                    <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> DigiClass. Dibuat dengan <i class="ri-heart-fill text-danger"></i> untuk pendidikan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
    </div>
    
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
            
            // Re-initialize waves effect
            if (typeof Waves !== 'undefined') {
                Waves.init();
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeComponents();
        });
        
        // Handle login success
        document.addEventListener('login-success', function() {
            Swal.fire({
                icon: 'success',
                title: 'Login Berhasil!',
                text: 'Selamat datang di DigiClass',
                timer: 1500,
                showConfirmButton: false
            });
        });
    </script>
</body>

</html>