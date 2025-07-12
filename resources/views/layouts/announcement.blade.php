<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Pengumuman Kelas | DigiClass</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistem Informasi Manajemen Kelas Digital" name="description" />
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
                                    <a href="{{ url('/') }}" class="">
                                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="22" class="auth-logo logo-dark mx-auto">
                                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="22" class="auth-logo logo-light mx-auto">
                                    </a>
                                    <h3 class="mt-3 text-primary">DigiClass</h3>
                                    <p class="text-muted mt-2">Sistem Informasi Manajemen Kelas Digital</p>
                                </div>

                                {{ $slot }}
                                
                                <div class="mt-5 text-center">
                                    <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> DigiClass. Sistem Manajemen Kelas Digital</p>
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

    <!-- Icon -->
    <script src="https://unicons.iconscout.com/release/v2.0.1/script/monochrome/bundle.js"></script>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    
    @livewireScripts
</body>
</html>