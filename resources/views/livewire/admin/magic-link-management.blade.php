<div>
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Manajemen Magic Link & Kartu QR</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                        <li class="breadcrumb-item active">Magic Link & Kartu QR</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-subtle-primary text-primary rounded fs-2">
                                <i class="ri-qr-code-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="text-muted text-truncate font-size-15 mb-2">Total Siswa Aktif</p>
                            <h3 class="fs-4 flex-grow-1 mb-3">{{ $siswaList->total() }}</h3>
                            <p class="text-muted mb-0 text-truncate">Magic link tersedia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-subtle-success text-success rounded fs-2">
                                <i class="ri-link"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="text-muted text-truncate font-size-15 mb-2">Magic Link</p>
                            <h3 class="fs-4 flex-grow-1 mb-3">Aktif</h3>
                            <p class="text-muted mb-0 text-truncate">Sistem berjalan normal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-subtle-info text-info rounded fs-2">
                                <i class="ri-file-pdf-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="text-muted text-truncate font-size-15 mb-2">Kartu QR</p>
                            <h3 class="fs-4 flex-grow-1 mb-3">PDF</h3>
                            <p class="text-muted mb-0 text-truncate">Format surat keterangan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md flex-shrink-0">
                            <span class="avatar-title bg-subtle-warning text-warning rounded fs-2">
                                <i class="ri-time-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-4">
                            <p class="text-muted text-truncate font-size-15 mb-2">Masa Berlaku</p>
                            <h3 class="fs-4 flex-grow-1 mb-3">Juli 2026</h3>
                            <p class="text-muted mb-0 text-truncate">Tanggal kedaluwarsa</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Daftar Siswa - Magic Link & Kartu QR</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="search-box">
                                <div class="position-relative">
                                    <input type="text" class="form-control" placeholder="Cari nama, NISN, atau NIS..." wire:model.live="search">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="filterTahunPelajaran">
                                <option value="">Semua Tahun Pelajaran</option>
                                @foreach($tahunPelajaranOptions as $tahun)
                                    <option value="{{ $tahun->id }}">{{ $tahun->nama_tahun_pelajaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="filterKelas">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="text-muted small">
                                Total: {{ $siswaList->total() }} siswa
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-nowrap align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>NIS</th>
                                    <th>NISN</th>
                                    <th>Kelas</th>
                                    <th>Wali Kelas</th>
                                    <th>Tahun Pelajaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswaList as $index => $siswa)
                                    <tr>
                                        <td>{{ $siswaList->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-3">
                                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                        {{ strtoupper(substr($siswa->nama_siswa, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $siswa->nama_siswa }}</h6>
                                                    <small class="text-muted">{{ $siswa->jk === 'L' ? 'Laki-laki' : 'Perempuan' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-info">{{ $siswa->nis }}</span></td>
                                        <td><span class="badge bg-secondary">{{ $siswa->nisn }}</span></td>
                                        <td>
                                            @php
                                                $currentKelas = $siswa->getCurrentKelas();
                                            @endphp
                                            <span class="badge bg-primary">{{ $currentKelas->nama_kelas ?? '-' }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $currentGuru = $siswa->getCurrentGuru();
                                            @endphp
                                            {{ $currentGuru->nama_guru ?? '-' }}
                                        </td>
                                        <td><span class="badge bg-success">{{ $siswa->tahunPelajaran->nama_tahun_pelajaran ?? '-' }}</span></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-info" wire:click="generateMagicLink({{ $siswa->id }})" title="Tampilkan Magic Link Pelanggaran">
                                                    <i class="ri-link me-1"></i> Magic Link
                                                </button>
                                                <a href="{{ route('generate-magic-link-card-pdf', $siswa->id) }}" class="btn btn-outline-success" title="Download Surat Keterangan Akun dengan QR Code PDF" target="_blank">
                                                    <i class="ri-qr-code-line me-1"></i> Kartu QR
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ri-inbox-line font-size-48 d-block mb-2"></i>
                                                Tidak ada data siswa
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    {{ $siswaList->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Information Card -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-information-line me-2"></i>Informasi Magic Link & Kartu QR
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Magic Link</h6>
                            <ul class="list-unstyled">
                                <li><i class="ri-check-line text-success me-2"></i>Link akses langsung untuk pelaporan pelanggaran</li>
                                <li><i class="ri-check-line text-success me-2"></i>Berlaku hingga Juli 2026</li>
                                <li><i class="ri-check-line text-success me-2"></i>Dapat dibagikan melalui WhatsApp atau media lain</li>
                                <li><i class="ri-check-line text-success me-2"></i>Aman dengan token unik untuk setiap siswa</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">Kartu QR</h6>
                            <ul class="list-unstyled">
                                <li><i class="ri-check-line text-success me-2"></i>Format surat keterangan akun dengan QR Code</li>
                                <li><i class="ri-check-line text-success me-2"></i>Dapat dicetak dan dibagikan secara fisik</li>
                                <li><i class="ri-check-line text-success me-2"></i>Berisi informasi lengkap siswa</li>
                                <li><i class="ri-check-line text-success me-2"></i>QR Code dapat di-scan untuk akses langsung</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toast configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Magic Link Event Listeners
        Livewire.on('magic-link-generated', (event) => {
            console.log('Magic link event received:', event);
            
            // Handle both direct object and event wrapper patterns
            let data = event;
            if (event && typeof event === 'object' && !event.link && Object.keys(event).length === 1) {
                // If event is wrapped, extract the first property
                const firstKey = Object.keys(event)[0];
                data = event[firstKey];
            }
            
            console.log('Processed data:', data);
            
            if (!data || !data.link) {
                console.error('Invalid magic link data:', data);
                Toast.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Data magic link tidak valid'
                });
                return;
            }
            
            Swal.fire({
                icon: 'success',
                title: 'Magic Link Berhasil Dibuat!',
                html: `
                    <div class="text-start">
                        <p><strong>Siswa:</strong> ${data.siswa_name}</p>
                        <p><strong>Berlaku hingga:</strong> ${data.expires_at}</p>
                        <div class="mt-3">
                            <label class="form-label">Magic Link:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="${data.link}" id="magicLinkInput" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('magicLinkInput')">
                                    <i class="ri-file-copy-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Buka Link',
                cancelButtonText: 'Tutup',
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                width: '600px'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open(data.link, '_blank');
                }
            });
        });

        Livewire.on('magic-link-error', (message) => {
            console.log('Magic link error event received:', message);
            
            Toast.fire({
                icon: 'error',
                title: 'Gagal membuat Magic Link!',
                text: message || 'Terjadi kesalahan tidak diketahui'
            });
        });
    });

    // Copy to clipboard function
    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        element.select();
        element.setSelectionRange(0, 99999);
        document.execCommand('copy');
        
        // Show toast notification
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
        
        Toast.fire({
            icon: 'success',
            title: 'Link berhasil disalin!'
        });
    }
</script>
@endpush