<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('main-page') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Curhat ke BK</li>
                    </ol>
                </div>
                <h4 class="page-title">Curhat ke BK</h4>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if($showAlert)
        <div class="alert alert-{{ $alertType }} alert-dismissible fade show" role="alert">
            {{ $alertMessage }}
            <button type="button" class="btn-close" wire:click="hideAlert"></button>
        </div>
    @endif

    @if(!$isAccessGranted)
        <!-- QR Scanner Access Page -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h5 class="card-title mb-0"><i class="mdi mdi-qrcode-scan me-1"></i> Scan QR Code untuk Akses</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="mdi mdi-qrcode-scan text-primary" style="font-size: 5rem;"></i>
                        </div>
                        <h6 class="mb-3">Silakan scan QR code khusus untuk mengakses form curhat</h6>
                        <p class="text-muted mb-4">QR code ini diperlukan untuk memastikan keamanan dan privasi layanan curhat BK</p>
                        
                        <div id="qr-reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
                        
                        <div class="mt-3">
                            <button type="button" class="btn btn-primary" onclick="startQrScan('access')">
                                <i class="mdi mdi-qrcode-scan me-2"></i> Mulai Scan QR Code
                            </button>
                            <button type="button" class="btn btn-secondary ms-2" onclick="stopQrScan()" style="display: none;" id="stopScanBtn">
                                <i class="mdi mdi-stop me-2"></i> Stop Scan
                            </button>
                        </div>
                        
                        <div class="mt-4">
                            <small class="text-muted">
                                <i class="mdi mdi-information me-1"></i>
                                Hubungi guru BK jika Anda tidak memiliki QR code akses
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
    <div class="row">
        <!-- Form Curhat Siswa -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="mdi mdi-message-text me-1"></i> Form Curhat ke BK</h5>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="submitCurhat">
                            <!-- Pilihan Mode Curhat -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Mode Curhat</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" wire:model="is_anonim" value="1" id="anonim">
                                            <label class="form-check-label" for="anonim">
                                                <i class="mdi mdi-incognito me-1"></i> Curhat Anonim
                                            </label>
                                        </div>
                                        <small class="text-muted">Identitas Anda akan disembunyikan</small>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" wire:model="is_anonim" value="0" id="dengan_identitas">
                                            <label class="form-check-label" for="dengan_identitas">
                                                <i class="mdi mdi-account me-1"></i> Dengan Identitas
                                            </label>
                                        </div>
                                        <small class="text-muted">Isi nama dan kelas Anda</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Identitas (Conditional) -->
                            @if($is_anonim == '0')
                                <div class="card border-info mb-4">
                                    <div class="card-header bg-light-info">
                                        <h6 class="card-title text-info mb-0"><i class="mdi mdi-account me-1"></i> Data Siswa</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="nama_siswa" class="form-label">Nama Siswa <span class="text-danger">*</span></label>
                                                    <input type="text" wire:model="nama_siswa" class="form-control @error('nama_siswa') is-invalid @enderror" 
                                                           placeholder="Masukkan nama lengkap">
                                                    @error('nama_siswa')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="kelas_siswa" class="form-label">Kelas <span class="text-danger">*</span></label>
                                                    <input type="text" wire:model="kelas_siswa" class="form-control @error('kelas_siswa') is-invalid @enderror" 
                                                           placeholder="Contoh: 7A, 8B, 9C">
                                                    @error('kelas_siswa')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Form Curhat -->
                            <div class="card border-primary">
                                <div class="card-header bg-light-primary">
                                    <h6 class="card-title text-primary mb-0"><i class="mdi mdi-message-text me-1"></i> Isi Curhat</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="kategori" class="form-label">Kategori Curhat <span class="text-danger">*</span></label>
                                                <select wire:model="kategori" class="form-select @error('kategori') is-invalid @enderror">
                                                    <option value="">Pilih Kategori</option>
                                                    @foreach($kategoriOptions as $key => $label)
                                                        <option value="{{ $key }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                @error('kategori')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="judul" class="form-label">Judul Curhat <span class="text-danger">*</span></label>
                                                <input type="text" wire:model="judul" class="form-control @error('judul') is-invalid @enderror" 
                                                       placeholder="Masukkan judul curhat">
                                                @error('judul')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="isi_curhat" class="form-label">Isi Curhat <span class="text-danger">*</span></label>
                                        <textarea wire:model="isi_curhat" class="form-control @error('isi_curhat') is-invalid @enderror" 
                                                  rows="6" placeholder="Ceritakan masalah atau hal yang ingin Anda sampaikan kepada BK..."></textarea>
                                        @error('isi_curhat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Minimal 20 karakter, maksimal 1000 karakter</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        <i class="mdi mdi-send me-1"></i> Kirim Curhat
                                    </span>
                                    <span wire:loading>
                                        <i class="mdi mdi-loading mdi-spin me-1"></i> Mengirim...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
   </div>
    @endif
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
let html5QrcodeScanner;
let currentScanType = '';

function startQrScan(type) {
    currentScanType = type;
    
    if (!html5QrcodeScanner) {
        html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader",
            { fps: 10, qrbox: {width: 250, height: 250} },
            false
        );
        
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        
        // Show stop button and hide start button
        document.querySelector('button[onclick="startQrScan(\'access\')"]').style.display = 'none';
        document.getElementById('stopScanBtn').style.display = 'inline-block';
    }
}

function stopQrScan() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear();
        html5QrcodeScanner = null;
        
        // Show start button and hide stop button
        document.querySelector('button[onclick="startQrScan(\'access\')"]').style.display = 'inline-block';
        document.getElementById('stopScanBtn').style.display = 'none';
    }
}

function onScanSuccess(decodedText, decodedResult) {
    // Send scanned data to Livewire component
    if (currentScanType === 'access') {
        @this.call('handleQrScan', decodedText);
    }
    
    // Clean up scanner
    stopQrScan();
}

function onScanFailure(error) {
    // Handle scan failure silently
    console.warn(`QR scan error: ${error}`);
}
</script>
@endpush