<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lisensi Tidak Valid - DigiClass</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .license-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 3rem;
            max-width: 500px;
            text-align: center;
        }
        .license-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1.5rem;
        }
        .btn-contact {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-contact:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            color: white;
        }
    </style>
</head>
<body>
    <div class="license-card">
        <div class="license-icon">
            <i class="ri-error-warning-line"></i>
        </div>
        
        <h2 class="mb-3 text-dark">Lisensi Tidak Valid</h2>
        
        <p class="text-muted mb-4">
            Maaf, lisensi untuk website ini tidak valid atau telah kedaluwarsa. 
            Silakan hubungi administrator untuk mendapatkan lisensi yang valid.
        </p>
        
        <div class="alert alert-danger mb-4">
            <i class="ri-shield-cross-line me-2"></i>
            <strong>Domain:</strong> {{ request()->getHost() }}<br>
            <strong>Status:</strong> Lisensi tidak ditemukan atau tidak valid
        </div>
        
        <div class="d-grid gap-2">
            <a href="mailto:admin@digiclass.com" class="btn btn-contact">
                <i class="ri-mail-line me-2"></i>
                Hubungi Administrator
            </a>
            
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('license-management') }}" class="btn btn-outline-primary">
                        <i class="ri-settings-3-line me-2"></i>
                        Kelola Lisensi
                    </a>
                @endif
            @endauth
        </div>
        
        <div class="mt-4 pt-3 border-top">
            <small class="text-muted">
                <i class="ri-information-line me-1"></i>
                Sistem Manajemen Sekolah Digital - DigiClass
            </small>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>