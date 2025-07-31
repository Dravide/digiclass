<div>
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Kelas Saya</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Kelas Saya</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            @if($guru)
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h4 class="card-title mb-0">Kelas yang Saya Ajar</h4>
                                        <p class="text-muted mb-0">Guru: {{ $guru->nama_guru }}</p>
                                        @if($guru->mataPelajaran)
                                            <p class="text-muted mb-0">Mata Pelajaran: {{ $guru->mataPelajaran->nama_mata_pelajaran }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Search and Filter -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="search-box">
                                            <div class="position-relative">
                                                <input type="text" class="form-control" placeholder="Cari kelas..." wire:model.live="search">
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
                                </div>

                                <!-- Kelas Cards -->
                                @if($kelas->count() > 0)
                                    <div class="row">
                                        @foreach($kelas as $kelasItem)
                                            <div class="col-xl-4 col-lg-6 col-md-6">
                                                <div class="card border">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <div class="avatar-sm">
                                                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                                        <i class="ri-building-line font-size-16"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h5 class="card-title mb-1">{{ $kelasItem->nama_kelas }}</h5>
                                                                <p class="text-muted mb-1">{{ $kelasItem->tahunPelajaran->nama_tahun_pelajaran ?? 'Tahun tidak ditemukan' }}</p>
                                                                <p class="text-muted mb-0">
                                                                    <i class="ri-group-line me-1"></i>
                                                                    {{ $kelasItem->siswa->count() }} Siswa
                                                                </p>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mt-3">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <div class="text-center">
                                                                        <p class="text-muted mb-1">Kapasitas</p>
                                                                        <h6 class="mb-0">{{ $kelasItem->kapasitas ?? 'Tidak terbatas' }}</h6>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="text-center">
                                                                        <p class="text-muted mb-1">Status</p>
                                                                        <span class="badge bg-success">Aktif</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="mt-3">
                                                            <div class="btn-group w-100" role="group">
                                                                <a href="{{ route('class-management') }}?kelas={{ $kelasItem->id }}" class="btn btn-outline-primary btn-sm">
                                                                    <i class="ri-group-line me-1"></i> Lihat Siswa
                                                                </a>
                                                                <a href="{{ route('nilai-management') }}?kelas={{ $kelasItem->id }}" class="btn btn-outline-success btn-sm">
                                                                    <i class="ri-award-line me-1"></i> Nilai
                                                                </a>
                                                                <a href="{{ route('presensi') }}?kelas={{ $kelasItem->id }}" class="btn btn-outline-info btn-sm">
                                                                    <i class="ri-user-check-line me-1"></i> Presensi
                                                                </a>
                                                            </div>
                                                        </div>

                                                        @if($kelasItem->link_wa)
                                                            <div class="mt-2">
                                                                <a href="{{ $kelasItem->link_wa }}" target="_blank" class="btn btn-success btn-sm w-100">
                                                                    <i class="ri-whatsapp-line me-1"></i> Grup WhatsApp
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Pagination -->
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                                                {{ $kelas->links() }}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="mb-3">
                                            <i class="ri-building-line display-4 text-muted"></i>
                                        </div>
                                        <h5 class="text-muted">Tidak ada kelas yang ditemukan</h5>
                                        <p class="text-muted">Anda belum memiliki kelas yang diajar atau belum ada kelas yang sesuai dengan pencarian.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <div class="mb-3">
                                    <i class="ri-user-3-line display-4 text-muted"></i>
                                </div>
                                <h5 class="text-muted">Data Guru Tidak Ditemukan</h5>
                                <p class="text-muted">Akun Anda belum terhubung dengan data guru. Silakan hubungi administrator untuk menghubungkan akun Anda dengan data guru.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>