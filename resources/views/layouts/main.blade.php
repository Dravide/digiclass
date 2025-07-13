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
    
    <style>
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
</head>

<body class="bg-light">
    <!-- Begin page -->
    <div class="min-vh-100">
        <!-- Header with Logo -->
        <header class="bg-primary shadow-sm py-3 mb-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 text-center">
                        <img src="{{ asset('assets/cropped-Logo-SMPN-1-Cipanas-2024.png') }}" 
                             alt="Logo SMPN 1 Cipanas" 
                             class="img-fluid" 
                             style="max-height: 80px;">
                        <h4 class="mt-2 mb-0 text-white fw-bold">DigiClass</h4>
                        <p class="text-white small mb-0">Sistem Manajemen Kelas Digital</p>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Main Content -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
    <!-- END page -->

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
        // Initialize components on page load
        document.addEventListener('DOMContentLoaded', () => {
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
    </script>
</body>

</html>