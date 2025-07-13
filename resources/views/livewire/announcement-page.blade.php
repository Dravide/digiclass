<div class="card my-auto overflow-hidden">
    <div class="row g-0">
        <div class="col-lg-6">
            <div class="bg-overlay bg-primary"></div>
            <div class="h-100 bg-auth align-items-end d-flex">
                <div class="p-5 text-white">
                    <h2 class="mb-3">Selamat Datang di DigiClass</h2>
                    <p class="mb-4">Sistem informasi manajemen kelas digital yang memudahkan siswa untuk mengetahui informasi kelas dan bergabung dengan grup WhatsApp kelas.</p>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <span class="avatar-title rounded-circle bg-white bg-opacity-20">
                                <i class="ri-graduation-cap-line font-size-20"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0">Cek Kelas Anda</h6>
                            <p class="mb-0 opacity-75">Masukkan NIS untuk melihat informasi kelas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="p-lg-5 p-4">
                <div>
                    <!-- Countdown Timer -->
                    <div class="text-center mb-4">
                        <div class="card border-0 bg-light">
                            <div class="card-body py-3">
                                <h6 class="text-primary mb-2">
                                    <i class="ri-time-line me-2"></i>{{ $countdownTitle }}
                                </h6>
                                <div id="countdown-timer" class="d-flex justify-content-center gap-3">
                                    <div class="text-center">
                                        <div class="bg-primary text-white rounded px-2 py-1">
                                            <span id="days" class="fw-bold">00</span>
                                        </div>
                                        <small class="text-muted">Hari</small>
                                    </div>
                                    <div class="text-center">
                                        <div class="bg-primary text-white rounded px-2 py-1">
                                            <span id="hours" class="fw-bold">00</span>
                                        </div>
                                        <small class="text-muted">Jam</small>
                                    </div>
                                    <div class="text-center">
                                        <div class="bg-primary text-white rounded px-2 py-1">
                                            <span id="minutes" class="fw-bold">00</span>
                                        </div>
                                        <small class="text-muted">Menit</small>
                                    </div>
                                    <div class="text-center">
                                        <div class="bg-primary text-white rounded px-2 py-1">
                                            <span id="seconds" class="fw-bold">00</span>
                                        </div>
                                        <small class="text-muted">Detik</small>
                                    </div>
                                </div>
                                <div id="countdown-message" class="mt-2" style="display: none;">
                                    <div class="alert alert-success mb-0">
                                        <i class="ri-check-circle-line me-2"></i>
                                        <strong>Waktu telah tiba!</strong> 13 Juli 2025 - 14:00 WIB
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="form-section" style="display: none;">
                        <div class="text-center mt-1">
                            <h4 class="font-size-18">Pengumuman Kelas</h4>
                            <p class="text-muted">Masukkan NIS untuk melihat informasi kelas Anda</p>
                        </div>

                        <form wire:submit.prevent="searchStudent" class="auth-input">
                        <div class="mb-3">
                            <label for="nis" class="form-label">Nomor Induk Siswa (NIS)</label>
                            <input type="text" 
                                   class="form-control @error('nis') is-invalid @enderror" 
                                   id="nis" 
                                   wire:model="nis"
                                   placeholder="Masukkan NIS Anda"
                                   maxlength="20">
                            @if($errorMessage)
                                <div class="invalid-feedback d-block">
                                    {{ $errorMessage }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-3">
                            <button class="btn btn-primary w-100" type="submit" wire:loading.attr="disabled">
                                <span wire:loading.remove>Cari Kelas</span>
                                <span wire:loading>
                                    <i class="ri-loader-2-line me-2"></i> Mencari...
                                </span>
                            </button>
                        </div>
                    </form>

                    @if($studentFound)
                        <div class="mt-4">
                            @if($canAccessClassInfo)
                                <div class="alert alert-success" role="alert">
                                    <h5 class="alert-heading"><i class="ri-check-circle-line me-2"></i>Data Ditemukan!</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="mb-2"><strong>Nama:</strong></p>
                                            <p class="mb-3">{{ $studentName }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-2"><strong>Kelas:</strong></p>
                                            <p class="mb-3">{{ $className }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="mb-2"><strong>Wali Kelas:</strong></p>
                                            <p class="mb-3">{{ $waliKelasName }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-2"><strong>Tahun Pelajaran:</strong></p>
                                            <p class="mb-3">{{ $tahunPelajaran }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($whatsappLink)
                                        <div class="mt-3">
                                            <a href="{{ $whatsappLink }}" 
                                               target="_blank" 
                                               class="btn btn-success w-100">
                                                <i class="ri-whatsapp-line me-2"></i>
                                                Bergabung ke Grup WhatsApp Kelas
                                            </a>
                                        </div>
                                    @else
                                        <div class="alert alert-warning mt-3" role="alert">
                                            <i class="ri-information-line me-2"></i>
                                            Link WhatsApp kelas belum tersedia. Silakan hubungi wali kelas Anda.
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-warning" role="alert">
                                    <h5 class="alert-heading"><i class="ri-alert-line me-2"></i>Persyaratan Belum Terpenuhi</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="mb-2"><strong>Nama:</strong></p>
                                            <p class="mb-3">{{ $studentName }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="mb-2"><strong>Tahun Pelajaran:</strong></p>
                                            <p class="mb-3">{{ $tahunPelajaran }}</p>
                                        </div>
                                    </div>
                                    <div class="alert alert-danger mt-3" role="alert">
                                        <i class="ri-error-warning-line me-2"></i>
                                        <strong>Maaf!</strong> Anda belum dapat melihat informasi kelas dan mengakses grup WhatsApp karena persyaratan perpustakaan belum terpenuhi. Silakan hubungi petugas perpustakaan untuk menyelesaikan persyaratan yang diperlukan.
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                    </div>
                </div>
            
                {{-- <div class="mt-4 text-center">
                    <p class="mb-0">Sudah punya akses admin? <a href="{{ route('login') }}" class="fw-medium text-primary">Login di sini</a></p>
                </div> --}}
            </div>
        </div>  
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set the target date from Livewire component
        const targetDate = new Date('{{ $this->getCountdownTargetDateJs() }}').getTime();
        const isExpired = {{ $this->isCountdownExpired() ? 'true' : 'false' }};
        
        function updateCountdown() {
            // Check if already expired on server side
            if (isExpired) {
                document.getElementById('countdown-timer').style.display = 'none';
                document.getElementById('countdown-message').style.display = 'block';
                document.getElementById('form-section').style.display = 'block';
                return;
            }
            
            const now = new Date().getTime();
            const distance = targetDate - now;
            
            if (distance > 0) {
                // Calculate time units
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                // Update the display
                document.getElementById('days').textContent = String(days).padStart(2, '0');
                document.getElementById('hours').textContent = String(hours).padStart(2, '0');
                document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
                document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
                
                // Show countdown timer, hide form
                document.getElementById('countdown-timer').style.display = 'flex';
                document.getElementById('countdown-message').style.display = 'none';
                document.getElementById('form-section').style.display = 'none';
            } else {
                // Time has passed - show form
                document.getElementById('countdown-timer').style.display = 'none';
                document.getElementById('countdown-message').style.display = 'block';
                document.getElementById('form-section').style.display = 'block';
            }
        }
        
        // Update countdown immediately
        updateCountdown();
        
        // Update countdown every second
        setInterval(updateCountdown, 1000);
    });
</script>
