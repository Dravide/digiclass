<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'DigiClass - Manajemen Kelas Digital')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistem Manajemen Kelas Digital" name="description" />
    <meta content="DigiClass" name="author" />
    
    <!-- SEO Meta Tags -->
    <meta name="keywords" content="digiclass, manajemen kelas digital, sistem sekolah, SMPN 1 Cipanas, pendidikan digital, e-learning, presensi online, perpustakaan digital">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Indonesian">
    <meta name="revisit-after" content="7 days">
    <meta name="distribution" content="global">
    <meta name="rating" content="general">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('title', 'DigiClass - Manajemen Kelas Digital')">
    <meta property="og:description" content="Sistem Manajemen Kelas Digital SMPN 1 Cipanas - Platform terpadu untuk mengelola kegiatan akademik, presensi, perpustakaan digital, dan komunikasi sekolah.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('assets/images/logo-dark.png') }}">
    <meta property="og:image:alt" content="Logo DigiClass SMPN 1 Cipanas">
    <meta property="og:site_name" content="DigiClass">
    <meta property="og:locale" content="id_ID">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'DigiClass - Manajemen Kelas Digital')">
    <meta name="twitter:description" content="Sistem Manajemen Kelas Digital SMPN 1 Cipanas - Platform terpadu untuk mengelola kegiatan akademik, presensi, perpustakaan digital, dan komunikasi sekolah.">
    <meta name="twitter:image" content="{{ asset('assets/images/logo-dark.png') }}">
    <meta name="twitter:image:alt" content="Logo DigiClass SMPN 1 Cipanas">
    
    <!-- Additional SEO Meta Tags -->
    <meta name="theme-color" content="#667eea">
    <meta name="msapplication-TileColor" content="#667eea">
    <meta name="application-name" content="DigiClass">
    <meta name="apple-mobile-web-app-title" content="DigiClass">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
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
    <!-- Select2 CSS -->
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    
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
    <div class="d-flex flex-column min-vh-100">
        <!-- Header with Logo - Fixed/Floating -->
        <header class="bg-white shadow-sm py-3 position-fixed w-100" style="top: 0; z-index: 1030;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 text-center">
                        <a href="{{ url('/') }}" class="text-decoration-none">
                            <img src="{{ asset('assets/images/logo-dark.png') }}" 
                                 alt="Logo SMPN 1 Cipanas" 
                                 class="img-fluid my-3" 
                                 style="max-height: 60px;">
                        </a>
                        

                    </div>
                </div>
            </div>
        </header>
        
        <!-- Main Content with top padding to account for fixed header -->
        <main class="flex-grow-1" style="padding-top: 140px; padding-bottom: 60px;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </main>
        
        <!-- Footer - Always at bottom -->
        <footer class="bg-white border-top py-4 mt-auto">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center mb-3 mb-lg-0">
                            <img src="{{ asset('assets/images/logo-dark.png') }}" 
                                 alt="Logo SMPN 1 Cipanas" 
                                 class="me-3" 
                                 style="max-height: 40px;">
                            <div>
                                <h6 class="mb-1 text-dark">SMPN 1 Cipanas</h6>
                                <p class="text-muted mb-0 small">Sistem Manajemen Kelas Digital</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="text-lg-end">
                            <p class="text-muted mb-2 small">
                                <i class="ri-map-pin-line me-1"></i>
                                Jl. Raya Cipanas No. 123, Cipanas, Cianjur, Jawa Barat
                            </p>
                            <p class="text-muted mb-2 small">
                                <i class="ri-phone-line me-1"></i>
                                (0263) 123456 | 
                                <i class="ri-mail-line me-1"></i>
                                info@smpn1cipanas.sch.id
                            </p>
                            <p class="text-muted mb-0 small">
                                © {{ date('Y') }} DigiClass. All rights reserved.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
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
    <!-- Select2 JS -->
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>

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