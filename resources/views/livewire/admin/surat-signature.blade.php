@push('styles')
<style>
    .signature-pad {
        border: 2px dashed #ddd;
        border-radius: 8px;
        cursor: crosshair;
    }
    .signature-controls {
        margin-top: 10px;
    }
    .qr-code-container {
        text-align: center;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f8f9fa;
    }
</style>
@endpush

<div>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">
                    {{ $showValidation ? 'Validasi Surat' : 'Tanda Tangan Digital' }}
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('surat-management') }}">Manajemen Surat</a></li>
                        <li class="breadcrumb-item active">{{ $showValidation ? 'Validasi' : 'Tanda Tangan' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

            <!-- Flash Messages -->
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session()->has('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Detail Surat -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Detail Surat</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Nomor Surat:</strong></div>
                                <div class="col-sm-9">{{ $surat->nomor_surat }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Jenis Surat:</strong></div>
                                <div class="col-sm-9">{{ ucfirst($surat->jenis_surat) }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Perihal:</strong></div>
                                <div class="col-sm-9">{{ $surat->perihal }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Penerima:</strong></div>
                                <div class="col-sm-9">
                                    {{ $surat->penerima }}
                                    @if($surat->jabatan_penerima)
                                        <br><small class="text-muted">{{ $surat->jabatan_penerima }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Tanggal:</strong></div>
                                <div class="col-sm-9">{{ $surat->tanggal_surat->format('d F Y') }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Status:</strong></div>
                                <div class="col-sm-9">
                                    @if($surat->status === 'draft')
                                        <span class="badge bg-secondary">Draft</span>
                                    @elseif($surat->status === 'signed')
                                        <span class="badge bg-warning">Ditandatangani</span>
                                    @elseif($surat->status === 'validated')
                                        <span class="badge bg-success">Divalidasi</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3"><strong>Isi Surat:</strong></div>
                                <div class="col-sm-9">
                                    <div class="border p-3 bg-light" style="white-space: pre-line;">{{ $surat->isi_surat }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Signature Pad atau Validasi -->
                <div class="col-lg-4">
                    @if(!$showValidation)
                        <!-- Signature Pad -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Tanda Tangan Digital</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <p class="text-muted">Buat tanda tangan Anda di area di bawah ini</p>
                                </div>
                                
                                <!-- Signature Canvas -->
                                <div class="signature-container mb-3">
                                    <canvas id="signature-pad" class="signature-pad" width="300" height="200"></canvas>
                                </div>
                                
                                <div class="signature-controls d-grid gap-2">
                                    <button type="button" class="btn btn-outline-secondary" onclick="clearSignature()">
                                        <i class="ri-eraser-line me-1"></i>Hapus Tanda Tangan
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="saveSignature()">
                                        <i class="ri-save-line me-1"></i>Simpan Tanda Tangan
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Validasi dan QR Code -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Validasi Surat</h5>
                            </div>
                            <div class="card-body text-center">
                                @if($surat->signature_data)
                                    <div class="mb-3">
                                        <h6>Tanda Tangan Digital</h6>
                                        <img src="{{ $surat->signature_data }}" alt="Signature" class="img-fluid border" style="max-height: 150px;">
                                    </div>
                                @endif
                                
                                @if($surat->qr_code_path)
                                    <div class="qr-code-container mb-3">
                                        <h6>QR Code Validasi</h6>
                                        <img src="{{ Storage::url($surat->qr_code_path) }}" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                                        <p class="text-muted mt-2">Scan QR Code untuk validasi surat</p>
                                    </div>
                                @endif
                                
                                @if($surat->signed_at)
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            Ditandatangani pada: {{ $surat->signed_at->format('d F Y H:i') }}
                                        </small>
                                    </div>
                                @endif
                                
                                @if($surat->validated_at)
                                    <div class="mb-3">
                                        <small class="text-success">
                                            Divalidasi pada: {{ $surat->validated_at->format('d F Y H:i') }}
                                        </small>
                                    </div>
                                @endif
                                
                                <div class="d-grid gap-2">
                                    @if($surat->status === 'signed')
                                        <button type="button" class="btn btn-success" wire:click="validateSurat">
                                            <i class="ri-shield-check-line me-1"></i> Validasi Surat
                                        </button>
                                    @endif
                                    
                                    <button type="button" class="btn btn-outline-primary" wire:click="downloadPdf">
                                        <i class="ri-download-line me-1"></i> Download PDF
                                    </button>
                                    
                                    <button type="button" class="btn btn-secondary" wire:click="backToManagement">
                                        <i class="ri-arrow-left-line me-1"></i> Kembali
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

    @if(!$showValidation)
        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
        <script>
            let signaturePad;
            
            document.addEventListener('DOMContentLoaded', function() {
                const canvas = document.getElementById('signature-pad');
                if (!canvas) return;
                
                signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: 'rgb(0, 0, 0)',
                    velocityFilterWeight: 0.7,
                    minWidth: 0.5,
                    maxWidth: 2.5,
                    throttle: 16,
                    minPointDistance: 3
                });
                
                // Resize canvas to fit container
                function resizeCanvas() {
                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas.width = canvas.offsetWidth * ratio;
                    canvas.height = canvas.offsetHeight * ratio;
                    canvas.getContext('2d').scale(ratio, ratio);
                    signaturePad.clear();
                }
                
                window.addEventListener('resize', resizeCanvas);
                resizeCanvas();
            });
            
            window.clearSignature = function() {
                if (signaturePad) {
                    signaturePad.clear();
                }
            }
            
            window.saveSignature = function() {
                if (!signaturePad || signaturePad.isEmpty()) {
                    alert('Silakan buat tanda tangan terlebih dahulu.');
                    return;
                }
                
                const dataURL = signaturePad.toDataURL('image/png');
                @this.set('signatureData', dataURL);
                @this.call('saveSignature');
            }
        </script>
        @endpush
    @endif
</div>