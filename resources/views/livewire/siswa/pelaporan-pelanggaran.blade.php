<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Siswa</a></li>
                        <li class="breadcrumb-item active">Pelaporan Pelanggaran</li>
                    </ol>
                </div>
                <h4 class="page-title">Pelaporan Pelanggaran</h4>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if($showAlert)
        <div class="alert alert-{{ $alertType }} alert-dismissible fade show" role="alert">
            {{ $alertMessage }}
            <button type="button" class="btn-close" wire:click="hideAlert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Laporkan Pelanggaran Siswa</h4>
                    <p class="text-muted mb-0">Bantu menjaga kedisiplinan sekolah dengan melaporkan pelanggaran yang Anda saksikan</p>
                </div>
                <div class="card-body">
                    <!-- Step 1: Scan QR Area Sekolah -->
                    <div class="mb-4">
                        <h5 class="text-primary"><i class="mdi mdi-qrcode-scan me-1"></i> Langkah 1: Scan QR Code Area Sekolah</h5>
                        <p class="text-muted">Pastikan Anda berada di area sekolah dan scan QR code yang tersedia</p>
                        
                        @if(!$isQrValid)
                            <div class="alert alert-warning">
                                <i class="mdi mdi-alert-circle me-1"></i>
                                Silakan scan QR code area sekolah terlebih dahulu untuk melanjutkan pelaporan
                            </div>
                            <button type="button" class="btn btn-primary" onclick="startQrScan('area')">
                                <i class="mdi mdi-qrcode-scan me-1"></i> Scan QR Area Sekolah
                            </button>
                        @else
                            <div class="alert alert-success">
                                <i class="mdi mdi-check-circle me-1"></i>
                                QR Code area sekolah berhasil di-scan! Anda dapat melanjutkan ke langkah berikutnya.
                            </div>
                        @endif
                    </div>

                    <!-- Step 2: Scan QR Siswa -->
                    @if($isQrValid)
                        <div class="mb-4">
                            <h5 class="text-primary"><i class="mdi mdi-account-search me-1"></i> Langkah 2: Scan QR Code Siswa</h5>
                            <p class="text-muted">Scan QR code siswa yang melakukan pelanggaran</p>
                            
                            @if(!$scannedSiswa)
                                <button type="button" class="btn btn-info" onclick="startQrScan('siswa')">
                                    <i class="mdi mdi-qrcode-scan me-1"></i> Scan QR Siswa
                                </button>
                            @else
                                <div class="alert alert-success">
                                    <i class="mdi mdi-check-circle me-1"></i>
                                    <strong>Siswa:</strong> {{ $scannedSiswa->nama_siswa }} ({{ $scannedSiswa->nis }})
                                    <br>
                                    <strong>Kelas:</strong> {{ $scannedSiswa->kelas->nama_kelas ?? 'Tidak ada kelas' }}
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="startQrScan('siswa')">
                                    <i class="mdi mdi-refresh me-1"></i> Scan Ulang
                                </button>
                            @endif
                        </div>
                    @endif

                    <!-- Step 3: Form Pelaporan -->
                    @if($isQrValid && $scannedSiswa)
                        <div class="mb-4">
                            <h5 class="text-primary"><i class="mdi mdi-file-document-edit me-1"></i> Langkah 3: Detail Pelanggaran</h5>
                            
                            <form wire:submit.prevent="submitLaporan">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="kategori" class="form-label">Kategori Pelanggaran <span class="text-danger">*</span></label>
                                        <select wire:model="selectedKategori" class="form-select @error('selectedKategori') is-invalid @enderror">
                                            <option value="">Pilih Kategori</option>
                                            @foreach($kategoriList as $kategori)
                                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedKategori')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="jenis" class="form-label">Jenis Pelanggaran <span class="text-danger">*</span></label>
                                        <select wire:model="selectedJenis" class="form-select @error('selectedJenis') is-invalid @enderror" @if(empty($jenisPelanggaranList)) disabled @endif>
                                            <option value="">Pilih Jenis Pelanggaran</option>
                                            @foreach($jenisPelanggaranList as $jenis)
                                                <option value="{{ $jenis->id }}">{{ $jenis->nama_jenis }} ({{ $jenis->poin }} poin)</option>
                                            @endforeach
                                        </select>
                                        @error('selectedJenis')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="tanggal" class="form-label">Tanggal Kejadian <span class="text-danger">*</span></label>
                                        <input type="date" wire:model="tanggal" class="form-control @error('tanggal') is-invalid @enderror">
                                        @error('tanggal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="jam" class="form-label">Jam Kejadian <span class="text-danger">*</span></label>
                                        <input type="time" wire:model="jam" class="form-control @error('jam') is-invalid @enderror">
                                        @error('jam')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="lokasi" class="form-label">Lokasi Kejadian <span class="text-danger">*</span></label>
                                        <input type="text" wire:model="lokasi" class="form-control @error('lokasi') is-invalid @enderror" placeholder="Contoh: Kantin, Kelas 7A, Lapangan">
                                        @error('lokasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi Pelanggaran <span class="text-danger">*</span></label>
                                    <textarea wire:model="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4" placeholder="Jelaskan secara detail pelanggaran yang terjadi..."></textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Minimal 10 karakter</div>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <button type="button" wire:click="resetForm" class="btn btn-secondary">
                                        <i class="mdi mdi-refresh me-1"></i> Reset Form
                                    </button>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="mdi mdi-send me-1"></i> Kirim Laporan
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Penting -->
    <div class="row">
        <div class="col-12">
            <div class="card border-info">
                <div class="card-header bg-light-info">
                    <h5 class="card-title text-info mb-0"><i class="mdi mdi-information me-1"></i> Informasi Penting</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Pastikan Anda berada di area sekolah saat melakukan pelaporan</li>
                        <li>QR code area sekolah tersedia di berbagai lokasi strategis sekolah</li>
                        <li>Laporkan hanya pelanggaran yang benar-benar Anda saksikan</li>
                        <li>Berikan deskripsi yang jelas dan objektif</li>
                        <li>Laporan akan diteruskan ke tim BK untuk ditindaklanjuti</li>
                        <li>Identitas pelapor akan dijaga kerahasiaannya</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Scanner Modal -->
<div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrScannerModalLabel">QR Code Scanner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qr-reader" style="width: 100%; height: 400px;"></div>
                <div class="mt-3">
                    <p class="text-muted">Arahkan kamera ke QR code</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
let html5QrcodeScanner;
let currentScanType = 'area';

function startQrScan(type) {
    currentScanType = type;
    const modal = new bootstrap.Modal(document.getElementById('qrScannerModal'));
    modal.show();
    
    // Initialize scanner when modal is shown
    document.getElementById('qrScannerModal').addEventListener('shown.bs.modal', function () {
        initializeScanner();
    });
    
    // Clean up when modal is hidden
    document.getElementById('qrScannerModal').addEventListener('hidden.bs.modal', function () {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear();
        }
    });
}

function initializeScanner() {
    html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader",
        { fps: 10, qrbox: {width: 250, height: 250} },
        false
    );
    
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
}

function onScanSuccess(decodedText, decodedResult) {
    // Process the scanned QR code
    if (currentScanType === 'area') {
        @this.call('processQrScan', decodedText);
    } else if (currentScanType === 'siswa') {
        @this.call('scanSiswaQr', decodedText);
    }
    
    // Close the modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('qrScannerModal'));
    modal.hide();
    
    // Clear the scanner
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear();
    }
}

function onScanFailure(error) {
    // Handle scan failure (optional)
    console.warn(`QR scan error: ${error}`);
}
</script>
@endpush