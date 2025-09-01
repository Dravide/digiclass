<div>
    <!-- Main Content - Presensi QR Scanner -->
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="text-center">
                        <h4 class="header-title mb-1">Presensi QR Scanner</h4>
                        <small class="text-muted">
                            <i class="mdi mdi-clock-outline"></i> {{ now()->setTimezone('Asia/Jakarta')->format('H:i:s') }} - 
                            <span class="badge bg-{{ $jenis_presensi === 'masuk' ? 'success' : 'danger' }} badge-sm">
                                {{ ucfirst($jenis_presensi) }}
                            </span>
                        </small>
                    </div>
                </div>
                    <div class="card-body">
                        <!-- Alert Result -->
                        @if($showResult)
                            <div class="alert alert-{{ $resultType === 'success' ? 'success' : ($resultType === 'error' ? 'danger' : 'warning') }} alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-{{ $resultType === 'success' ? 'check-circle' : ($resultType === 'error' ? 'alert-circle' : 'information') }}"></i>
                                {{ $resultMessage }}
                                <button type="button" class="btn-close" wire:click="hideResult" aria-label="Close"></button>
                            </div>
                        @endif

                        <form wire:submit="prosesQrCode">
                            <!-- Hidden input for QR scanner -->
                            <input type="text" 
                                   class="form-control" 
                                   id="qr_code" 
                                   wire:model="qr_code" 
                                   style="position: absolute; left: -9999px; opacity: 0; height: 1px; width: 1px;"
                                   autofocus>

                            <div class="row">
                                <!-- Kolom Kiri: Kamera Otomatis -->
                                <div class="col-md-6">
                                    <div class="text-center mb-3">
                                        <h6 class="text-muted mb-2">
                                            <i class="mdi mdi-camera"></i> Foto Presensi
                                        </h6>
                                        <div class="position-relative">
                                            <video id="webcam-video" autoplay muted class="rounded border" style="width: 100%; max-width: 300px; height: 200px; object-fit: cover;"></video>
                                            <canvas id="webcam-canvas" class="rounded border" style="width: 100%; max-width: 300px; height: 200px; display: none;"></canvas>
                                            <div id="camera-status" class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-success">Kamera Aktif</span>
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mt-2">Foto otomatis diambil saat scan QR</small>
                                    </div>
                                </div>

                                <!-- Kolom Kanan: QR Scanner Area -->
                                <div class="col-md-6">
                                    <div class="text-center">
                                        <h6 class="text-muted mb-3">
                                            <i class="mdi mdi-qrcode-scan"></i> Scan QR Code
                                        </h6>
                                        
                                        <div class="qr-scanner-area p-4 border border-2 border-dashed rounded" style="min-height: 200px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                            <div class="d-flex flex-column justify-content-center align-items-center h-100">
                                                @if($qr_code)
                                                    <i class="mdi mdi-check-circle text-success" style="font-size: 3rem;"></i>
                                                    <p class="text-success fw-bold mt-2 mb-0">QR Code Terdeteksi!</p>
                                                    <small class="text-muted">Memproses presensi...</small>
                                                @else
                                                    <i class="mdi mdi-qrcode text-primary" style="font-size: 3rem;"></i>
                                                    <p class="text-muted mt-2 mb-0">Arahkan QR Scanner ke area ini</p>
                                                    <div class="d-flex align-items-center mt-2">
                                                        <div class="spinner-grow spinner-grow-sm text-primary me-2" role="status"></div>
                                                        <small class="text-muted">Siap untuk scan</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @error('qr_code')
                                            <div class="alert alert-danger mt-3">
                                                <i class="mdi mdi-alert-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>

    <!-- Presensi Hari Ini -->
    <div class="row justify-content-center mt-4">
        <div class="col-lg-8 col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="header-title mb-0">
                            <i class="mdi mdi-account-check"></i> Presensi Hari Ini
                        </h5>
                        <span class="badge bg-info">{{ count($presensiHariIni) }} orang</span>
                    </div>
                    <small class="text-muted">{{ now()->setTimezone('Asia/Jakarta')->format('d M Y') }}</small>
                </div>
                    <div class="card-body">
                        @if(count($presensiHariIni) > 0)
                            @php
                                $latestPresensi = $presensiHariIni[0]; // Ambil presensi terakhir (sudah diurutkan desc)
                            @endphp
                            <div class="row justify-content-center">
                                <div class="col-lg-8 col-md-10">
                                    <div class="d-flex align-items-center p-4 border rounded-3 shadow-sm bg-light">
                                        <div class="avatar-lg me-4">
                                            <span class="avatar-title bg-{{ $latestPresensi['jenis_presensi'] === 'masuk' ? 'success' : 'danger' }} text-white rounded-circle fs-2">
                                                {{ strtoupper(substr($latestPresensi['user']['name'], 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fs-4 fw-bold mb-1">{{ $latestPresensi['user']['name'] }}</div>
                                            <div class="text-muted mb-2">{{ ucfirst($latestPresensi['user']['role']) }}</div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-{{ $latestPresensi['jenis_presensi'] === 'masuk' ? 'success' : 'danger' }} fs-6 px-3 py-2">
                                                    {{ ucfirst($latestPresensi['jenis_presensi']) }}
                                                </span>
                                                @if(isset($latestPresensi['is_terlambat']) && $latestPresensi['is_terlambat'])
                                                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">Terlambat</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fs-3 fw-bold text-primary">
                                                {{ \Carbon\Carbon::parse($latestPresensi['created_at'])->setTimezone('Asia/Jakarta')->format('H:i') }}
                                            </div>
                                            <small class="text-muted">Presensi Terakhir</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(count($presensiHariIni) > 1)
                                <div class="text-center mt-3">
                                    <small class="text-muted">Dan {{ count($presensiHariIni) - 1 }} presensi lainnya hari ini</small>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="mdi mdi-account-off text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">Belum ada presensi hari ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Storage Management (Admin Only) -->
        @if(Auth::check() && Auth::user()->role === 'admin')
            <div class="row justify-content-center mt-4">
                <div class="col-lg-8 col-md-10">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="header-title mb-0">
                                <i class="mdi mdi-folder-cog"></i> Storage Management (Admin Only)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-warning w-100" wire:click="bersihkanFotoOrphan">
                                        <i class="mdi mdi-delete-sweep"></i> Hapus Foto Orphan
                                    </button>
                                    <small class="text-muted d-block mt-1">Hapus foto yang tidak terkait dengan presensi</small>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-danger w-100" wire:click="hapusSemuaFoto" 
                                            onclick="return confirm('Yakin ingin menghapus semua foto presensi?')">
                                        <i class="mdi mdi-delete-forever"></i> Hapus Semua Foto
                                    </button>
                                    <small class="text-muted d-block mt-1">Hapus semua foto presensi (hati-hati!)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
</div>

@push('styles')
<style>
    .qr-scanner-area {
        transition: all 0.3s ease;
    }
    
    .qr-scanner-area:hover {
        border-color: #0d6efd !important;
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%) !important;
    }
    
    .avatar-xs {
        width: 2rem;
        height: 2rem;
    }
    
    .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .card-header h4, .card-header h5 {
            font-size: 1rem;
        }
        
        .badge {
            font-size: 0.7rem;
        }
        
        #webcam-video, #webcam-canvas {
            height: 150px !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-hide result messages
    document.addEventListener('livewire:init', () => {
        Livewire.on('auto-hide-result', () => {
            setTimeout(() => {
                Livewire.dispatch('hideResult');
            }, 5000);
        });
        
        Livewire.on('auto-hide-error', () => {
            setTimeout(() => {
                Livewire.dispatch('hideResult');
            }, 8000);
        });
    });
    
    // QR Scanner functionality
    let webcamStream = null;
    let webcamVideo = null;
    let webcamCanvas = null;
    let webcamContext = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        webcamVideo = document.getElementById('webcam-video');
        webcamCanvas = document.getElementById('webcam-canvas');
        
        if (webcamCanvas) {
            webcamContext = webcamCanvas.getContext('2d');
            webcamCanvas.width = 300;
            webcamCanvas.height = 200;
        }
        
        // Auto-start camera
        initializeCamera();
        
        // Keep QR input focused for scanner
        maintainQrInputFocus();
    });
    
    async function initializeCamera() {
        try {
            webcamStream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    width: { ideal: 300 },
                    height: { ideal: 200 },
                    facingMode: 'environment' // Use back camera on mobile
                } 
            });
            
            if (webcamVideo) {
                webcamVideo.srcObject = webcamStream;
                webcamVideo.style.display = 'block';
            }
            
        } catch (error) {
            console.error('Error accessing camera:', error);
            const cameraStatus = document.getElementById('camera-status');
            if (cameraStatus) {
                cameraStatus.innerHTML = '<span class="badge bg-danger">Kamera Error</span>';
            }
        }
    }
    
    function maintainQrInputFocus() {
        const qrInput = document.getElementById('qr_code');
        if (!qrInput) return;
        
        // Initial focus
        qrInput.focus();
        
        // Maintain focus
        setInterval(() => {
            if (document.activeElement !== qrInput) {
                qrInput.focus();
            }
        }, 1000);
        
        // Re-focus on any click
        document.addEventListener('click', () => {
            setTimeout(() => qrInput.focus(), 10);
        });
    }
    
    // Auto-submit when QR code is scanned
    document.addEventListener('input', function(e) {
        if (e.target.id === 'qr_code') {
            const qrValue = e.target.value.trim();
            if (qrValue.length >= 8) {
                // Auto-capture photo
                setTimeout(() => {
                    capturePhoto();
                }, 100);
                
                // Auto-submit form
                setTimeout(() => {
                    @this.call('prosesQrCode');
                }, 300);
            }
        }
    });
    
    // Handle Enter key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.id === 'qr_code') {
            e.preventDefault();
            const qrValue = e.target.value.trim();
            if (qrValue.length > 0) {
                capturePhoto();
                setTimeout(() => {
                    @this.call('prosesQrCode');
                }, 200);
            }
        }
    });
    
    function capturePhoto() {
        if (webcamVideo && webcamContext && webcamVideo.videoWidth > 0) {
            // Draw video frame to canvas
            webcamContext.drawImage(webcamVideo, 0, 0, 300, 200);
            
            // Convert to base64
            const photoData = webcamCanvas.toDataURL('image/jpeg', 0.8);
            
            // Send to Livewire
            @this.call('setFotoWebcam', photoData);
            
            // Show captured photo briefly
            webcamCanvas.style.display = 'block';
            webcamVideo.style.display = 'none';
            
            // Return to video after 2 seconds
            setTimeout(() => {
                webcamCanvas.style.display = 'none';
                webcamVideo.style.display = 'block';
            }, 2000);
        }
    }
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (webcamStream) {
            webcamStream.getTracks().forEach(track => track.stop());
        }
    });
</script>
@endpush
