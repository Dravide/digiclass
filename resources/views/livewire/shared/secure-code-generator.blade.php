<div>
    @push('styles')
    <style>
        .secure-code-card {
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        
        .secure-code-card:hover {
            transform: translateY(-2px);
        }
        
        .secure-code-display {
            font-family: 'Courier New', monospace;
            font-size: 1.1em;
            font-weight: bold;
            color: #2c3e50;
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 6px;
            border: 2px dashed #dee2e6;
        }
        
        .generate-btn {
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .generate-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
        }
        
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }
        
        .badge-role {
            font-size: 0.85em;
            padding: 6px 12px;
        }
        
        .copy-btn {
            font-size: 0.8em;
            padding: 4px 8px;
        }
    </style>
    @endpush

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="text-primary mb-2">
                <i class="mdi mdi-key-variant me-2"></i>
                Generator Secure Code
            </h2>
            <p class="text-muted">Sistem untuk menghasilkan secure code 25 digit khusus guru dan tata usaha</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Generate Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card secure-code-card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-plus-circle me-2"></i>
                        Generate Secure Code Baru
                    </h5>
                </div>
                <div class="card-body text-center">
                    @if(auth()->user()->hasRole('admin'))
                        <p class="text-muted mb-3">
                            Pilih user untuk dibuatkan secure code 25 digit yang unik
                        </p>
                        
                        <div class="mb-3">
                            <select wire:model="selectedUserId" class="form-select" style="max-width: 300px; margin: 0 auto;">
                                <option value="">-- Pilih User (Guru/Tata Usaha) --</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user['id'] }}" @if($user['has_secure_code']) disabled @endif>
                                        {{ $user['name'] }} ({{ $user['email'] }}) 
                                        @if($user['has_secure_code']) - Sudah memiliki secure code @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        @if($selectedUserId)
                            @php
                                $selectedUser = collect($availableUsers)->firstWhere('id', $selectedUserId);
                            @endphp
                            @if($selectedUser && $selectedUser['has_secure_code'])
                                <div class="alert alert-warning mb-3">
                                    <i class="mdi mdi-alert me-2"></i>
                                    User yang dipilih sudah memiliki secure code aktif.
                                </div>
                            @endif
                        @endif
                    @else
                        <p class="text-muted mb-3">
                            Klik tombol di bawah untuk menghasilkan secure code 25 digit yang unik
                        </p>
                        
                        @if($userHasSecureCode)
                            <div class="alert alert-info mb-3">
                                <i class="mdi mdi-information me-2"></i>
                                Anda sudah memiliki secure code aktif. Lihat di tabel di bawah.
                            </div>
                        @endif
                    @endif
                    
                    @php
                        $isDisabled = false;
                        $disabledReason = '';
                        
                        if (auth()->user()->hasRole('admin')) {
                            if (!$selectedUserId) {
                                $isDisabled = true;
                                $disabledReason = 'Pilih user terlebih dahulu';
                            } else {
                                $selectedUser = collect($availableUsers)->firstWhere('id', $selectedUserId);
                                if ($selectedUser && $selectedUser['has_secure_code']) {
                                    $isDisabled = true;
                                    $disabledReason = 'User sudah memiliki secure code';
                                }
                            }
                        } else {
                            if ($userHasSecureCode) {
                                $isDisabled = true;
                                $disabledReason = 'Anda sudah memiliki secure code';
                            }
                        }
                    @endphp
                    
                    <button type="button" 
                            class="btn btn-primary generate-btn"
                            wire:click="generateSecureCode"
                            wire:loading.attr="disabled"
                            @if($isDisabled) disabled @endif
                            @if($disabledReason) title="{{ $disabledReason }}" @endif>
                        <span wire:loading.remove wire:target="generateSecureCode">
                            <i class="mdi mdi-key-plus me-2"></i>
                            @if(auth()->user()->hasRole('admin'))
                                Generate Secure Code untuk User
                            @else
                                Generate Secure Code
                            @endif
                        </span>
                        <span wire:loading wire:target="generateSecureCode">
                            <i class="mdi mdi-loading mdi-spin me-2"></i>
                            Generating...
                        </span>
                    </button>
                    
                    @if($isDisabled && $disabledReason)
                        <div class="text-muted mt-2 small">
                            <i class="mdi mdi-information me-1"></i>
                            {{ $disabledReason }}
                        </div>
                    @endif
                    
                    @if(auth()->user()->hasRole('admin'))
                        <div class="mt-3">
                            <hr class="my-3">
                            <h6 class="text-muted mb-3">Bulk Generation</h6>
                            <div class="d-flex gap-2 justify-content-center flex-wrap">
                                <button type="button" 
                                        class="btn btn-success"
                                        wire:click="generateBulkGuru"
                                        wire:loading.attr="disabled"
                                        onclick="return confirm('Yakin ingin generate secure code untuk SEMUA Guru?')">
                                    <span wire:loading.remove wire:target="generateBulkGuru">
                                        <i class="mdi mdi-account-group me-2"></i>
                                        Bulk Generate Semua Guru
                                    </span>
                                    <span wire:loading wire:target="generateBulkGuru">
                                        <i class="mdi mdi-loading mdi-spin me-2"></i>
                                        Generating Guru...
                                    </span>
                                </button>
                                
                                <button type="button" 
                                        class="btn btn-info"
                                        wire:click="generateBulkTataUsaha"
                                        wire:loading.attr="disabled"
                                        onclick="return confirm('Yakin ingin generate secure code untuk SEMUA Tata Usaha?')">
                                    <span wire:loading.remove wire:target="generateBulkTataUsaha">
                                        <i class="mdi mdi-account-tie me-2"></i>
                                        Bulk Generate Semua Tata Usaha
                                    </span>
                                    <span wire:loading wire:target="generateBulkTataUsaha">
                                        <i class="mdi mdi-loading mdi-spin me-2"></i>
                                        Generating Tata Usaha...
                                    </span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card stats-card">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $secureCodes->total() }}</h3>
                    <p class="mb-0">Total Secure Codes</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stats-card">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $secureCodes->where('user_id', Auth::user()->id)->count() }}</h3>
                    <p class="mb-0">Secure Code Saya</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="mdi mdi-magnify"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       placeholder="Cari berdasarkan nama, email, atau secure code..."
                       wire:model.live.debounce.300ms="search">
            </div>
        </div>
        <div class="col-md-6 text-end d-flex justify-content-end align-items-center gap-3">
            <button type="button" 
                    class="btn btn-success btn-sm"
                    wire:click="exportExcel"
                    wire:loading.attr="disabled"
                    title="Export ke Excel">
                <span wire:loading.remove wire:target="exportExcel">
                    <i class="mdi mdi-file-excel me-1"></i>
                    Export Excel
                </span>
                <span wire:loading wire:target="exportExcel">
                    <i class="mdi mdi-loading mdi-spin me-1"></i>
                    Exporting...
                </span>
            </button>
            <span class="text-muted">
                Menampilkan {{ $secureCodes->count() }} dari {{ $secureCodes->total() }} data
            </span>
        </div>
    </div>

    <!-- Secure Codes Table -->
    <div class="row">
        <div class="col-12">
            <div class="card secure-code-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-table me-2"></i>
                        Daftar Secure Codes
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Nama Pengguna</th>
                                    <th width="15%">Jabatan</th>
                                    <th width="35%">Secure Code</th>
                                    <th width="15%">Tanggal Dibuat</th>
                                    <th width="5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($secureCodes as $index => $secureCode)
                                    <tr>
                                        <td>{{ $secureCodes->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <i class="mdi mdi-account text-white"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $secureCode->user->name }}</h6>
                                                    <small class="text-muted">{{ $secureCode->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($secureCode->user->hasRole('guru'))
                                                <span class="badge bg-success badge-role">Guru</span>
                                            @elseif($secureCode->user->hasRole('tata_usaha'))
                                                <span class="badge bg-info badge-role">Tata Usaha</span>
                                            @else
                                                <span class="badge bg-secondary badge-role">Lainnya</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <code class="secure-code-display me-2">{{ $secureCode->secure_code }}</code>
                                                <button type="button" 
                                                        class="btn btn-outline-primary btn-sm copy-btn"
                                                        onclick="copyToClipboard('{{ $secureCode->secure_code }}')"
                                                        title="Copy to clipboard">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $secureCode->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($secureCode->user_id === Auth::user()->id)
                                                <button type="button" 
                                                        class="btn btn-outline-danger btn-sm"
                                                        wire:click="hapusSecureCode({{ $secureCode->id }})"
                                                        wire:confirm="Apakah Anda yakin ingin menghapus secure code ini?"
                                                        title="Hapus">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="mdi mdi-database-search mdi-48px mb-2"></i>
                                                <p class="mb-0">Belum ada secure code yang dibuat</p>
                                                <small>Klik tombol "Generate Secure Code" untuk membuat yang pertama</small>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($secureCodes->hasPages())
                    <div class="card-footer">
                        {{ $secureCodes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                const toast = document.createElement('div');
                toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed';
                toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
                toast.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="mdi mdi-check-circle me-2"></i>
                            Secure code berhasil disalin!
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                `;
                document.body.appendChild(toast);
                
                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
                
                // Remove toast after it's hidden
                toast.addEventListener('hidden.bs.toast', function() {
                    document.body.removeChild(toast);
                });
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
                alert('Gagal menyalin secure code');
            });
        }
    </script>
    @endpush
</div>