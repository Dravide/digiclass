<div>
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">License Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                        <li class="breadcrumb-item active">License Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if($message)
        <div class="alert alert-{{ $messageType === 'success' ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
            <i class="ri-{{ $messageType === 'success' ? 'check-circle' : 'error-warning' }}-line me-2"></i>
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" wire:click="$set('message', '')"></button>
        </div>
    @endif

    <!-- Main Content -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h4 class="card-title mb-0">Status Lisensi</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-medium">Domain Saat Ini</td>
                                    <td>: {{ $currentDomain }}</td>
                                </tr>
                                @if($licenseInfo)
                                    <tr>
                                        <td class="fw-medium">License Key</td>
                                        <td>: {{ Str::limit($licenseInfo['license']->license_key, 50) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Nama Aplikasi</td>
                                        <td>: {{ $licenseInfo['license']->app_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Status</td>
                                        <td>: 
                                            @if($licenseInfo['validation']['valid'])
                                                <span class="badge bg-success"><i class="ri-check-circle-line me-1"></i>Valid</span>
                                            @else
                                                <span class="badge bg-danger"><i class="ri-close-circle-line me-1"></i>Tidak Valid</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Tanggal Expired</td>
                                        <td>: {{ $licenseInfo['license']->expires_at ? $licenseInfo['license']->expires_at->format('d M Y') : 'Tidak ada' }}</td>
                                    </tr>
                                    @if($licenseInfo['license']->notes)
                                        <tr>
                                            <td class="fw-medium">Catatan</td>
                                            <td>: {{ $licenseInfo['license']->notes }}</td>
                                        </tr>
                                    @endif
                                @else
                                    <tr>
                                        <td class="fw-medium">Status</td>
                                        <td>: <span class="badge bg-warning"><i class="ri-alert-line me-1"></i>Belum Ada Lisensi</span></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Aksi</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" wire:click="bukaModal">
                            <i class="ri-key-line me-1"></i> Input Lisensi
                        </button>
                        
                        <button type="button" class="btn btn-outline-info" wire:click="validateLicense" wire:loading.attr="disabled">
                            <i class="ri-shield-check-line me-1"></i> 
                            <span wire:loading.remove wire:target="validateLicense">Validasi Lisensi</span>
                            <span wire:loading wire:target="validateLicense">Memvalidasi...</span>
                        </button>
                        
                        <button type="button" class="btn btn-outline-secondary" 
                                wire:click="generateSampleLicense" 
                                wire:loading.attr="disabled">
                            <i class="ri-file-copy-line me-1"></i>
                            <span wire:loading.remove wire:target="generateSampleLicense">Generate Sample License</span>
                            <span wire:loading wire:target="generateSampleLicense">
                                <span class="spinner-border spinner-border-sm me-1"></span> Generating...
                            </span>
                        </button>
                        
                        @if($licenseInfo)
                            <button type="button" class="btn btn-outline-danger" wire:click="deactivateLicense" wire:loading.attr="disabled" onclick="return confirm('Yakin ingin menonaktifkan lisensi?')">
                                <i class="ri-close-circle-line me-1"></i>
                                <span wire:loading.remove wire:target="deactivateLicense">Nonaktifkan</span>
                                <span wire:loading wire:target="deactivateLicense">Menonaktifkan...</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Input Lisensi -->
    @if($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="ri-key-line me-2"></i>Input Lisensi</h5>
                        <button type="button" class="btn-close" wire:click="tutupModal"></button>
                    </div>
                    <div class="modal-body">
                        @if($message && $showModal)
                            <div class="alert alert-{{ $messageType === 'success' ? 'success' : 'danger' }}" role="alert">
                                {{ $message }}
                            </div>
                        @endif

                        <div class="alert alert-info" role="alert">
                            <i class="ri-information-line me-2"></i>
                            Domain: <strong>{{ $currentDomain }}</strong>
                        </div>

                        <div class="mb-3">
                            <label for="license_key" class="form-label">License Key <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('license_key') is-invalid @enderror" 
                                    id="license_key" 
                                    wire:model="license_key" 
                                    rows="3" 
                                    placeholder="Masukkan license key..."></textarea>
                            @error('license_key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="app_name" class="form-label">Nama Aplikasi <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('app_name') is-invalid @enderror" 
                                   id="app_name" 
                                   wire:model="app_name" 
                                   placeholder="Nama aplikasi...">
                            @error('app_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                    id="notes" 
                                    wire:model="notes" 
                                    rows="2" 
                                    placeholder="Catatan tambahan..."></textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" wire:click="tutupModal">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="simpanLisensi" wire:loading.attr="disabled" wire:target="simpanLisensi">
                            <span wire:loading.remove wire:target="simpanLisensi">Simpan Lisensi</span>
                            <span wire:loading wire:target="simpanLisensi">
                                <span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="simpanLisensi,validateLicense,generateSampleLicense,deactivateLicense"
         style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: none; align-items: center; justify-content: center;">
        <div class="text-center text-white">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="mt-2">Memproses...</div>
        </div>
    </div>
</div>
