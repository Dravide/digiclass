<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Download Dokumen</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">DigiClass</a></li>
                            <li class="breadcrumb-item active">Download</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Download Daftar Hadir -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Download Daftar Hadir</h4>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="downloadDaftarHadir">
                            <div class="mb-3">
                                <label for="selectedKelas" class="form-label">Pilih Kelas</label>
                                <select wire:model="selectedKelas" class="form-select" id="selectedKelas">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kelas }} - {{ $k->tahunPelajaran->nama_tahun_pelajaran ?? 'N/A' }}</option>
                                    @endforeach
                                </select>
                                @error('selectedKelas') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="selectedMonth" class="form-label">Pilih Bulan</label>
                                <select wire:model="selectedMonth" class="form-select" id="selectedMonth">
                                    @foreach($months as $key => $month)
                                        <option value="{{ $key }}">{{ $month }}</option>
                                    @endforeach
                                </select>
                                @error('selectedMonth') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="selectedYear" class="form-label">Pilih Tahun</label>
                                <select wire:model="selectedYear" class="form-select" id="selectedYear">
                                    @foreach($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                                @error('selectedYear') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit" class="btn btn-primary me-2">
                                <i class="mdi mdi-file-pdf me-1"></i> Download PDF
                            </button>
                            <button type="button" wire:click="downloadDaftarHadirExcel" class="btn btn-success">
                                <i class="mdi mdi-file-excel me-1"></i> Download Excel
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Download Daftar Nilai -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Download Daftar Nilai</h4>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="downloadDaftarNilai">
                            <div class="mb-3">
                                <label for="selectedKelasNilai" class="form-label">Pilih Kelas</label>
                                <select wire:model="selectedKelas" class="form-select" id="selectedKelasNilai">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kelas }} - {{ $k->tahunPelajaran->nama_tahun_pelajaran ?? 'N/A' }}</option>
                                    @endforeach
                                </select>
                                @error('selectedKelas') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="selectedMataPelajaran" class="form-label">Pilih Mata Pelajaran</label>
                                <select wire:model="selectedMataPelajaran" class="form-select" id="selectedMataPelajaran">
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach($mataPelajaran as $mp)
                                        <option value="{{ $mp->id }}">{{ $mp->nama_mapel }}</option>
                                    @endforeach
                                </select>
                                @error('selectedMataPelajaran') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit" class="btn btn-success me-2">
                                <i class="mdi mdi-file-pdf me-1"></i> Download PDF
                            </button>
                            <button type="button" wire:click="downloadDaftarNilaiExcel" class="btn btn-primary">
                                <i class="mdi mdi-file-excel me-1"></i> Download Excel
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Siswa Terbaru dengan Keterangan Pindahan -->
        @if($latestStudents->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Siswa Terbaru (Pindahan)</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>NISN</th>
                                        <th>Kelas</th>
                                        <th>Tahun Pelajaran</th>
                                        <th>Tanggal Ditambahkan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($latestStudents as $index => $siswa)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $siswa->nama_siswa }}</td>
                                        <td>{{ $siswa->nisn }}</td>
                                        <td>
                                            @if($siswa->kelasSiswa->isNotEmpty())
                                                {{ $siswa->kelasSiswa->first()->kelas->nama_kelas ?? 'N/A' }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $siswa->tahunPelajaran->nama_tahun_pelajaran ?? 'N/A' }}</td>
                                        <td>{{ $siswa->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for all select elements
        $('#selectedKelas').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Kelas --',
            allowClear: true
        });
        
        $('#selectedKelasNilai').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Kelas --',
            allowClear: true
        });
        
        $('#selectedMataPelajaran').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Mata Pelajaran --',
            allowClear: true
        });
        
        $('#selectedMonth').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih Bulan',
            allowClear: false
        });
        
        $('#selectedYear').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih Tahun',
            allowClear: false
        });
        
        // Handle Livewire updates
        Livewire.hook('message.processed', (message, component) => {
            // Reinitialize Select2 after Livewire updates
            setTimeout(() => {
                $('#selectedKelas').select2({
                    theme: 'bootstrap-5',
                    placeholder: '-- Pilih Kelas --',
                    allowClear: true
                });
                
                $('#selectedKelasNilai').select2({
                    theme: 'bootstrap-5',
                    placeholder: '-- Pilih Kelas --',
                    allowClear: true
                });
                
                $('#selectedMataPelajaran').select2({
                    theme: 'bootstrap-5',
                    placeholder: '-- Pilih Mata Pelajaran --',
                    allowClear: true
                });
            }, 100);
        });
        
        // Handle Select2 change events for Livewire
        $('#selectedKelas').on('change', function() {
            @this.set('selectedKelas', $(this).val());
        });
        
        $('#selectedKelasNilai').on('change', function() {
            @this.set('selectedKelas', $(this).val());
        });
        
        $('#selectedMataPelajaran').on('change', function() {
            @this.set('selectedMataPelajaran', $(this).val());
        });
        
        $('#selectedMonth').on('change', function() {
            @this.set('selectedMonth', $(this).val());
        });
        
        $('#selectedYear').on('change', function() {
            @this.set('selectedYear', $(this).val());
        });
    });
</script>
@endpush