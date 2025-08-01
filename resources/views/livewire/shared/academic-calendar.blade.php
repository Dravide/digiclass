<div><div class="card border-0 shadow-sm">
    <div class="card-header bg-gradient-success border-0">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="ri-calendar-line me-2"></i>Kalender Akademik
            </h5>
            <div class="d-flex align-items-center">
                <button wire:click="previousMonth" class="btn btn-sm btn-outline-light me-2">
                    <i class="ri-arrow-left-s-line"></i>
                </button>
                <span class="fw-bold">{{ $monthName }} {{ $currentYear }}</span>
                <button wire:click="nextMonth" class="btn btn-sm btn-outline-light ms-2">
                    <i class="ri-arrow-right-s-line"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <!-- Calendar Header -->
        <div class="row g-0 border-bottom">
            @foreach(['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
            <div class="col text-center py-2 fw-bold text-muted small border-end">
                {{ $day }}
            </div>
            @endforeach
        </div>
        
        <!-- Calendar Days -->
        <div class="calendar-grid">
            @foreach(array_chunk($calendarDays, 7) as $week)
            <div class="row g-0">
                @foreach($week as $day)
                <div class="col border-end border-bottom position-relative calendar-day {{ !$day['isCurrentMonth'] ? 'text-muted bg-light' : '' }} {{ $day['isToday'] ? 'bg-primary bg-opacity-10' : '' }}" 
                     style="min-height: 80px; cursor: pointer;" 
                     wire:click="showDayDetails('{{ $day['date']->format('Y-m-d') }}')" 
                     @if(count($day['events']) > 0) title="Klik untuk melihat detail jadwal" @endif>
                    <div class="p-2">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="fw-bold {{ $day['isToday'] ? 'text-primary' : '' }}">{{ $day['day'] }}</span>
                            @if($day['isToday'])
                            <span class="badge bg-primary rounded-pill small">Hari ini</span>
                            @endif
                        </div>
                        
                        <!-- Teaching Schedules -->
                        @if(count($day['events']) > 0)
                        <div class="mt-1">
                            @foreach(array_slice($day['events'], 0, 3) as $event)
                            <div class="badge bg-{{ $event['color'] }} bg-opacity-75 text-dark small d-block mb-1 text-start p-1" 
                                 style="font-size: 0.6rem; line-height: 1.1;" 
                                 title="{{ $event['title'] }} - {{ $event['subtitle'] ?? '' }}">
                                <div class="fw-bold">{{ Str::limit($event['title'], 20) }}</div>
                                @if(isset($event['subtitle']))
                                <div class="text-muted" style="font-size: 0.55rem;">{{ Str::limit($event['subtitle'], 25) }}</div>
                                @endif
                            </div>
                            @endforeach
                            
                            @if(count($day['events']) > 3)
                            <div class="text-muted small">+{{ count($day['events']) - 3 }} jadwal lainnya</div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Legend -->
    <div class="card-footer bg-light border-0">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <small class="text-muted fw-bold">Keterangan:</small>
            </div>
            <div class="col-auto">
                <span class="badge bg-primary bg-opacity-75 text-dark"><i class="ri-user-line me-1"></i>Jadwal Mengajar</span>
            </div>
            <div class="col-auto">
                <small class="text-muted">Menampilkan kelas yang memiliki jadwal pada hari tersebut</small>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Jadwal -->
@if($showModal)
<div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="ri-calendar-line me-2"></i>
                    Jadwal Kelas - {{ \Carbon\Carbon::parse($selectedDate)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
            </div>
            <div class="modal-body">
                @if(count($selectedEvents) > 0)
                    <div class="row g-3">
                        @foreach($selectedEvents as $index => $event)
                        <div class="col-12">
                            <div class="card border-primary border-opacity-25">
                                <div class="card-header bg-primary bg-opacity-10">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-20 rounded-circle p-2 me-3">
                                            <i class="ri-group-line text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-primary">{{ $event['title'] }}</h6>
                                            <small class="text-muted">{{ $event['subtitle'] }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if(isset($event['schedules']) && count($event['schedules']) > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="15%">Jam Ke</th>
                                                        <th width="20%">Waktu</th>
                                                        <th width="35%">Mata Pelajaran</th>
                                                        <th width="30%">Guru</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($event['schedules'] as $schedule)
                                                    <tr>
                                                        <td>
                                                            <span class="badge bg-primary">{{ $schedule['jam_ke'] }}</span>
                                                        </td>
                                                        <td>
                                                            <i class="ri-time-line me-1 text-muted"></i>
                                                            {{ $schedule['jam'] }}
                                                        </td>
                                                        <td>
                                                            <i class="ri-book-line me-1 text-muted"></i>
                                                            {{ $schedule['mata_pelajaran'] }}
                                                        </td>
                                                        <td>
                                                            <i class="ri-user-line me-1 text-muted"></i>
                                                            {{ $schedule['guru'] }}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <i class="ri-calendar-line text-muted" style="font-size: 3rem;"></i>
                        </div>
                        <h6 class="text-muted">Tidak ada jadwal kelas</h6>
                        <p class="text-muted small">Tidak ada kelas yang memiliki jadwal pada hari ini.</p>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="closeModal">
                    <i class="ri-close-line me-1"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<style>
.calendar-day:hover {
    background-color: rgba(0, 123, 255, 0.05) !important;
    cursor: pointer;
}

.calendar-grid .row {
    min-height: 80px;
}

.badge {
    font-size: 0.65rem;
    line-height: 1.2;
}

.modal.show {
    display: block !important;
}
</style>
</div>