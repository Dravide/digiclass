<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Siswa</a></li>
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
            <button type="button" class="btn-close" wire:click="hideAlert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Form Curhat -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title"><i class="mdi mdi-heart me-1"></i> Sampaikan Curhat Anda</h4>
                    <p class="text-muted mb-0">Tim BK siap mendengarkan dan membantu menyelesaikan masalah Anda</p>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="submitCurhat">
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori Masalah <span class="text-danger">*</span></label>
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
                        
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul Curhat <span class="text-danger">*</span></label>
                            <input type="text" wire:model="judul" class="form-control @error('judul') is-invalid @enderror" placeholder="Berikan judul singkat untuk curhat Anda">
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">5-100 karakter</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="isi_curhat" class="form-label">Ceritakan Masalah Anda <span class="text-danger">*</span></label>
                            <textarea wire:model="isi_curhat" class="form-control @error('isi_curhat') is-invalid @enderror" rows="8" placeholder="Ceritakan masalah Anda dengan detail. Semakin jelas cerita Anda, semakin baik bantuan yang bisa kami berikan..."></textarea>
                            @error('isi_curhat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">20-1000 karakter</div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:model="is_anonim" id="is_anonim">
                                <label class="form-check-label" for="is_anonim">
                                    <strong>Kirim sebagai Anonim</strong>
                                </label>
                            </div>
                            <div class="form-text text-muted">
                                <i class="mdi mdi-information me-1"></i>
                                Jika dicentang, identitas Anda akan disembunyikan dari tim BK. Namun, tim BK mungkin kesulitan memberikan bantuan yang lebih personal.
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" wire:click="resetForm" class="btn btn-secondary">
                                <i class="mdi mdi-refresh me-1"></i> Reset Form
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-send me-1"></i> Kirim Curhat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <!-- Informasi BK -->
            <div class="card border-info">
                <div class="card-header bg-light-info">
                    <h5 class="card-title text-info mb-0"><i class="mdi mdi-information me-1"></i> Tentang Layanan BK</h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">Tim Bimbingan Konseling (BK) siap membantu Anda mengatasi berbagai masalah:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="mdi mdi-check-circle text-success me-2"></i> Masalah akademik</li>
                        <li class="mb-2"><i class="mdi mdi-check-circle text-success me-2"></i> Hubungan sosial</li>
                        <li class="mb-2"><i class="mdi mdi-check-circle text-success me-2"></i> Masalah keluarga</li>
                        <li class="mb-2"><i class="mdi mdi-check-circle text-success me-2"></i> Konsultasi karir</li>
                        <li class="mb-2"><i class="mdi mdi-check-circle text-success me-2"></i> Kesehatan mental</li>
                    </ul>
                    <div class="alert alert-warning mt-3">
                        <small><i class="mdi mdi-shield-check me-1"></i> <strong>Privasi Terjamin:</strong> Semua curhat akan dijaga kerahasiaannya oleh tim BK profesional.</small>
                    </div>
                </div>
            </div>
            
            <!-- Riwayat Curhat -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="mdi mdi-history me-1"></i> Riwayat Curhat</h5>
                    <button type="button" wire:click="toggleRiwayat" class="btn btn-sm btn-outline-primary">
                        @if($showRiwayat)
                            <i class="mdi mdi-eye-off me-1"></i> Sembunyikan
                        @else
                            <i class="mdi mdi-eye me-1"></i> Tampilkan
                        @endif
                    </button>
                </div>
                @if($showRiwayat)
                    <div class="card-body">
                        @if(count($riwayatCurhat) > 0)
                            @foreach($riwayatCurhat as $curhat)
                                <div class="border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-1">{{ $curhat->judul }}</h6>
                                        <span class="badge bg-{{ $curhat->status_badge_color }}">{{ $curhat->status_label }}</span>
                                    </div>
                                    <p class="text-muted small mb-2">
                                        <i class="mdi mdi-tag me-1"></i> {{ $curhat->kategori_label }}
                                        <br>
                                        <i class="mdi mdi-calendar me-1"></i> {{ $curhat->tanggal_curhat->format('d M Y H:i') }}
                                    </p>
                                    <p class="mb-2">{{ Str::limit($curhat->isi_curhat, 100) }}</p>
                                    @if($curhat->respon_bk)
                                        <div class="alert alert-success p-2 mb-0">
                                            <small><strong>Respon BK:</strong> {{ Str::limit($curhat->respon_bk, 80) }}</small>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="mdi mdi-comment-text-outline text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">Belum ada riwayat curhat</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
            
            <!-- Kontak Darurat -->
            <div class="card border-danger">
                <div class="card-header bg-light-danger">
                    <h5 class="card-title text-danger mb-0"><i class="mdi mdi-phone me-1"></i> Kontak Darurat</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Jika Anda membutuhkan bantuan segera:</strong></p>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="mdi mdi-phone text-success me-2"></i>
                            <strong>BK Sekolah:</strong> (021) 123-4567
                        </li>
                        <li class="mb-2">
                            <i class="mdi mdi-whatsapp text-success me-2"></i>
                            <strong>WhatsApp BK:</strong> 0812-3456-7890
                        </li>
                        <li class="mb-2">
                            <i class="mdi mdi-phone text-warning me-2"></i>
                            <strong>Hotline Nasional:</strong> 119
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>