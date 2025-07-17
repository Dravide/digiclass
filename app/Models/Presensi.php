<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Presensi extends Model
{
    protected $table = 'presensi';
    
    protected $fillable = [
        'siswa_id',
        'jadwal_id',
        'tanggal',
        'jam_masuk',
        'status',
        'keterangan',
        'qr_code'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_masuk' => 'datetime:H:i'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($presensi) {
            if (empty($presensi->qr_code)) {
                $presensi->qr_code = Str::random(32);
            }
        });
    }

    // Relationship dengan siswa
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relationship dengan jadwal
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class);
    }

    // Scope untuk presensi hari ini
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('tanggal', Carbon::now('Asia/Jakarta')->toDateString());
    }

    // Scope berdasarkan status
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    // Scope berdasarkan jadwal
    public function scopeByJadwal(Builder $query, int $jadwalId): Builder
    {
        return $query->where('jadwal_id', $jadwalId);
    }

    // Accessor untuk status badge class
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'hadir' => 'bg-success',
            'terlambat' => 'bg-warning',
            'izin' => 'bg-info',
            'sakit' => 'bg-secondary',
            'dispensasi' => 'bg-primary',
            'alpha' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'hadir' => 'Hadir',
            'terlambat' => 'Terlambat',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'dispensasi' => 'Dispensasi',
            'alpha' => 'Alpha',
            default => 'Tidak Diketahui'
        };
    }

    // Method untuk generate QR code URL
    public function getQrCodeUrlAttribute(): string
    {
        return route('presensi.scan', ['code' => $this->qr_code]);
    }
}