<div>
    <!-- Presensi Hari Ini - Dipindahkan ke atas dengan ukuran diperkecil -->
    <div class="row justify-content-center mb-3">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="header-title mb-0">
                            <i class="mdi mdi-account-check"></i> Presensi Hari Ini
                        </h6>
                        <span class="badge bg-info">{{ count($presensiHariIni) }} orang</span>
                    </div>
                    <small class="text-muted">{{ now()->setTimezone('Asia/Jakarta')->format('d M Y') }}</small>
                </div>
                <div class="card-body py-2">
                    @if(count($presensiHariIni) > 0)
                        @php
                            $latestPresensi = $presensiHariIni[0]; // Ambil presensi terakhir (sudah diurutkan desc)
                        @endphp
                        <div class="d-flex align-items-center p-2 border rounded-2 shadow-sm bg-light">
                            <!-- Foto Presensi -->
                            <div class="me-3">
                                @if(isset($latestPresensi['foto_path']) && $latestPresensi['foto_path'])
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $latestPresensi['foto_path']) }}" 
                                              alt="Foto Presensi" 
                                              class="foto-presensi rounded-2 border border-2 border-primary" 
                                              style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                              data-bs-toggle="modal" 
                                              data-bs-target="#fotoModalLatest">
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success" style="font-size: 0.6rem;">
                                            <i class="mdi mdi-camera" style="font-size: 0.7rem;"></i>
                                        </span>
                                    </div>
                                @else
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-{{ $latestPresensi['jenis_presensi'] === 'masuk' ? 'success' : ($latestPresensi['jenis_presensi'] === 'lembur' ? 'warning' : 'danger') }} text-white rounded-circle">
                                            {{ strtoupper(substr($latestPresensi['user']['name'], 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-grow-1">
                                <div class="fw-bold mb-1">{{ $latestPresensi['user']['name'] }}</div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-{{ $latestPresensi['jenis_presensi'] === 'masuk' ? 'success' : ($latestPresensi['jenis_presensi'] === 'lembur' ? 'warning' : 'danger') }} small">
                                        {{ ucfirst($latestPresensi['jenis_presensi']) }}
                                    </span>
                                    @if(isset($latestPresensi['is_terlambat']) && $latestPresensi['is_terlambat'])
                                        <span class="badge bg-warning text-dark small">Terlambat</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <div class="fw-bold text-primary">
                                    {{ \Carbon\Carbon::parse($latestPresensi['created_at'])->setTimezone('Asia/Jakarta')->format('H:i') }}
                                </div>
                                <small class="text-muted">Terakhir</small>
                            </div>
                        </div>
                        

                        
                        <!-- Modal untuk foto presensi terakhir -->
                        @if(isset($latestPresensi['foto_path']) && $latestPresensi['foto_path'])
                            <div class="modal fade" id="fotoModalLatest" tabindex="-1" aria-labelledby="fotoModalLatestLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="fotoModalLatestLabel">
                                                <i class="mdi mdi-camera me-2"></i>Foto Presensi - {{ $latestPresensi['user']['name'] }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ asset('storage/' . $latestPresensi['foto_path']) }}" 
                                                 alt="Foto Presensi" 
                                                 class="img-fluid rounded-3 border">
                                            <div class="mt-3">
                                                <p class="mb-1"><strong>Nama:</strong> {{ $latestPresensi['user']['name'] }}</p>
                                                <p class="mb-1"><strong>Jenis Presensi:</strong> {{ ucfirst($latestPresensi['jenis_presensi']) }}</p>
                                                <p class="mb-0"><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($latestPresensi['created_at'])->setTimezone('Asia/Jakarta')->format('d M Y H:i:s') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-2">
                            <i class="mdi mdi-calendar-times text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">Belum ada presensi hari ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content - Presensi QR Scanner -->
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="text-center">
                        <h4 class="header-title mb-1">Presensi QR Scanner</h4>
                        <small class="text-muted">
                            <span class="badge bg-{{ $jenis_presensi === 'masuk' ? 'success' : ($jenis_presensi === 'lembur' ? 'warning' : 'danger') }} badge-sm">
                                {{ ucfirst($jenis_presensi) }}
                            </span>
                        </small>
                    </div>
                </div>
                    <div class="card-body">
                        <!-- Toast Container -->
                        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
                            @if($showResult)
                                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" id="presensi-toast">
                                    <div class="toast-header bg-{{ $resultType === 'success' ? 'success' : ($resultType === 'error' ? 'danger' : 'warning') }} text-white">
                                        <i class="mdi mdi-{{ $resultType === 'success' ? 'check-circle' : ($resultType === 'error' ? 'alert-circle' : 'information') }} me-2"></i>
                                        <strong class="me-auto">
                                            {{ $resultType === 'success' ? 'Berhasil' : ($resultType === 'error' ? 'Error' : 'Peringatan') }}
                                        </strong>
                                        <small>{{ now()->format('H:i') }}</small>
                                        <button type="button" class="btn-close btn-close-white" wire:click="hideResult" aria-label="Close"></button>
                                    </div>
                                    <div class="toast-body">
                                        {{ $resultMessage }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if(!$this->isBolehPresensi())
                            <!-- Pesan Hari Libur -->
                            <div class="alert alert-warning text-center mb-4">
                                <i class="mdi mdi-calendar-remove h4 mb-2"></i>
                                <h5 class="alert-heading">Presensi Tidak Tersedia</h5>
                                <p class="mb-0">{{ $pesanHariLibur }}</p>
                            </div>
                            
                            <!-- Form yang dinonaktifkan -->
                            <div class="position-relative">
                                <div class="overlay-disabled position-absolute w-100 h-100" style="background: rgba(255,255,255,0.8); z-index: 10; border-radius: 0.375rem;"></div>
                        @endif

                        <form wire:submit="prosesQrCode" @if(!$this->isBolehPresensi()) style="pointer-events: none; opacity: 0.6;" @endif>
                            <!-- Hidden input for QR scanner - Optimized for Fully Kiosk Browser -->
                            <input type="text" 
                                   class="form-control" 
                                   id="qr_code" 
                                   wire:model="qr_code" 
                                   style="position: absolute; left: -9999px; opacity: 0; height: 1px; width: 1px; z-index: 9999;"
                                   autofocus
                                   autocomplete="off"
                                   spellcheck="false"
                                   autocapitalize="off"
                                   autocorrect="off"
                                   inputmode="text"
                                   tabindex="1">

                            <div class="row">
                                <!-- Kolom Kiri: Foto Presensi -->
                                <div class="col-lg-5 col-md-6">
                                    <div class="text-center mb-3">
                                        <h6 class="text-muted mb-2">
                                            <i class="mdi mdi-camera"></i> Foto Presensi
                                        </h6>
                                        <div class="position-relative">
                                            <video id="webcam-video" autoplay muted class="rounded border" style="width: 100%; max-width: 400px; height: 300px; object-fit: cover;"></video>
                                            <canvas id="webcam-canvas" class="rounded border" style="width: 100%; max-width: 400px; height: 300px; display: none;"></canvas>
                                            <div id="camera-status" class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-success">Kamera Aktif</span>
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mt-2">Foto otomatis diambil saat scan QR</small>
                                    </div>
                                </div>

                                <!-- Kolom Tengah: QR Scanner Area -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="text-center">
                                        <h6 class="text-muted mb-3">
                                            <i class="mdi mdi-qrcode-scan"></i> Scan QR Code
                                        </h6>
                                        
                                        <div class="qr-scanner-area p-3 border border-2 border-dashed rounded" style="min-height: 200px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
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
                                                    
                                                    <!-- Fully Kiosk Browser Status -->
                                                    <div id="fully-kiosk-status" class="mt-3 text-center" style="display: none;">
                                                        <div class="badge bg-info mb-2">
                                                            <i class="mdi mdi-tablet"></i> Fully Kiosk Browser Terdeteksi
                                                        </div>
                                                        <div class="small text-muted">
                                                            Scanner terintegrasi dengan hardware
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- QR Scanner Instructions -->
                                                    <div id="scanner-instructions" class="mt-3 text-center">
                                                        <div class="small text-muted">
                                                            <i class="mdi mdi-information-outline"></i>
                                                            Gunakan QR scanner hardware atau ketik manual
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- USB Scanner Debug Info -->
                                                    <div id="usb-debug-info" class="mt-2 text-center" style="display: none;">
                                                        <div class="small text-info">
                                                            <i class="mdi mdi-bug"></i> Debug: <span id="debug-text">Mendeteksi USB scanner...</span>
                                                        </div>
                                                        <div class="mt-2">
                                                            <button type="button" class="btn btn-sm btn-outline-info" onclick="testScannerInput()">
                                                                <i class="mdi mdi-test-tube"></i> Test Scanner
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleDebugMode()">
                                                                <i class="mdi mdi-eye"></i> <span id="debug-toggle-text">Hide Debug</span>
                                                            </button>
                                                        </div>
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

                                <!-- Kolom Kanan: Jadwal Presensi -->
                                <div class="col-lg-4 col-md-12">
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-3">
                                            <i class="mdi mdi-clock-outline"></i> Jadwal Presensi
                                        </h6>
                                        
                                        @php
                                            $jamPresensi = \App\Models\JamPresensi::getJamPresensiHari();
                                            $currentTime = now()->setTimezone('Asia/Jakarta')->format('H:i');
                                        @endphp

                                        @if($jamPresensi)
                                            <!-- Card Jadwal Masuk -->
                                            <div class="card border-success mb-3" style="border-width: 2px !important;">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-3">
                                                            <i class="mdi mdi-login text-white"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1 text-success fw-bold">Presensi Masuk</h6>
                                                            <div class="d-flex align-items-center">
                                                                <i class="mdi mdi-clock-outline text-muted me-1"></i>
                                                                <span class="text-muted small">
                                                                    {{ \Carbon\Carbon::parse($jamPresensi->jam_masuk_mulai)->format('H:i') }} - 
                                                                    {{ \Carbon\Carbon::parse($jamPresensi->jam_masuk_selesai)->format('H:i') }} WIB
                                                                </span>
                                                            </div>
                                                            <div class="mt-1">
                                                                @if($jamPresensi->bisaPresensiMasuk())
                                                                    <span class="badge bg-success-subtle text-success">
                                                                        <i class="mdi mdi-check-circle me-1"></i>Aktif Sekarang
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-secondary-subtle text-secondary">
                                                                        <i class="mdi mdi-clock-outline me-1"></i>Tidak Aktif
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Card Jadwal Pulang -->
                                            <div class="card border-danger mb-3" style="border-width: 2px !important;">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-danger rounded-circle d-flex align-items-center justify-content-center me-3">
                                                            <i class="mdi mdi-logout text-white"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1 text-danger fw-bold">Presensi Pulang</h6>
                                                            <div class="d-flex align-items-center">
                                                                <i class="mdi mdi-clock-outline text-muted me-1"></i>
                                                                <span class="text-muted small">
                                                                    {{ \Carbon\Carbon::parse($jamPresensi->jam_pulang_mulai)->format('H:i') }} - 
                                                                    {{ \Carbon\Carbon::parse($jamPresensi->jam_pulang_selesai)->format('H:i') }} WIB
                                                                </span>
                                                            </div>
                                                            <div class="mt-1">
                                                                @if($jamPresensi->bisaPresensiPulang())
                                                                    <span class="badge bg-danger-subtle text-danger">
                                                                        <i class="mdi mdi-check-circle me-1"></i>Aktif Sekarang
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-secondary-subtle text-secondary">
                                                                        <i class="mdi mdi-clock-outline me-1"></i>Tidak Aktif
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Card Jadwal Lembur -->
                                            @if($jamPresensi->jam_lembur_mulai && $jamPresensi->jam_lembur_selesai)
                                                <div class="card border-warning" style="border-width: 2px !important;">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-warning rounded-circle d-flex align-items-center justify-content-center me-3">
                                                                <i class="mdi mdi-clock-plus text-white"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1 text-warning fw-bold">Presensi Lembur</h6>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="mdi mdi-clock-outline text-muted me-1"></i>
                                                                    <span class="text-muted small">
                                                                        {{ \Carbon\Carbon::parse($jamPresensi->jam_lembur_mulai)->format('H:i') }} - 
                                                                        {{ \Carbon\Carbon::parse($jamPresensi->jam_lembur_selesai)->format('H:i') }} WIB
                                                                    </span>
                                                                </div>
                                                                <div class="mt-1">
                                                                    @if($jamPresensi->bisaPresensiLembur())
                                                                        <span class="badge bg-warning-subtle text-warning">
                                                                            <i class="mdi mdi-check-circle me-1"></i>Aktif Sekarang
                                                                        </span>
                                                                    @else
                                                                        <span class="badge bg-secondary-subtle text-secondary">
                                                                            <i class="mdi mdi-clock-outline me-1"></i>Tidak Aktif
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <!-- Fallback jika tidak ada pengaturan jam presensi -->
                                            <div class="card border-warning" style="border-width: 2px !important;">
                                                <div class="card-body p-3 text-center">
                                                    <div class="avatar-sm bg-warning rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2">
                                                        <i class="mdi mdi-alert-outline text-white"></i>
                                                    </div>
                                                    <h6 class="mb-1 text-warning fw-bold">Pengaturan Belum Dikonfigurasi</h6>
                                                    <p class="text-muted small mb-2">Jam presensi untuk hari {{ \App\Models\JamPresensi::getNamaHariIni() }} belum diatur.</p>
                                                    <a href="{{ route('pengaturan-jam-presensi') }}" class="btn btn-warning btn-sm">
                                                        <i class="mdi mdi-settings me-1"></i>Atur Sekarang
                                                    </a>
                                                </div>
                                            </div>
                                        @endif


                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        @if(!$this->isBolehPresensi())
                            </div> <!-- Penutup overlay-disabled -->
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
    
    /* Foto presensi styles */
    .foto-presensi {
        transition: transform 0.2s ease;
    }
    
    .foto-presensi:hover {
        transform: scale(1.05);
    }
    
    .avatar-md {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-md .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Modal image styles */
    .modal-body img {
        max-height: 70vh;
        object-fit: contain;
    }
    
    /* Badge styles */
    .badge.small {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    
    /* Camera video styling for stable orientation */
    #webcam-video {
        transform: none !important;
        object-fit: cover !important;
        object-position: center !important;
        width: 100% !important;
        max-width: 500px !important;
        height: 350px !important;
        background: #000;
        border-radius: 0.375rem;
    }
    
    #webcam-canvas {
        transform: none !important;
        object-fit: cover !important;
        width: 100% !important;
        max-width: 500px !important;
        height: 350px !important;
        border-radius: 0.375rem;
    }
    
    /* QR Scanner area styling */
    .qr-scanner-area {
        transition: all 0.3s ease;
    }
    
    .qr-scanner-area:hover {
        border-color: #007bff !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .foto-presensi {
            width: 50px !important;
            height: 50px !important;
        }
        
        .avatar-md {
            width: 50px;
            height: 50px;
        }
        
        #webcam-video, #webcam-canvas {
            height: 250px !important;
            max-width: 100% !important;
            transform: none !important;
        }
        
        .qr-scanner-area {
            min-height: 120px !important;
        }
        
        .col-md-8, .col-md-4 {
            margin-bottom: 1rem;
        }
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
    
    /* Toast notification styling */
    .toast {
        min-width: 300px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border: none;
        transition: all 0.3s ease-in-out;
    }
    
    .toast.show {
        animation: slideInRight 0.3s ease-out;
    }
    
    .toast.hiding {
        animation: slideOutRight 0.3s ease-in;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .toast-header {
        border-bottom: none;
        font-weight: 600;
    }
    
    .toast-body {
        font-size: 0.9rem;
        color: #495057;
    }
</style>
@endpush

@push('scripts')
<script>
    // Toast notification auto-hide functionality
    document.addEventListener('livewire:init', () => {
        
        // Function to hide toast with animation
        function hideToastWithAnimation() {
            const toastElement = document.getElementById('presensi-toast');
            if (toastElement) {
                toastElement.classList.add('hiding');
                setTimeout(() => {
                    @this.call('hideResult');
                }, 300); // Wait for slide-out animation
            }
        }
        
        // Auto-hide success messages after 4 seconds
        Livewire.on('auto-hide-result', () => {
            setTimeout(() => {
                hideToastWithAnimation();
            }, 4000);
        });
        
        // Auto-hide error messages after 6 seconds
        Livewire.on('auto-hide-error', () => {
            setTimeout(() => {
                hideToastWithAnimation();
            }, 6000);
        });
        
        // Auto-hide any existing toast on page load
        setTimeout(() => {
            const toastElement = document.getElementById('presensi-toast');
            if (toastElement && toastElement.classList.contains('show')) {
                hideToastWithAnimation();
            }
        }, 5000);
        
        // Initialize Bootstrap toast behavior
        document.addEventListener('DOMContentLoaded', function() {
            const toastElements = document.querySelectorAll('.toast');
            toastElements.forEach(function(toastElement) {
                // Enable manual close button functionality
                const closeButton = toastElement.querySelector('.btn-close');
                if (closeButton) {
                    closeButton.addEventListener('click', function() {
                        hideToastWithAnimation();
                    });
                }
            });
        });
    });
    
    // QR Scanner functionality
    let webcamStream = null;
    let webcamVideo = null;
    let webcamCanvas = null;
    let webcamContext = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize camera ready flag
        window.cameraReady = false;
        
        const qrInput = document.getElementById('qr_code');
        webcamVideo = document.getElementById('webcam-video');
        webcamCanvas = document.getElementById('webcam-canvas');
        let webcamStream = null;
        
        if (webcamCanvas) {
            webcamContext = webcamCanvas.getContext('2d');
            webcamCanvas.width = 300;
            webcamCanvas.height = 200;
        }
        
        // Detect Fully Kiosk Browser
        const isFullyKiosk = navigator.userAgent.includes('Fully') || 
                           window.fully !== undefined || 
                           window.Android !== undefined;
        
        console.log('Fully Kiosk Browser detected:', isFullyKiosk);
        
        if (isFullyKiosk) {
            // Fully Kiosk Browser specific optimizations
            setupFullyKioskOptimizations();
        }
        
        // Auto-start camera
        initializeCamera().catch(error => {
            console.error('Failed to initialize camera:', error);
        });
        
        // Keep QR input focused for scanner and add event listeners
        if (qrInput) {
            // Initial focus with delay
            setTimeout(() => {
                qrInput.focus();
                if (isFullyKiosk) {
                    qrInput.click();
                }
            }, 1000);
            
            // Capture photo when QR code is detected
            qrInput.addEventListener('input', function() {
                if (this.value.trim().length > 0) {
                    console.log('QR Code detected, capturing photo...');
                    capturePhoto();
                }
            });
            
            // Enhanced focus management
            maintainQrInputFocus();
        }
    });
    
    // Fully Kiosk Browser specific optimizations
    function setupFullyKioskOptimizations() {
        console.log('Setting up Fully Kiosk Browser optimizations...');
        
        // Disable screen saver if available
        if (window.fully && window.fully.setScreenSaverEnabled) {
            window.fully.setScreenSaverEnabled(false);
        }
        
        // Keep screen on if available
        if (window.fully && window.fully.setScreenOn) {
            window.fully.setScreenOn(true);
        }
        
        // Enable hardware keyboard if available
        if (window.fully && window.fully.setKeyboardEnabled) {
            window.fully.setKeyboardEnabled(true);
        }
        
        // Set up barcode scanner integration if available
        if (window.fully && window.fully.startBarcodeScanner) {
            setupFullyBarcodeScanner();
        }
        
        // Enhanced input handling for Fully Kiosk
        document.addEventListener('keydown', function(e) {
            const qrInput = document.getElementById('qr_code');
            if (qrInput && document.activeElement !== qrInput) {
                // Redirect all keyboard input to QR input
                qrInput.focus();
                // Simulate the key press on the QR input
                setTimeout(() => {
                    qrInput.value += e.key;
                    qrInput.dispatchEvent(new Event('input', { bubbles: true }));
                }, 10);
            }
        });
    }
    
    // Fully Kiosk Browser barcode scanner integration
    function setupFullyBarcodeScanner() {
        console.log('Setting up Fully Kiosk barcode scanner...');
        
        // Update UI based on Fully Kiosk Browser detection
        const fullyStatus = document.getElementById('fully-kiosk-status');
        const scannerInstructions = document.getElementById('scanner-instructions');
        
        if (window.fully) {
            console.log('Fully Kiosk Browser detected');
            
            // Show Fully Kiosk status indicator
            if (fullyStatus) {
                fullyStatus.style.display = 'block';
            }
            if (scannerInstructions) {
                scannerInstructions.innerHTML = '<div class="small text-success"><i class="mdi mdi-check-circle"></i> Hardware scanner siap digunakan</div>';
            }
            
            // Disable screen saver and keep screen on
            if (window.fully.setScreensaverEnabled) {
                window.fully.setScreensaverEnabled(false);
            }
            if (window.fully.turnScreenOn) {
                window.fully.turnScreenOn(true);
            }
            
            // Enable USB scanner support
            if (window.fully.setUsbScannerEnabled) {
                window.fully.setUsbScannerEnabled(true);
                console.log('USB scanner enabled');
            }
            
            // Configure barcode scanner settings
            if (window.fully.setBarcodeReaderEnabled) {
                window.fully.setBarcodeReaderEnabled(true);
                console.log('Barcode reader enabled');
            }
            
            // Set scanner to continuous mode
            if (window.fully.setScannerContinuousMode) {
                window.fully.setScannerContinuousMode(true);
                console.log('Scanner continuous mode enabled');
            }
            
            // Configure barcode scanner if available
            if (window.fully.bind) {
                // Bind multiple scanner events
                window.fully.bind('onBarcodeScanned', 'onBarcodeScanned(result)');
                window.fully.bind('onUsbScannerData', 'onUsbScannerData(data)');
                window.fully.bind('onScannerConnected', 'onScannerConnected()');
                window.fully.bind('onScannerDisconnected', 'onScannerDisconnected()');
                console.log('Scanner event bindings configured');
            }
        } else {
            // Update instructions for non-Fully Kiosk browsers
            if (scannerInstructions) {
                scannerInstructions.innerHTML = '<div class="small text-warning"><i class="mdi mdi-alert-circle"></i> Gunakan QR scanner eksternal atau ketik kode manual</div>';
            }
        }
        
        // Global function to handle barcode scan results
        window.onBarcodeScanned = function(result) {
            console.log('Barcode scanned via Fully Kiosk:', result);
            handleScanResult(result);
        };
        
        // Global function to handle USB scanner data
        window.onUsbScannerData = function(data) {
            console.log('USB Scanner data received:', data);
            handleScanResult(data);
        };
        
        // Global function to handle scanner connection
        window.onScannerConnected = function() {
            console.log('Scanner connected');
            const fullyStatus = document.getElementById('fully-kiosk-status');
            const scannerInstructions = document.getElementById('scanner-instructions');
            
            if (fullyStatus) {
                fullyStatus.innerHTML = '<div class="badge bg-success mb-2"><i class="mdi mdi-usb"></i> USB Scanner Terhubung</div><div class="small text-muted">Scanner siap digunakan</div>';
            }
            if (scannerInstructions) {
                scannerInstructions.innerHTML = '<div class="small text-success"><i class="mdi mdi-check-circle"></i> USB QR Scanner aktif dan siap</div>';
            }
        };
        
        // Global function to handle scanner disconnection
        window.onScannerDisconnected = function() {
            console.log('Scanner disconnected');
            const fullyStatus = document.getElementById('fully-kiosk-status');
            const scannerInstructions = document.getElementById('scanner-instructions');
            
            if (fullyStatus) {
                fullyStatus.innerHTML = '<div class="badge bg-warning mb-2"><i class="mdi mdi-usb-off"></i> USB Scanner Terputus</div><div class="small text-muted">Periksa koneksi USB</div>';
            }
            if (scannerInstructions) {
                scannerInstructions.innerHTML = '<div class="small text-warning"><i class="mdi mdi-alert-circle"></i> USB Scanner tidak terhubung</div>';
            }
        };
        
        // Unified function to handle scan results from any source
        function handleScanResult(result) {
            if (!result || result.trim() === '') {
                console.log('Empty scan result, ignoring');
                return;
            }
            
            const cleanResult = result.trim();
            console.log('Processing scan result:', cleanResult);
            
            // Show scanning feedback
            const fullyStatus = document.getElementById('fully-kiosk-status');
            if (fullyStatus && window.fully) {
                fullyStatus.innerHTML = '<div class="badge bg-success mb-2"><i class="mdi mdi-check-circle"></i> QR Code Terbaca!</div><div class="small text-muted">Memproses data...</div>';
            }
            
            const qrInput = document.getElementById('qr_code');
            if (qrInput) {
                qrInput.value = cleanResult;
                qrInput.focus();
                qrInput.dispatchEvent(new Event('input', { bubbles: true }));
                // Trigger Livewire update
                @this.set('qr_code', cleanResult);
            }
            
            // Reset status after processing
            setTimeout(() => {
                if (fullyStatus && window.fully) {
                    fullyStatus.innerHTML = '<div class="badge bg-info mb-2"><i class="mdi mdi-tablet"></i> Fully Kiosk Browser Terdeteksi</div><div class="small text-muted">Scanner terintegrasi dengan hardware</div>';
                }
            }, 3000);
        }
        
        // Auto-start barcode scanner and USB scanner detection
        setTimeout(() => {
            if (window.fully) {
                updateDebugInfo('Fully Kiosk terdeteksi, mengkonfigurasi scanner...');
                
                // Start built-in barcode scanner
                if (window.fully.startBarcodeScanner) {
                    window.fully.startBarcodeScanner();
                    console.log('Fully Kiosk barcode scanner started');
                    updateDebugInfo('Built-in scanner diaktifkan');
                }
                
                // Check for USB scanner
                if (window.fully.getUsbDevices) {
                    const usbDevices = window.fully.getUsbDevices();
                    console.log('USB devices detected:', usbDevices);
                    updateDebugInfo(`USB devices: ${usbDevices ? usbDevices.length : 0} ditemukan`);
                    
                    // Look for scanner devices
                    const scannerFound = usbDevices && usbDevices.some(device => 
                        device.toLowerCase().includes('scanner') || 
                        device.toLowerCase().includes('barcode') ||
                        device.toLowerCase().includes('qr')
                    );
                    
                    if (scannerFound) {
                        console.log('USB Scanner device detected');
                        updateDebugInfo('USB Scanner terdeteksi!');
                        window.onScannerConnected();
                    } else {
                        updateDebugInfo('USB Scanner tidak terdeteksi dalam daftar perangkat');
                    }
                } else {
                    updateDebugInfo('API getUsbDevices tidak tersedia');
                }
                
                // Enable keyboard wedge mode for USB scanners
                if (window.fully.setKeyboardWedgeEnabled) {
                    window.fully.setKeyboardWedgeEnabled(true);
                    console.log('Keyboard wedge mode enabled for USB scanners');
                    updateDebugInfo('Keyboard wedge mode diaktifkan');
                } else {
                    updateDebugInfo('Keyboard wedge mode tidak tersedia');
                }
                
                // Set scanner timeout
                if (window.fully.setScannerTimeout) {
                    window.fully.setScannerTimeout(30000); // 30 seconds
                    console.log('Scanner timeout set to 30 seconds');
                    updateDebugInfo('Scanner timeout diatur ke 30 detik');
                }
                
                // Show debug info
                const debugDiv = document.getElementById('usb-debug-info');
                if (debugDiv) {
                    debugDiv.style.display = 'block';
                }
            } else {
                updateDebugInfo('Fully Kiosk Browser tidak terdeteksi');
            }
        }, 2000);
        
        // Additional USB scanner detection via keyboard events
        setupUsbScannerKeyboardDetection();
    }
    
    // Function to detect USB scanner via keyboard input patterns
    function setupUsbScannerKeyboardDetection() {
        let scanBuffer = '';
        let scanTimeout;
        
        document.addEventListener('keydown', function(e) {
            // Clear timeout on any key press
            clearTimeout(scanTimeout);
            
            // Check if this might be from a USB scanner (rapid input)
            if (e.key.length === 1) {
                scanBuffer += e.key;
                
                // Set timeout to process buffer
                scanTimeout = setTimeout(() => {
                    if (scanBuffer.length >= 8) {
                        console.log('Potential USB scanner input detected:', scanBuffer);
                        
                        // Check if QR input is focused or if this looks like scanner input
                        const qrInput = document.getElementById('qr_code');
                        if (qrInput && (document.activeElement === qrInput || scanBuffer.match(/^[A-Za-z0-9]+$/))) {
                            handleScanResult(scanBuffer);
                        }
                    }
                    scanBuffer = '';
                }, 100); // 100ms delay to capture full scan
            } else if (e.key === 'Enter' && scanBuffer.length >= 8) {
                // Enter key often signals end of USB scanner input
                console.log('USB scanner input completed with Enter:', scanBuffer);
                handleScanResult(scanBuffer);
                scanBuffer = '';
                clearTimeout(scanTimeout);
            }
        });
    }
    
    async function initializeCamera() {
        try {
            webcamStream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    width: { ideal: 300 },
                    height: { ideal: 200 },
                    facingMode: 'user' // Use front camera for better orientation
                } 
            });
            
            if (webcamVideo) {
                webcamVideo.srcObject = webcamStream;
                
                // Wait for video to be ready
                return new Promise((resolve, reject) => {
                    webcamVideo.onloadedmetadata = () => {
                        webcamVideo.play().then(() => {
                            console.log('Video ready:', {
                                width: webcamVideo.videoWidth,
                                height: webcamVideo.videoHeight,
                                readyState: webcamVideo.readyState
                            });
                            
                            // Reset video orientation and display
                            webcamVideo.style.display = 'block';
                            webcamVideo.style.transform = 'none';
                            webcamVideo.style.objectFit = 'cover';
                            
                            // Update status
                            const cameraStatus = document.getElementById('camera-status');
                            if (cameraStatus) {
                                cameraStatus.innerHTML = '<span class="badge bg-success">Kamera Aktif</span>';
                            }
                            
                            // Add delay to ensure camera is fully ready
                            setTimeout(() => {
                                window.cameraReady = true;
                                console.log('Camera is now ready for photo capture');
                                resolve();
                            }, 2000); // 2 second delay
                            
                        }).catch(reject);
                    };
                    
                    webcamVideo.onerror = (error) => {
                        console.error('Video error:', error);
                        reject(error);
                    };
                    
                    // Timeout after 15 seconds
                    setTimeout(() => {
                        reject(new Error('Camera initialization timeout'));
                    }, 15000);
                });
            }
            
        } catch (error) {
            console.error('Error accessing camera:', error);
            const cameraStatus = document.getElementById('camera-status');
            if (cameraStatus) {
                cameraStatus.innerHTML = '<span class="badge bg-danger">Kamera Error</span>';
            }
            throw error;
        }
    }
    
    function maintainQrInputFocus() {
        const qrInput = document.getElementById('qr_code');
        if (!qrInput) return;
        
        // Initial focus with delay for Fully Kiosk Browser
        setTimeout(() => {
            qrInput.focus();
            qrInput.click(); // Additional trigger for Fully Kiosk
        }, 500);
        
        // Aggressive focus maintenance for Fully Kiosk Browser
        setInterval(() => {
            if (document.activeElement !== qrInput) {
                qrInput.focus();
                qrInput.click();
            }
        }, 500); // More frequent checks
        
        // Re-focus on any interaction
        document.addEventListener('click', () => {
            setTimeout(() => {
                qrInput.focus();
                qrInput.click();
            }, 10);
        });
        
        document.addEventListener('touchstart', () => {
            setTimeout(() => {
                qrInput.focus();
                qrInput.click();
            }, 10);
        });
        
        // Fully Kiosk Browser specific events
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                setTimeout(() => {
                    qrInput.focus();
                    qrInput.click();
                }, 100);
            }
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
        if (!webcamVideo || !webcamContext) {
            console.error('Webcam video atau context tidak tersedia');
            return;
        }
        
        // Pastikan kamera sudah siap
        if (!window.cameraReady) {
            console.log('Kamera belum siap, menunggu...');
            setTimeout(() => {
                capturePhoto();
            }, 1000);
            return;
        }
        
        // Pastikan video sudah ready dan memiliki dimensi
        if (webcamVideo.videoWidth === 0 || webcamVideo.videoHeight === 0) {
            console.error('Video belum ready atau tidak memiliki dimensi');
            return;
        }
        
        // Pastikan video sedang playing
        if (webcamVideo.paused || webcamVideo.ended) {
            console.error('Video tidak sedang playing');
            return;
        }
        
        try {
            // Set canvas dimensions to match video
            const videoWidth = webcamVideo.videoWidth;
            const videoHeight = webcamVideo.videoHeight;
            
            // Calculate aspect ratio to maintain proportions
            const aspectRatio = videoWidth / videoHeight;
            const canvasWidth = 300;
            const canvasHeight = Math.round(canvasWidth / aspectRatio);
            
            webcamCanvas.width = canvasWidth;
            webcamCanvas.height = canvasHeight;
            
            // Clear canvas first
            webcamContext.clearRect(0, 0, canvasWidth, canvasHeight);
            
            // Draw video frame to canvas
            webcamContext.drawImage(webcamVideo, 0, 0, canvasWidth, canvasHeight);
            
            // Convert to base64 with higher quality
            const photoData = webcamCanvas.toDataURL('image/jpeg', 0.9);
            
            // Validate that we actually captured something (not just black)
            const imageData = webcamContext.getImageData(0, 0, canvasWidth, canvasHeight);
            const pixels = imageData.data;
            let totalBrightness = 0;
            
            // Check average brightness to detect black images
            for (let i = 0; i < pixels.length; i += 4) {
                totalBrightness += (pixels[i] + pixels[i + 1] + pixels[i + 2]) / 3;
            }
            
            const avgBrightness = totalBrightness / (pixels.length / 4);
            
            if (avgBrightness < 10) {
                console.error('Foto terlalu gelap, kemungkinan kamera belum siap');
                // Coba lagi setelah delay
                setTimeout(() => {
                    capturePhoto();
                }, 500);
                return;
            }
            
            console.log('Foto berhasil diambil dengan brightness:', avgBrightness);
            
            // Send to Livewire
            @this.call('setFotoWebcam', photoData);
            
            // Show captured photo briefly
            webcamCanvas.style.display = 'block';
            webcamVideo.style.display = 'none';
            
            // Return to video after 2 seconds with proper reset
            setTimeout(() => {
                webcamCanvas.style.display = 'none';
                webcamVideo.style.display = 'block';
                // Reset video orientation
                webcamVideo.style.transform = 'none';
                webcamVideo.style.objectFit = 'cover';
            }, 2000);
            
        } catch (error) {
            console.error('Error capturing photo:', error);
        }
    }
    
    // Function to reset camera orientation
    function resetCameraOrientation() {
        if (webcamVideo) {
            webcamVideo.style.transform = 'none';
            webcamVideo.style.objectFit = 'cover';
            webcamVideo.style.objectPosition = 'center';
            webcamVideo.style.width = '100%';
            webcamVideo.style.maxWidth = '500px';
            webcamVideo.style.height = '350px';
        }
        
        if (webcamCanvas) {
            webcamCanvas.style.transform = 'none';
            webcamCanvas.style.objectFit = 'cover';
            webcamCanvas.style.width = '100%';
            webcamCanvas.style.maxWidth = '500px';
            webcamCanvas.style.height = '350px';
        }
    }
    
    // Reset camera orientation every 5 seconds to prevent drift
    setInterval(() => {
        if (window.cameraReady && webcamVideo && webcamVideo.style.display !== 'none') {
            resetCameraOrientation();
        }
    }, 5000);
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (webcamStream) {
            webcamStream.getTracks().forEach(track => track.stop());
        }
    });
    
    // Reset orientation when page becomes visible again
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && window.cameraReady) {
            setTimeout(() => {
                resetCameraOrientation();
            }, 1000);
        }
    });
    
    // Auto-refresh jenis presensi setiap 60 detik
    // Ini memastikan sistem otomatis beralih antara masuk/pulang
    // berdasarkan jadwal yang dikonfigurasi tanpa intervensi manual
    setInterval(function() {
        console.log('Auto-refreshing jenis presensi...');
        @this.call('autoDetectJenisPresensi');
    }, 60000); // 60 detik
    
    // Debug info update function
     function updateDebugInfo(message) {
         const debugText = document.getElementById('debug-text');
         if (debugText) {
             debugText.textContent = message;
             console.log('Debug:', message);
         }
     }
     
     // Test scanner input function
     function testScannerInput() {
         const qrInput = document.getElementById('qr_code');
         if (qrInput) {
             updateDebugInfo('Testing scanner input...');
             
             // Simulate scanner input
             const testQR = 'TEST123456789';
             qrInput.value = testQR;
             qrInput.focus();
             
             // Trigger input event
             const inputEvent = new Event('input', { bubbles: true });
             qrInput.dispatchEvent(inputEvent);
             
             updateDebugInfo(`Test QR code: ${testQR}`);
             
             // Clear after 3 seconds
             setTimeout(() => {
                 qrInput.value = '';
                 updateDebugInfo('Test selesai, field dikosongkan');
             }, 3000);
         }
     }
     
     // Toggle debug mode
     function toggleDebugMode() {
         const debugDiv = document.getElementById('usb-debug-info');
         const toggleText = document.getElementById('debug-toggle-text');
         
         if (debugDiv && toggleText) {
             if (debugDiv.style.display === 'none') {
                 debugDiv.style.display = 'block';
                 toggleText.textContent = 'Hide Debug';
                 updateDebugInfo('Debug mode diaktifkan');
             } else {
                 debugDiv.style.display = 'none';
                 toggleText.textContent = 'Show Debug';
             }
         }
     }
    
    // Monitor all keyboard events for debugging
    function setupKeyboardMonitoring() {
        document.addEventListener('keydown', function(e) {
            // Log all key events for debugging
            console.log('Key event:', {
                key: e.key,
                code: e.code,
                keyCode: e.keyCode,
                target: e.target.tagName,
                timestamp: new Date().toISOString()
            });
            
            // Update debug info for scanner-like input
            if (e.target.id === 'qr_code' && e.key.length === 1) {
                updateDebugInfo(`Input terdeteksi: ${e.key}`);
            }
        });
        
        // Monitor input changes
        document.addEventListener('input', function(e) {
            if (e.target.id === 'qr_code') {
                const value = e.target.value;
                updateDebugInfo(`QR Code input: ${value.length} karakter`);
                
                if (value.length >= 8) {
                    updateDebugInfo('QR Code lengkap terdeteksi, memproses...');
                }
            }
        });
    }
    
    // Juga refresh saat halaman pertama kali dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // Setup Fully Kiosk Browser integration
        setupFullyBarcodeScanner();
        
        // Setup keyboard monitoring for debugging
        setupKeyboardMonitoring();
        
        // Show debug info for troubleshooting
        const debugDiv = document.getElementById('usb-debug-info');
        if (debugDiv) {
            debugDiv.style.display = 'block';
        }
        updateDebugInfo('Sistem siap, menunggu input scanner...');
        
        setTimeout(function() {
            @this.call('autoDetectJenisPresensi');
        }, 2000); // Delay 2 detik untuk memastikan komponen sudah siap
    });
</script>
@endpush
