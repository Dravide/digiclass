<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Presensi QR - DigiClass')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistem Presensi QR DigiClass" name="description" />
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
    <!-- Custom Theme Css -->
    <link href="{{ asset('assets/css/custom-theme.css') }}" rel="stylesheet" type="text/css" />
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- Custom Styles for Presensi QR -->
    <style>
        .text-purple {
            color: #6f42c1 !important;
        }
        .btn-purple {
            background-color: #6f42c1;
            border-color: #6f42c1;
            color: #fff;
        }
        .btn-purple:hover {
            background-color: #5a359a;
            border-color: #5a359a;
            color: #fff;
        }
        .bg-purple {
            background-color: #6f42c1 !important;
        }
        
        /* Compact Header Styles */
        #page-topbar {
            height: 50px !important;
            min-height: 50px !important;
        }
        
        .navbar-header {
            height: 50px !important;
            padding: 0 15px;
        }
        
        .navbar-brand-box {
            width: auto !important;
            min-width: auto !important;
        }
        
        .logo-sm img, .logo-lg img {
            height: 20px !important;
        }
        
        .header-item {
            height: 40px !important;
            width: 40px !important;
            font-size: 16px !important;
        }
        
        .page-title-box h4 {
            font-size: 16px !important;
            margin: 0 !important;
            line-height: 50px;
        }
        
        /* Main content adjustment */
        .main-content {
            margin-left: 0 !important;
            margin-top: 50px !important;
        }
        
        /* Full width content */
        .container-fluid {
            padding: 15px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .page-title-box {
                display: none !important;
            }
            
            .navbar-header {
                padding: 0 10px;
            }
            
            .container-fluid {
                padding: 10px;
            }
        }
        
        @media (max-width: 576px) {
            .logo-lg {
                display: none !important;
            }
            
            .header-item {
                height: 35px !important;
                width: 35px !important;
                font-size: 14px !important;
            }
        }
        
        /* Additional styling like main.blade.php */
        .hover-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
    </style>

    @stack('styles')
    @livewireStyles
</head>

<body class="bg-light">
    <!-- Begin page -->
    <div class="d-flex flex-column min-vh-100">
        <!-- Header with Logo - Fixed/Floating -->
        <header class="bg-white shadow-sm py-2 position-fixed w-100" style="top: 0; z-index: 1030;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 text-center">
                        <a href="{{ url('/') }}" class="text-decoration-none">
                            <img src="{{ asset('assets/images/logo-dark.png') }}" 
                                 alt="Logo SMPN 1 Cipanas" 
                                 class="img-fluid my-2" 
                                 style="max-height: 40px;">
                        </a>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Main Content with top padding to account for fixed header -->
        <main class="flex-grow-1" style="padding-top: 120px; padding-bottom: 40px;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </main>
    </div>
    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    
    @stack('scripts')
    @livewireScripts
    
    <script>
        // Initialize components on page load
        document.addEventListener('DOMContentLoaded', () => {
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
    </script>
</body>

</html>