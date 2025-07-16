<div>
    @push('styles')
    <style>
        .qr-scanner-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }
        
        #qr-video {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #dee2e6;
        }
        
        .scanner-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 200px;
            border: 2px solid #007bff;
            border-radius: 8px;
            pointer-events: none;
        }
        
        .scanner-overlay::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border: 2px solid rgba(0, 123, 255, 0.3);
            border-radius: 8px;
            animation: scanner-pulse 2s infinite;
        }
        
        @keyframes scanner-pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        .stats-card {
            transition: transform 0.2s;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
        }
        
        .presensi-item {
            transition: all 0.2s;
            border-left: 4px solid transparent;
        }
        
        .presensi-item:hover {
            background-color: #f8f9fa;
            border-left-color: #007bff;
        }
        
        .status-hadir { border-left-color: #28a745 !important; }
        .status-terlambat { border-left-color: #ffc107 !important; }
        .status-alpha { border-left-color: #dc3545 !important; }
        .status-izin { border-left-color: #17a2b8 !important; }
        .status-sakit { border-left-color: #6c757d !important; }
    </style>
    @endpush

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="text-primary mb-2">
                <i class="mdi mdi-qrcode-scan me-2"></i>
                Presensi Siswa
            </h2>
            <p class="text-muted">Scan QR Code <strong>NIS Siswa</strong> untuk mencatat kehadiran</p>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="mdi mdi-information me-2"></i>
                <strong>Cara Penggunaan:</strong> 
                1. Pilih jadwal yang aktif 
                2. Klik "Inisialisasi Presensi" 
                3. Siswa scan QR code yang berisi NIS mereka
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if($showAlert)
        <div class="alert alert-{{ $alertType == 'error' ? 'danger' : $alertType }} alert-dismissible fade show" role="alert">
            <i class="mdi mdi-{{ $alertType == 'success' ? 'check-circle' : ($alertType == 'error' ? 'alert-circle' : 'information') }} me-2"></i>
            {{ $alertMessage }}
            <button type="button" class="btn-close" wire:click="hideAlert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary bg-soft">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="mdi mdi-account-group text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $totalPresensi }}</h5>
                            <p class="text-muted mb-0">Total Presensi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success bg-soft">
                                <span class="avatar-title rounded-circle bg-success">
                                    <i class="mdi mdi-check-circle text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $totalHadir }}</h5>
                            <p class="text-muted mb-0">Hadir</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning bg-soft">
                                <span class="avatar-title rounded-circle bg-warning">
                                    <i class="mdi mdi-clock-alert text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $totalTerlambat }}</h5>
                            <p class="text-muted mb-0">Terlambat</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-danger bg-soft">
                                <span class="avatar-title rounded-circle bg-danger">
                                    <i class="mdi mdi-close-circle text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $totalAlpha }}</h5>
                            <p class="text-muted mb-0">Alpha</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- QR Scanner Section -->
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-qrcode-scan me-2"></i>
                        Scanner NIS Siswa
                    </h5>
                    <small class="text-white-50">Scan QR Code yang berisi NIS siswa</small>
                </div>
                <div class="card-body">
                    <!-- Date and Schedule Selection -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal</label>
                            <input type="date" class="form-control" wire:model.live="selectedDate">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jadwal</label>
                            <select class="form-select" wire:model.live="selectedJadwal">
                                <option value="">Semua Jadwal</option>
                                @foreach($jadwalList as $jadwal)
                                    <option value="{{ $jadwal->id }}">
                                        {{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->mataPelajaran->nama_mapel }} ({{ $jadwal->kelas->nama_kelas }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- QR Scanner -->
                    <div class="qr-scanner-container mb-3">
                        <video id="qr-video" autoplay muted playsinline></video>
                        <div class="scanner-overlay"></div>
                    </div>

                    <!-- Scanner Controls -->
                    <div class="text-center">
                        <button type="button" class="btn btn-primary me-2" id="start-scanner">
                            <i class="mdi mdi-play me-1"></i> Mulai Scan
                        </button>
                        <button type="button" class="btn btn-secondary" id="stop-scanner">
                            <i class="mdi mdi-stop me-1"></i> Berhenti
                        </button>
                    </div>

                    <!-- Initialize Presensi Section -->
                    @if($selectedJadwal)
                        <hr>
                        <div class="text-center">
                            <h6 class="text-muted mb-2">Inisialisasi Presensi untuk Jadwal</h6>
                            <button type="button" class="btn btn-success" wire:click="initializePresensiForJadwal({{ $selectedJadwal }})">
                                <i class="mdi mdi-account-multiple-plus me-1"></i> Inisialisasi Presensi
                            </button>
                            <p class="text-muted small mt-2 mb-0">
                                <i class="mdi mdi-information me-1"></i>
                                Siswa scan QR code yang berisi <strong>NIS mereka</strong>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Student Attendance List -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="mdi mdi-format-list-bulleted me-2"></i>
                            Daftar Presensi Hari Ini
                        </h5>
                        <small class="text-muted">{{ Carbon\Carbon::parse($selectedDate)->locale('id')->isoFormat('dddd, D MMMM Y') }}</small>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($presensiList) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($presensiList as $presensi)
                                <div class="list-group-item presensi-item status-{{ $presensi->status }} border-0 px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar avatar-sm">
                                                <div class="avatar-initial bg-{{ $presensi->status == 'hadir' ? 'success' : ($presensi->status == 'terlambat' ? 'warning' : 'danger') }} rounded-circle">
                                                    {{ substr($presensi->siswa->nama_siswa, 0, 1) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $presensi->siswa->nama_siswa }}</h6>
                                                    <p class="text-muted mb-1 small">
                                                        {{ $presensi->jadwal->mataPelajaran->nama_mapel }} - {{ $presensi->jadwal->kelas->nama_kelas }}
                                                    </p>
                                                    <p class="text-muted mb-0 small">
                                                        <i class="mdi mdi-clock-outline me-1"></i>
                                                        @if($presensi->jam_masuk)
                                                            Masuk: {{ $presensi->jam_masuk }}
                                                        @else
                                                            Belum presensi
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge bg-{{ $presensi->status == 'hadir' ? 'success' : ($presensi->status == 'terlambat' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($presensi->status) }}
                                                    </span>
                                                    @if($presensi->keterangan)
                                                        <p class="text-muted mb-0 small mt-1">{{ $presensi->keterangan }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-account-off text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">Belum ada presensi</h5>
                            <p class="text-muted">Pilih jadwal dan inisialisasi presensi untuk memulai</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script>
        let video = document.getElementById('qr-video');
        let canvas = document.createElement('canvas');
        let context = canvas.getContext('2d');
        let scanning = false;
        let stream = null;
        let lastScannedCode = null;
        let lastScanTime = 0;
        let scanCooldown = 3000; // 3 detik cooldown

        document.getElementById('start-scanner').addEventListener('click', startScanner);
        document.getElementById('stop-scanner').addEventListener('click', stopScanner);

        async function startScanner() {
            try {
                // Reset state
                lastScannedCode = null;
                lastScanTime = 0;
                
                // Stop existing stream if any
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
                
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        facingMode: 'environment',
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    } 
                });
                
                video.srcObject = stream;
                await video.play();
                
                scanning = true;
                requestAnimationFrame(scanQR);
                
                document.getElementById('start-scanner').disabled = true;
                document.getElementById('stop-scanner').disabled = false;
                
                console.log('Scanner dimulai');
            } catch (err) {
                console.error('Error mengakses kamera:', err);
                alert('Tidak dapat mengakses kamera. Pastikan browser memiliki izin kamera.');
            }
        }

        function stopScanner() {
            scanning = false;
            
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            
            video.srcObject = null;
            
            // Reset state
            lastScannedCode = null;
            lastScanTime = 0;
            
            document.getElementById('start-scanner').disabled = false;
            document.getElementById('stop-scanner').disabled = true;
            
            console.log('Scanner dihentikan');
        }

        function scanQR() {
            if (!scanning) return;
            
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.height = video.videoHeight;
                canvas.width = video.videoWidth;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height);
                
                if (code) {
                    const currentTime = Date.now();
                    
                    // Cek apakah ini kode yang sama dalam waktu cooldown
                    if (lastScannedCode === code.data && (currentTime - lastScanTime) < scanCooldown) {
                        // Lanjutkan scanning tanpa memproses
                        requestAnimationFrame(scanQR);
                        return;
                    }
                    
                    console.log('QR Code terdeteksi:', code.data);
                    
                    // Update state
                    lastScannedCode = code.data;
                    lastScanTime = currentTime;
                    
                    // Hentikan scanning sementara untuk memproses
                    scanning = false;
                    
                    // Proses QR code
                    @this.call('processQrScan', code.data).then(() => {
                        // Restart scanning setelah proses selesai
                        setTimeout(() => {
                            if (stream && video.srcObject) {
                                scanning = true;
                                requestAnimationFrame(scanQR);
                            }
                        }, 1500);
                    }).catch((error) => {
                        console.error('Error processing QR:', error);
                        // Restart scanning meskipun ada error
                        setTimeout(() => {
                            if (stream && video.srcObject) {
                                scanning = true;
                                requestAnimationFrame(scanQR);
                            }
                        }, 1500);
                    });
                    
                    return;
                }
            }
            
            requestAnimationFrame(scanQR);
        }

        // Auto hide alert setelah 5 detik
        document.addEventListener('livewire:init', () => {
            Livewire.on('hide-alert', () => {
                setTimeout(() => {
                    @this.call('hideAlert');
                }, 5000);
            });
        });
    </script>
    @endpush
</div>