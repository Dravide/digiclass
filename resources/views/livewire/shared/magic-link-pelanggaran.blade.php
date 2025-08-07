<div>
    @if(!$isValidToken)
        <!-- Error State -->
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">
                                <i class="ri-error-warning-line me-2"></i>
                                Link Tidak Valid
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <i class="ri-error-warning-fill text-danger" style="font-size: 4rem;"></i>
                            </div>
                            <h6 class="text-danger mb-3">{{ $errorMessage }}</h6>
                            <p class="text-muted mb-4">
                                Silakan hubungi pihak sekolah untuk mendapatkan link yang valid.
                            </p>
                            <a href="{{ route('main-page') }}" class="btn btn-primary">
                                <i class="ri-home-line me-2"></i>
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Valid Token - Display Violations -->
        <div class="container mt-4">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">
                                <i class="ri-user-line me-2"></i>
                                Data Pelanggaran Siswa
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><strong>Nama Siswa:</strong></h6>
                                    <p class="mb-2">{{ $siswa->nama_siswa }}</p>
                                    
                                    <h6><strong>NIS:</strong></h6>
                                    <p class="mb-2">{{ $siswa->nis }}</p>
                                    
                                    <h6><strong>NISN:</strong></h6>
                                    <p class="mb-0">{{ $siswa->nisn }}</p>
                                </div>
                                <div class="col-md-6">
                                    @if($siswa->kelasSiswa->where('tahun_pelajaran_id', $tahunPelajaranAktif->id)->first())
                                        @php
                                            $kelasAktif = $siswa->kelasSiswa->where('tahun_pelajaran_id', $tahunPelajaranAktif->id)->first();
                                        @endphp
                                        <h6><strong>Kelas:</strong></h6>
                                        <p class="mb-2">{{ $kelasAktif->kelas->nama_kelas }}</p>
                                        
                                        <h6><strong>Wali Kelas:</strong></h6>
                                        <p class="mb-2">{{ $kelasAktif->kelas->waliKelas->nama_guru ?? 'Belum ditentukan' }}</p>
                                    @endif
                                    
                                    <h6><strong>Tahun Pelajaran:</strong></h6>
                                    <p class="mb-0">{{ $tahunPelajaranAktif->tahun_pelajaran }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total Points Summary -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card {{ $totalPoin >= 100 ? 'border-danger' : ($totalPoin >= 50 ? 'border-warning' : 'border-success') }}">
                        <div class="card-header {{ $totalPoin >= 100 ? 'bg-danger' : ($totalPoin >= 50 ? 'bg-warning' : 'bg-success') }} text-white">
                            <h5 class="mb-0">
                                <i class="ri-bar-chart-line me-2"></i>
                                Total Poin Pelanggaran
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="display-4 fw-bold {{ $totalPoin >= 100 ? 'text-danger' : ($totalPoin >= 50 ? 'text-warning' : 'text-success') }}">
                                {{ $totalPoin }}
                            </div>
                            <p class="mb-0 text-muted">Total Poin Pelanggaran</p>
                            @if($totalPoin >= 100)
                                <div class="alert alert-danger mt-3 mb-0">
                                    <i class="ri-alarm-warning-line me-2"></i>
                                    <strong>Peringatan:</strong> Total poin pelanggaran sudah mencapai batas maksimal!
                                </div>
                            @elseif($totalPoin >= 50)
                                <div class="alert alert-warning mt-3 mb-0">
                                    <i class="ri-error-warning-line me-2"></i>
                                    <strong>Perhatian:</strong> Total poin pelanggaran sudah cukup tinggi.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sanksi Notification Section -->
            @if($showSanksiInfo && $sanksiSiswa)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="ri-notification-3-line me-2"></i>
                                Notifikasi Sanksi Siswa
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="alert alert-warning mb-3">
                                        <h6 class="alert-heading">
                                            <i class="ri-alert-line me-2"></i>
                                            Sanksi yang Harus Dijalankan
                                        </h6>
                                        <hr>
                                        <p class="mb-2"><strong>Jenis Sanksi:</strong> {{ $sanksiSiswa->jenis_sanksi }}</p>
                                        <p class="mb-2"><strong>Deskripsi:</strong> {{ $sanksiSiswa->deskripsi_sanksi }}</p>
                                        <p class="mb-2"><strong>Tingkat:</strong> 
                                            <span class="badge bg-{{ $sanksiSiswa->tingkat_pelanggaran == 'ringan' ? 'success' : ($sanksiSiswa->tingkat_pelanggaran == 'sedang' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($sanksiSiswa->tingkat_pelanggaran) }}
                                            </span>
                                        </p>
                                        <p class="mb-0"><strong>Penanggung Jawab:</strong> {{ $sanksiSiswa->penanggungjawab }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h6 class="mb-3">Status Penanganan</h6>
                                        <div class="mb-3">
                                            @if($statusPenanganan == 'selesai')
                                                <span class="badge bg-success fs-6 px-3 py-2">
                                                    <i class="ri-check-line me-1"></i>
                                                    Sudah Ditangani
                                                </span>
                                            @elseif($statusPenanganan == 'dalam_proses')
                                                <span class="badge bg-warning fs-6 px-3 py-2">
                                                    <i class="ri-time-line me-1"></i>
                                                    Dalam Proses
                                                </span>
                                            @else
                                                <span class="badge bg-danger fs-6 px-3 py-2">
                                                    <i class="ri-error-warning-line me-1"></i>
                                                    Belum Ditangani
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-muted small mb-0">
                                            Silakan hubungi pihak sekolah untuk informasi lebih lanjut mengenai penanganan sanksi ini.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Violations List -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="ri-list-check me-2"></i>
                                Riwayat Pelanggaran ({{ $pelanggaranList->count() }} pelanggaran)
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($pelanggaranList->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Kategori</th>
                                                <th>Jenis Pelanggaran</th>
                                                <th>Poin</th>
                                                <th>Keterangan</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pelanggaranList as $index => $pelanggaran)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $pelanggaran->tanggal_pelanggaran_formatted }}</td>
                                                    <td>
                                                        <span class="badge bg-info">
                                                            {{ $pelanggaran->jenisPelanggaran->kategoriPelanggaran->nama_kategori }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $pelanggaran->jenisPelanggaran->nama_pelanggaran }}</td>
                                                    <td>
                                                        <span class="badge {{ $pelanggaran->poin_pelanggaran >= 25 ? 'bg-danger' : ($pelanggaran->poin_pelanggaran >= 10 ? 'bg-warning' : 'bg-success') }}">
                                                            {{ $pelanggaran->poin_pelanggaran }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $pelanggaran->keterangan ?: '-' }}</td>
                                                    <td>
                                                        <span class="badge {{ $this->getStatusBadgeClass($pelanggaran->status_penanganan) }}">
                                                            {{ $this->getStatusLabel($pelanggaran->status_penanganan) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="ri-checkbox-circle-line text-success" style="font-size: 4rem;"></i>
                                    <h5 class="mt-3 text-success">Tidak Ada Pelanggaran</h5>
                                    <p class="text-muted">Siswa ini belum memiliki catatan pelanggaran pada tahun pelajaran {{ $tahunPelajaranAktif->tahun_pelajaran }}.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-2">
                                <i class="ri-information-line me-2"></i>
                                Data ini diambil dari sistem informasi sekolah pada {{ now()->format('d/m/Y H:i') }} WIB
                            </p>
                            <a href="{{ route('main-page') }}" class="btn btn-primary">
                                <i class="ri-home-line me-2"></i>
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .display-4 {
        font-size: 3.5rem;
    }
    
    .table th {
        border-top: none;
    }
    
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }
    
    .badge {
        font-size: 0.75em;
    }
    
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2.5rem;
        }
        
        .table-responsive {
            font-size: 0.875rem;
        }
    }
</style>
@endpush