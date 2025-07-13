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
                                        ({{ $k->tahunPelajaran->nama_tahun ?? 'N/A' }})
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
                                        ({{ $k->tahunPelajaran->nama_tahun ?? 'N/A' }})
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