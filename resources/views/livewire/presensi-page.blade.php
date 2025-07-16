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
        
        /* Radio Button Styling */
        .btn-group-sm .btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .presensi-controls {
            min-width: 200px;
        }

        @media (max-width: 768px) {
            .presensi-controls {
                min-width: 100%;
                margin-top: 10px;
            }
            
            .btn-group {
                width: 100%;
            }
            
            .btn-group .btn {
                flex: 1;
            }
        }
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
                                Siswa dapat scan QR code <strong>NIS mereka</strong> atau guru dapat mengubah status secara manual
                            </p>
                        </div>
                    @endif

                    <!-- Manual Attendance Info -->
                    @if(count($presensiList) > 0)
                        <hr>
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-information me-2"></i>
                                <div>
                                    <strong>Cara Menggunakan:</strong>
                                    <ul class="mb-0 mt-1">
                                        <li>Siswa dapat scan QR code NIS mereka untuk presensi otomatis</li>
                                        <li>Atau gunakan tombol radio di sebelah kanan untuk mengubah status secara manual</li>
                                    </ul>
                                </div>
                            </div>
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
                                                <div class="text-end presensi-controls">
                                    <span class="badge bg-{{ $presensi->status == 'hadir' ? 'success' : ($presensi->status == 'terlambat' ? 'warning' : 'danger') }} mb-2">
                                        {{ ucfirst($presensi->status) }}
                                    </span>
                                    @if($presensi->keterangan)
                                        <p class="text-muted mb-2 small">{{ $presensi->keterangan }}</p>
                                    @endif
                                    
                                    <!-- Radio Button untuk Presensi Manual -->
                                    <div class="mt-2">
                                        <small class="text-muted d-block mb-1">Ubah Status:</small>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <input type="radio" class="btn-check" name="presensi_{{ $presensi->id }}" id="hadir_{{ $presensi->id }}" 
                                                   wire:click="updatePresensiManual({{ $presensi->id }}, 'hadir')" 
                                                   {{ $presensi->status == 'hadir' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-success btn-sm" for="hadir_{{ $presensi->id }}">
                                                <i class="mdi mdi-check"></i> Hadir
                                            </label>

                                            <input type="radio" class="btn-check" name="presensi_{{ $presensi->id }}" id="terlambat_{{ $presensi->id }}" 
                                                   wire:click="updatePresensiManual({{ $presensi->id }}, 'terlambat')" 
                                                   {{ $presensi->status == 'terlambat' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-warning btn-sm" for="terlambat_{{ $presensi->id }}">
                                                <i class="mdi mdi-clock-alert"></i> Terlambat
                                            </label>

                                            <input type="radio" class="btn-check" name="presensi_{{ $presensi->id }}" id="alpha_{{ $presensi->id }}" 
                                                   wire:click="updatePresensiManual({{ $presensi->id }}, 'alpha')" 
                                                   {{ $presensi->status == 'alpha' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger btn-sm" for="alpha_{{ $presensi->id }}">
                                                <i class="mdi mdi-close"></i> Alpha
                                            </label>
                                        </div>
                                    </div>
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
    <script src="https://unpkg.com/jsqr@1.4.0/dist/jsQR.js"></script>
    <script>
        let scanning = false;
        let processing = false; // Flag untuk mencegah multiple processing
        let lastScannedCode = null;
        let lastScanTime = 0;
        let scanCooldown = 3000; // 3 detik cooldown
        let video = null;
        let canvas = null;
        let context = null;
        let stream = null;
        let streamKeepAlive = null; // Keep-alive untuk VPS
        let isVPS = window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1';
        let scanInterval = isVPS ? 100 : 50; // Slower scanning di VPS

        document.getElementById('start-scanner').addEventListener('click', startScanner);
        document.getElementById('stop-scanner').addEventListener('click', stopScanner);

        async function startScanner() {
            try {
                // Stop any existing stream first
                if (stream) {
                    stream.getTracks().forEach(track => {
                        track.stop();
                        console.log('Stopping existing track:', track.kind);
                    });
                    stream = null;
                }
                
                // Reset state
                processing = false;
                lastScannedCode = null;
                lastScanTime = 0;
                scanning = true;
                
                // Get video element
                video = document.getElementById('qr-video');
                
                // Clear any existing video source
                if (video.srcObject) {
                    video.srcObject = null;
                }
                
                // Create canvas for QR detection
                if (!canvas) {
                    canvas = document.createElement('canvas');
                    context = canvas.getContext('2d');
                }
                
                // Request camera access with fallback constraints
                let constraints = {
                    video: {
                        facingMode: 'environment',
                        width: { ideal: 640, min: 320 },
                        height: { ideal: 480, min: 240 }
                    }
                };
                
                try {
                    stream = await navigator.mediaDevices.getUserMedia(constraints);
                } catch (envError) {
                    console.warn('Environment camera failed, trying any camera:', envError);
                    // Fallback to any available camera
                    constraints = {
                        video: {
                            width: { ideal: 640, min: 320 },
                            height: { ideal: 480, min: 240 }
                        }
                    };
                    stream = await navigator.mediaDevices.getUserMedia(constraints);
                }
                
                video.srcObject = stream;
                
                // Wait for video to load with timeout
                await new Promise((resolve, reject) => {
                    const timeout = setTimeout(() => {
                        reject(new Error('Video load timeout'));
                    }, 10000); // 10 second timeout
                    
                    video.onloadedmetadata = () => {
                        clearTimeout(timeout);
                        video.play().then(() => {
                            console.log('Video started playing');
                            resolve();
                        }).catch(reject);
                    };
                    
                    video.onerror = (err) => {
                        clearTimeout(timeout);
                        reject(err);
                    };
                });
                
                // Start scanning
                scanQR();
                
                // Setup enhanced event listeners untuk VPS
                setupVideoEventListeners();
                
                // Start keep-alive untuk VPS
                if (isVPS) {
                    startStreamKeepAlive();
                }
                
                document.getElementById('start-scanner').disabled = true;
                document.getElementById('stop-scanner').disabled = false;
                
                console.log('Scanner dimulai dengan stream:', stream.getTracks().map(t => t.kind));
            } catch (err) {
                console.error('Error mengakses kamera:', err);
                alert('Tidak dapat mengakses kamera. Pastikan browser memiliki izin kamera dan menggunakan HTTPS. Error: ' + err.message);
                scanning = false;
                
                // Reset UI state on error
                document.getElementById('start-scanner').disabled = false;
                document.getElementById('stop-scanner').disabled = true;
            }
        }

        function stopScanner() {
            scanning = false;
            
            // Stop video stream with proper cleanup
            if (stream) {
                stream.getTracks().forEach(track => {
                    track.stop();
                    console.log('Stopped track:', track.kind, 'State:', track.readyState);
                });
                stream = null;
            }
            
            // Clear video with proper cleanup
            if (video) {
                video.pause();
                video.srcObject = null;
                video.load(); // Reset video element
            }
            
            // Reset state
            processing = false;
            lastScannedCode = null;
            lastScanTime = 0;
            
            // Stop keep-alive
            if (streamKeepAlive) {
                clearInterval(streamKeepAlive);
                streamKeepAlive = null;
            }
            
            document.getElementById('start-scanner').disabled = false;
            document.getElementById('stop-scanner').disabled = true;
            
            console.log('Scanner dihentikan dan resources dibersihkan');
        }

        function scanQR() {
            if (!scanning || !video || video.readyState !== video.HAVE_ENOUGH_DATA) {
                if (scanning) {
                    requestAnimationFrame(scanQR);
                }
                return;
            }
            
            // Set canvas size to match video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            // Draw video frame to canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Get image data
            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            
            // Scan for QR code dengan options untuk VPS
            const options = {
                inversionAttempts: isVPS ? 'dontInvert' : 'attemptBoth' // Optimasi untuk VPS
            };
            const code = jsQR(imageData.data, imageData.width, imageData.height, options);
            
            if (code) {
                const currentTime = Date.now();
                
                // Cek apakah sedang processing atau dalam cooldown
                if (processing || (lastScannedCode === code.data && (currentTime - lastScanTime) < scanCooldown)) {
                    // Skip processing tapi tetap lanjutkan scanning
                    if (scanning) {
                        requestAnimationFrame(scanQR);
                    }
                    return;
                }
                
                console.log('QR Code terdeteksi:', code.data);
                
                // Set flag processing dan update state
                processing = true;
                lastScannedCode = code.data;
                lastScanTime = currentTime;
                
                // Proses QR code dengan handling yang lebih baik
                @this.call('processQrScan', code.data).then(() => {
                    console.log('QR Code berhasil diproses:', code.data);
                    processing = false; // Reset flag processing
                }).catch((error) => {
                    console.error('Error processing QR:', error);
                    processing = false; // Reset flag processing
                    // Jika ada error, reset cooldown untuk mencoba lagi
                    lastScannedCode = null;
                    lastScanTime = 0;
                });
            }
            
            // Continue scanning dengan interval yang disesuaikan
            if (scanning) {
                if (isVPS) {
                    setTimeout(() => {
                        if (scanning) requestAnimationFrame(scanQR);
                    }, scanInterval);
                } else {
                    requestAnimationFrame(scanQR);
                }
            }
        }

        // Auto hide alert setelah 5 detik
        document.addEventListener('livewire:init', () => {
            Livewire.on('hide-alert', () => {
                setTimeout(() => {
                    @this.call('hideAlert');
                }, 5000);
            });
        });

        // Enhanced cleanup for various scenarios
        window.addEventListener('beforeunload', function() {
            stopScanner();
        });
        
        // Cleanup when page becomes hidden (mobile browsers)
        document.addEventListener('visibilitychange', function() {
            if (document.hidden && scanning) {
                console.log('Page hidden, stopping scanner');
                stopScanner();
            }
        });
        
        // Cleanup on page focus loss
        window.addEventListener('blur', function() {
            if (scanning) {
                console.log('Window lost focus, stopping scanner');
                stopScanner();
            }
        });
        
        // Auto-restart when page becomes visible again
        window.addEventListener('focus', function() {
            if (!scanning && document.getElementById('start-scanner').disabled === false) {
                console.log('Window focused, scanner ready to restart');
            }
        });

        // Keep-alive function untuk menjaga stream tetap aktif di VPS
        function startStreamKeepAlive() {
            streamKeepAlive = setInterval(() => {
                if (video && video.srcObject && scanning) {
                    // Trigger small canvas draw untuk keep stream active
                    const tempCanvas = document.createElement('canvas');
                    const tempContext = tempCanvas.getContext('2d');
                    tempCanvas.width = 1;
                    tempCanvas.height = 1;
                    try {
                        tempContext.drawImage(video, 0, 0, 1, 1);
                    } catch (e) {
                        console.warn('Keep-alive draw failed:', e);
                    }
                }
            }, isVPS ? 500 : 1000); // More frequent di VPS
        }

        // Enhanced stream health check
        function checkStreamHealth() {
            if (scanning && video && (!video.srcObject || video.srcObject.getTracks().length === 0)) {
                console.warn('Stream lost, attempting recovery...');
                restartScanner();
            }
        }

        // Auto-restart scanner function
        function restartScanner() {
            console.log('Restarting scanner...');
            stopScanner();
            setTimeout(() => {
                if (!scanning) { // Only restart if not already scanning
                    startScanner();
                }
            }, 1000);
        }

        // Enhanced video event listeners untuk VPS
        function setupVideoEventListeners() {
            if (video) {
                video.addEventListener('suspend', () => {
                    console.warn('Video suspended, checking stream health...');
                    if (scanning) {
                        setTimeout(checkStreamHealth, 1000);
                    }
                });

                video.addEventListener('abort', () => {
                    console.warn('Video aborted, restarting scanner...');
                    if (scanning) {
                        restartScanner();
                    }
                });

                video.addEventListener('error', (e) => {
                    console.error('Video error:', e);
                    if (scanning) {
                        restartScanner();
                    }
                });

                video.addEventListener('ended', () => {
                    console.warn('Video ended, restarting scanner...');
                    if (scanning) {
                        restartScanner();
                    }
                });
            }
        }

    </script>
    @endpush
</div>