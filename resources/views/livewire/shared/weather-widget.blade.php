<div class="card border-0 shadow-sm h-100">
    <div class="card-header bg-gradient-{{ $weatherColor }} text-white border-0">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="ri-temp-hot-line me-2"></i>Cuaca Hari Ini
            </h5>
            <button wire:click="refreshWeather" class="btn btn-sm btn-outline-light" title="Refresh Cuaca">
                <i class="ri-refresh-line"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Current Weather -->
        <div class="text-center mb-4">
            <div class="d-flex align-items-center justify-content-center mb-3">
                <i class="{{ $weather['icon'] }} display-4 text-{{ $weatherColor }} me-3"></i>
                <div>
                    <h2 class="mb-0 fw-bold">{{ $weather['temperature'] }}°C</h2>
                    <p class="mb-0 text-muted">{{ $weather['condition'] }}</p>
                </div>
            </div>
            
            <div class="row g-3 text-center">
                <div class="col-4">
                    <div class="p-2 rounded bg-light">
                        <i class="ri-drop-line text-info mb-1 d-block"></i>
                        <small class="text-muted d-block">Kelembaban</small>
                        <strong>{{ $weather['humidity'] }}%</strong>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2 rounded bg-light">
                        <i class="ri-windy-line text-primary mb-1 d-block"></i>
                        <small class="text-muted d-block">Angin</small>
                        <strong>{{ $weather['wind_speed'] }} km/h</strong>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2 rounded bg-light">
                        <i class="ri-map-pin-line text-success mb-1 d-block"></i>
                        <small class="text-muted d-block">Lokasi</small>
                        <strong class="small">{{ Str::limit($location, 8) }}</strong>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Weather Forecast -->
        <div class="border-top pt-3">
            <h6 class="mb-3 fw-bold text-muted">Prakiraan 3 Hari</h6>
            @foreach($weather['forecast'] as $forecast)
            <div class="d-flex align-items-center justify-content-between mb-2 p-2 rounded hover-bg-light">
                <div class="d-flex align-items-center">
                    <i class="{{ $forecast['icon'] }} text-primary me-2"></i>
                    <span class="fw-medium">{{ $forecast['day'] }}</span>
                </div>
                <div class="text-end">
                    <span class="fw-bold">{{ $forecast['temp_high'] }}°</span>
                    <span class="text-muted">/ {{ $forecast['temp_low'] }}°</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Footer with time -->
    <div class="card-footer bg-light border-0 text-center">
        <small class="text-muted">
            <i class="ri-time-line me-1"></i>
            Diperbarui: {{ $currentTime }} - {{ $currentDate }}
        </small>
    </div>
    <style>
.hover-bg-light:hover {
    background-color: rgba(0, 0, 0, 0.05) !important;
    cursor: pointer;
}

.display-4 {
    font-size: 3rem;
}

@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }
}
</style>
</div>

