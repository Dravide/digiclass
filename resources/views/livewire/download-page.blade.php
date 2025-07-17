<div>
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                <h4 class="mb-sm-0 font-size-18">Download Dokumen</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Download Daftar Hadir -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-calendar-check text-primary me-2"></i>
                        Download Daftar Hadir
                    </h4>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="downloadDaftarHadir">
                        <div class="mb-3">
                            <label for="kelas-hadir" class="form-label">Pilih Kelas</label>
                            <select wire:model="selectedKelas" id="kelas-hadir" class="form-select" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}">
                                        {{ $k->nama_kelas }} - {{ $k->guru->nama_guru ?? 'Belum ada wali kelas' }} 
                                
                                    </option>
                                @endforeach
                            </select>
                            @error('selectedKelas') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="month-hadir" class="form-label">Bulan</label>
                                    <select wire:model="selectedMonth" id="month-hadir" class="form-select" required>
                                        @foreach($months as $value => $name)
                                            <option value="{{ $value }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedMonth') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="year-hadir" class="form-label">Tahun</label>
                                    <select wire:model="selectedYear" id="year-hadir" class="form-select" required>
                                        @foreach($years as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedYear') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="mdi mdi-download me-2"></i>
                                Download Daftar Hadir
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Download Daftar Nilai -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-file-document-outline text-success me-2"></i>
                        Download Daftar Nilai
                    </h4>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="downloadDaftarNilai">
                        <div class="mb-3">
                            <label for="kelas-nilai" class="form-label">Pilih Kelas</label>
                            <select wire:model="selectedKelas" id="kelas-nilai" class="form-select" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}">
                                        {{ $k->nama_kelas }} - {{ $k->guru->nama_guru ?? 'Belum ada wali kelas' }} 
                    
                                    </option>
                                @endforeach
                            </select>
                            @error('selectedKelas') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="mapel-nilai" class="form-label">Mata Pelajaran</label>
                            <select wire:model="selectedMataPelajaran" id="mapel-nilai" class="form-select" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($mataPelajaran as $mapel)
                                    <option value="{{ $mapel->id }}">{{ $mapel->nama_lengkap }}</option>
                                @endforeach
                            </select>
                            @error('selectedMataPelajaran') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="mdi mdi-download me-2"></i>
                                Download Daftar Nilai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Students Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-account-plus text-success me-2"></i>
                        Siswa Pindahan Terbaru
                    </h4>
                </div>
                <div class="card-body">
                    @if($latestStudents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-nowrap table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>NISN</th>
                                        <th>NIS</th>
                                        <th>Tahun Pelajaran</th>
                                        <th>Tanggal Ditambahkan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($latestStudents as $student)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs me-3">
                                                        <span class="avatar-title rounded-circle bg-warning text-white font-size-16">
                                                            {{ strtoupper(substr($student->nama_siswa, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1">{{ $student->nama_siswa }}</h5>
                                                        <p class="text-muted font-size-13 mb-0">{{ $student->jk === 'L' ? 'Laki-laki' : 'Perempuan' }} - <span class="text-warning">Pindahan</span></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-soft-info">{{ $student->nisn ?: '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-soft-secondary">{{ $student->nis ?: '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-soft-primary">{{ $student->tahunPelajaran->nama_tahun_pelajaran ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $student->created_at->format('d/m/Y H:i') }}</span>
                                                <br>
                                                <small class="text-muted">{{ $student->created_at->diffForHumans() }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="mdi mdi-account-switch font-size-48 text-muted mb-2"></i>
                                <h5 class="text-muted">Belum ada siswa pindahan</h5>
                                <p class="text-muted mb-0">Belum ada data siswa pindahan yang ditambahkan</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Information Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-2">
                                <i class="mdi mdi-information-outline text-info me-2"></i>
                                Informasi Download
                            </h5>
                            <p class="text-muted mb-0">
                                Dokumen yang diunduh berformat PDF dan dapat langsung dicetak. 
                                Pastikan data kelas dan mata pelajaran sudah tersedia sebelum melakukan download.
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex justify-content-md-end justify-content-start">
                                <div class="me-3">
                                    <i class="mdi mdi-file-pdf-box text-danger" style="font-size: 2rem;"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Format</small>
                                    <strong>PDF</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>