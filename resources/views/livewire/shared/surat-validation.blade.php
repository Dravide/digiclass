<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Validasi Surat Digital</h4>
                </div>
                <div class="card-body">
                    @if($notFound)
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="mdi mdi-alert-circle-outline text-danger" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="text-danger">Surat Tidak Ditemukan</h4>
                            <p class="text-muted">Surat yang Anda cari tidak ditemukan atau belum ditandatangani.</p>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="text-success mb-3">
                                    <i class="mdi mdi-check-circle"></i> Surat Valid
                                </h5>
                                
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold" width="150">Nomor Surat:</td>
                                            <td>{{ $surat->nomor_surat }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Perihal:</td>
                                            <td>{{ $surat->perihal }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Tanggal:</td>
                                            <td>{{ $surat->tanggal_surat->format('d F Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Pembuat:</td>
                                            <td>{{ $surat->creator->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Status:</td>
                                            <td>
                                                @if($surat->status === 'signed')
                                                    <span class="badge bg-success">Ditandatangani</span>
                                                @elseif($surat->status === 'validated')
                                                    <span class="badge bg-primary">Tervalidasi</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($surat->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if($surat->signed_at)
                                        <tr>
                                            <td class="fw-bold">Ditandatangani:</td>
                                            <td>{{ $surat->signed_at->format('d F Y H:i') }}</td>
                                        </tr>
                                        @endif
                                        @if($surat->validated_at)
                                        <tr>
                                            <td class="fw-bold">Divalidasi:</td>
                                            <td>{{ $surat->validated_at->format('d F Y H:i') }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                                
                                @if($surat->isi_surat)
                                <div class="mt-4">
                                    <h6 class="fw-bold">Isi Surat:</h6>
                                    <div class="border p-3 rounded bg-light">
                                        {!! nl2br(e($surat->isi_surat)) !!}
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="col-md-4">
                                @if($surat->qr_code_path)
                                <div class="text-center">
                                    <h6 class="fw-bold mb-3">QR Code</h6>
                                    <img src="{{ asset('storage/' . $surat->qr_code_path) }}" 
                                         alt="QR Code" 
                                         class="img-fluid border rounded"
                                         style="max-width: 200px;">
                                </div>
                                @endif
                                
                                @if($surat->signature_data)
                                <div class="text-center mt-4">
                                    <h6 class="fw-bold mb-3">Tanda Tangan Digital</h6>
                                    <img src="{{ $surat->signature_data }}" 
                                         alt="Digital Signature" 
                                         class="img-fluid border rounded"
                                         style="max-width: 200px; max-height: 100px;">
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted small">
                                <i class="mdi mdi-information"></i>
                                Surat ini telah diverifikasi secara digital dan sah.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>