<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-alert-circle text-warning me-2"></i>
                    Notifikasi Sanksi Siswa
                </h5>
            </div>
            <div class="col-auto">
                <a href="{{ route('notifikasi-sanksi-siswa') }}" class="btn btn-sm btn-outline-primary">
                    <i class="mdi mdi-eye"></i> Lihat Semua
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <!-- Statistik Ringkasan -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-warning-subtle">
                            <span class="avatar-title rounded-circle bg-warning text-white">
                                <i class="mdi mdi-clock-outline"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-0">{{ $totalSiswaPerluDitangani }}</h4>
                        <p class="text-muted mb-0">Perlu Ditangani</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-info-subtle">
                            <span class="avatar-title rounded-circle bg-info text-white">
                                <i class="mdi mdi-progress-clock"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-0">{{ $totalSiswaDalamProses }}</h4>
                        <p class="text-muted mb-0">Dalam Proses</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-success-subtle">
                            <span class="avatar-title rounded-circle bg-success text-white">
                                <i class="mdi mdi-check-circle"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-0">{{ $totalSiswaSelesai }}</h4>
                        <p class="text-muted mb-0">Selesai</p>
                    </div>
                </div>
            </div>
        </div>
        
        @if($totalSiswaPerluDitangani > 0)
            <hr>
            
            <!-- Daftar Siswa Kritis -->
            <h6 class="mb-3">
                <i class="mdi mdi-account-alert text-danger me-2"></i>
                Siswa Prioritas Tinggi
            </h6>
            
            <div class="list-group list-group-flush">
                @foreach($siswaKritis as $data)
                    <div class="list-group-item px-0 py-2">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-xs">
                                            <span class="avatar-title rounded-circle bg-danger text-white fs-6">
                                                {{ $data['total_poin'] }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0">{{ $data['siswa']['nama_siswa'] }}</h6>
                                        <p class="text-muted mb-0 small">
                                            {{ $data['kelas']['nama_kelas'] }} • {{ $data['sanksi']['jenis_sanksi'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-{{ $data['sanksi']['badge_color'] }}">
                                    {{ $data['sanksi']['penanggungjawab'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if(count($siswaKritis) >= 5)
                <div class="text-center mt-3">
                    <a href="{{ route('notifikasi-sanksi-siswa') }}" class="btn btn-sm btn-outline-warning">
                        <i class="mdi mdi-plus"></i> Lihat Lainnya
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-4">
                <div class="text-muted">
                    <i class="mdi mdi-check-circle-outline fs-1 text-success mb-2"></i>
                    <p class="mb-0">Tidak ada siswa yang perlu ditangani saat ini</p>
                </div>
            </div>
        @endif
    </div>
</div>