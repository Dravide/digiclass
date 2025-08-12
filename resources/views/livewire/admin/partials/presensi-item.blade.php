<div class="list-group-item presensi-item status-{{ $presensi->status }} border-0 px-0">
    <div class="d-flex align-items-start presensi-item-wrapper">
        <div class="flex-shrink-0">
            <div class="avatar avatar-sm">
                <div class="avatar-initial bg-{{ $presensi->status == 'hadir' ? 'success' : ($presensi->status == 'terlambat' ? 'warning' : ($presensi->status == 'izin' ? 'info' : ($presensi->status == 'sakit' ? 'secondary' : ($presensi->status == 'dispensasi' ? 'primary' : 'danger')))) }} rounded-circle">
                    {{ substr($presensi->siswa->nama_siswa, 0, 1) }}
                </div>
            </div>
        </div>
        <div class="flex-grow-1 ms-3">
            <div class="d-flex justify-content-between align-items-start presensi-content">
                <div class="presensi-info">
                    <h6 class="mb-1">{{ $presensi->siswa->nama_siswa }}</h6>
                    <p class="text-muted mb-1 small">
                        {{ $presensi->jadwal->mataPelajaran->nama_mapel }} - {{ $presensi->jadwal->kelas->nama_kelas }}
                    </p>
                    <p class="text-muted mb-0 small">
                        <i class="mdi mdi-clock-outline me-1"></i>
                        @if($presensi->jam_masuk)
                            Masuk: {{ $presensi->jam_masuk }}
                        @else
                            Belum presensi
                        @endif
                     </p>
                 </div>
                 <div class="text-end presensi-controls">
                    <span class="badge bg-{{ $presensi->status == 'hadir' ? 'success' : ($presensi->status == 'terlambat' ? 'warning' : 'danger') }} mb-2">
                        {{ ucfirst($presensi->status) }}
                    </span>
                    @if($presensi->keterangan)
                        <p class="text-muted mb-2 small">{{ $presensi->keterangan }}</p>
                    @endif
                    
                    <!-- Radio Button untuk Presensi Manual -->
                    <div class="mt-2">
                        <small class="text-muted d-block mb-2">Ubah Status:</small>
                        <div class="d-flex flex-wrap gap-1">
                            <!-- Hadir -->
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check" name="presensi_{{ $presensi->id }}" id="hadir_{{ $presensi->id }}" 
                                       wire:click="updatePresensiManual({{ $presensi->id }}, 'hadir')" 
                                       {{ $presensi->status == 'hadir' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success btn-sm px-2 py-1" for="hadir_{{ $presensi->id }}" title="Hadir">
                                    <i class="mdi mdi-check"></i>
                                    <span class="d-none d-md-inline ms-1">Hadir</span>
                                </label>
                            </div>
                            
                            <!-- Terlambat -->
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check" name="presensi_{{ $presensi->id }}" id="terlambat_{{ $presensi->id }}" 
                                       wire:click="updatePresensiManual({{ $presensi->id }}, 'terlambat')" 
                                       {{ $presensi->status == 'terlambat' ? 'checked' : '' }}>
                                <label class="btn btn-outline-warning btn-sm px-2 py-1" for="terlambat_{{ $presensi->id }}" title="Terlambat">
                                    <i class="mdi mdi-clock-alert"></i>
                                    <span class="d-none d-md-inline ms-1">Terlambat</span>
                                </label>
                            </div>
                            
                            <!-- Alpha -->
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check" name="presensi_{{ $presensi->id }}" id="alpha_{{ $presensi->id }}" 
                                       wire:click="updatePresensiManual({{ $presensi->id }}, 'alpha')" 
                                       {{ $presensi->status == 'alpha' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger btn-sm px-2 py-1" for="alpha_{{ $presensi->id }}" title="Alpha">
                                    <i class="mdi mdi-close"></i>
                                    <span class="d-none d-md-inline ms-1">Alpha</span>
                                </label>
                            </div>
                            
                            <!-- Izin -->
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check" name="presensi_{{ $presensi->id }}" id="izin_{{ $presensi->id }}" 
                                       wire:click="updatePresensiManual({{ $presensi->id }}, 'izin')" 
                                       {{ $presensi->status == 'izin' ? 'checked' : '' }}>
                                <label class="btn btn-outline-info btn-sm px-2 py-1" for="izin_{{ $presensi->id }}" title="Izin">
                                    <i class="mdi mdi-information"></i>
                                    <span class="d-none d-md-inline ms-1">Izin</span>
                                </label>
                            </div>
                            
                            <!-- Sakit -->
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check" name="presensi_{{ $presensi->id }}" id="sakit_{{ $presensi->id }}" 
                                       wire:click="updatePresensiManual({{ $presensi->id }}, 'sakit')" 
                                       {{ $presensi->status == 'sakit' ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary btn-sm px-2 py-1" for="sakit_{{ $presensi->id }}" title="Sakit">
                                    <i class="mdi mdi-medical-bag"></i>
                                    <span class="d-none d-md-inline ms-1">Sakit</span>
                                </label>
                            </div>
                            
                            <!-- Dispensasi -->
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check" name="presensi_{{ $presensi->id }}" id="dispensasi_{{ $presensi->id }}" 
                                       wire:click="updatePresensiManual({{ $presensi->id }}, 'dispensasi')" 
                                       {{ $presensi->status == 'dispensasi' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary btn-sm px-2 py-1" for="dispensasi_{{ $presensi->id }}" title="Dispensasi">
                                    <i class="mdi mdi-file-document"></i>
                                    <span class="d-none d-md-inline ms-1">Dispensasi</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>