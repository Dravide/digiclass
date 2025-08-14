@section('title', 'Cek Data Siswa')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="card">
                <div class="card-body">
            <div class="text-center mb-4">
                <h4 class="card-title">Cek Data Siswa</h4>
                <p class="card-title-desc">Masukkan NIS dan NISN untuk melihat data presensi dan nilai</p>
            </div>

            <form wire:submit="checkStudent">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nis" class="form-label">NIS (Nomor Induk Siswa)</label>
                            <input type="text" 
                                   class="form-control @error('nis') is-invalid @enderror" 
                                   id="nis" 
                                   wire:model="nis" 
                                   placeholder="Masukkan NIS">
                            @error('nis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nisn" class="form-label">NISN (Nomor Induk Siswa Nasional)</label>
                            <input type="text" 
                                   class="form-control @error('nisn') is-invalid @enderror" 
                                   id="nisn" 
                                   wire:model="nisn" 
                                   placeholder="Masukkan NISN">
                            @error('nisn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="btn btn-primary waves-effect waves-light" wire:loading.attr="disabled">
                        <span wire:loading.remove><i class="ri-search-line me-1"></i> Cek Data Siswa</span>
                        <span wire:loading>
                            <i class="ri-loader-2-line me-1"></i> Memuat...
                        </span>
                    </button>
                </div>
            </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errorMessage)
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <i class="ri-error-warning-line me-2"></i>
            {{ $errorMessage }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <!-- Student Information -->
    @if($showResults && $student)
        <div class="row mt-4">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="ri-user-line me-2"></i>Informasi Siswa
                        </h5>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-primary d-flex align-items-center justify-content-center">
                                    <i class="ri-user-line text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $student->nama_siswa }}</h6>
                                <p class="text-muted mb-0">
                                    @if($student->kelasSiswa->isNotEmpty())
                                        {{ $student->kelasSiswa->first()->kelas->nama_kelas ?? 'Tidak ada kelas' }}
                                    @else
                                        Tidak ada kelas
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-medium">NIS</td>
                                        <td>{{ $student->nis }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">NISN</td>
                                        <td>{{ $student->nisn }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="ri-calendar-line me-2"></i>Tahun Pelajaran
                        </h5>
                        
                        @if($tahunPelajaranList->count() > 1)
                            <div class="mb-3">
                                <label for="academicYear" class="form-label">Pilih Tahun Pelajaran</label>
                                <select class="form-select" id="academicYear" wire:model.live="selectedTahunPelajaran">
                                    @foreach($tahunPelajaranList as $tp)
                                        <option value="{{ $tp->id }}">{{ $tp->nama_tahun_pelajaran }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        
                        @if($selectedTahunPelajaran)
                            <div class="alert alert-info mb-0">
                                <i class="ri-information-line me-2"></i>
                                Data yang ditampilkan untuk tahun pelajaran yang dipilih
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Attendance Data -->
        @if(!empty($attendanceData))
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">
                            <i class="ri-calendar-check-line me-2"></i>Data Presensi
                        </h5>
                        <span class="badge bg-info">{{ count($attendanceData) }} bulan</span>
                    </div>
                    
                    <div class="row">
                        @foreach($attendanceData as $attendance)
                            <div class="col-lg-6 col-md-6">
                                <div class="card border mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="card-title">{{ $attendance['month_name'] }} {{ $attendance['year'] }}</h6>
                                                <p class="text-muted mb-2">Total: {{ $attendance['total'] }} hari</p>
                                                <div class="d-flex gap-1 flex-wrap">
                                                    @if($attendance['hadir'] > 0)
                                                        <span class="badge bg-success">H: {{ $attendance['hadir'] }}</span>
                                                    @endif
                                                    @if($attendance['alpha'] > 0)
                                                        <span class="badge bg-danger">A: {{ $attendance['alpha'] }}</span>
                                                    @endif
                                                    @if($attendance['izin'] > 0)
                                                        <span class="badge bg-warning">I: {{ $attendance['izin'] }}</span>
                                                    @endif
                                                    @if($attendance['sakit'] > 0)
                                                        <span class="badge bg-info">S: {{ $attendance['sakit'] }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <h5 class="text-primary mb-0">{{ $attendance['percentage'] }}%</h5>
                                                <small class="text-muted">Kehadiran</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="card mt-3">
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="ri-calendar-line text-muted" style="font-size: 3rem;"></i>
                        <h6 class="mt-3 text-muted">Tidak ada data presensi</h6>
                        <p class="text-muted mb-0">Data presensi belum tersedia</p>
                    </div>
                </div>
            </div>
        @endif
        <!-- Grade Data -->
        @if(!empty($gradeData))
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">
                            <i class="ri-file-list-line me-2"></i>Data Nilai
                        </h5>
                        <span class="badge bg-success">{{ count($gradeData) }} mata pelajaran</span>
                    </div>
                    
                    <div class="grade-list">
                        @foreach($gradeData as $subject => $data)
                            <div class="card border mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0">{{ $subject }}</h6>
                                        <span class="badge bg-primary">Rata-rata: {{ $data['average'] }}</span>
                                    </div>
                                    
                                    @if(!empty($data['grades']))
                                        <div class="row">
                                            @foreach($data['grades'] as $grade)
                                                <div class="col-md-6 col-lg-4 mb-2">
                                                    <div class="small bg-light p-2 rounded">
                                                        <div class="fw-semibold">{{ $grade['tugas'] }}</div>
                                                        <div class="d-flex justify-content-between">
                                                            <span class="text-muted">{{ $grade['tanggal'] }}</span>
                                                            <span class="fw-bold text-primary">{{ $grade['nilai'] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="card mt-3">
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="ri-file-list-line text-muted" style="font-size: 3rem;"></i>
                        <h6 class="mt-3 text-muted">Tidak ada data nilai</h6>
                        <p class="text-muted mb-0">Data nilai belum tersedia</p>
                    </div>
                </div>
            </div>
        @endif
        <!-- Reset Button -->
        <div class="text-center mt-4">
            <button type="button" class="btn btn-outline-secondary waves-effect waves-light" wire:click="resetForm">
                <i class="ri-refresh-line me-2"></i>Cek Data Siswa Lain
            </button>
        </div>
        
        <!-- Footer Info -->
        <div class="text-center mt-4">
            <small class="text-muted">
                <i class="ri-information-line me-1"></i>
                Data diperbarui secara real-time. Jika ada ketidaksesuaian, silakan hubungi pihak sekolah.
            </small>
        </div>
    @endif
        </div>
    </div>
</div>