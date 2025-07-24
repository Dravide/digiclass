<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class JurnalMengajar extends Model
{
    protected $table = 'jurnal_mengajar';
    
    protected $fillable = [
        'jadwal_id',
        'guru_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'materi_ajar',
        'kegiatan_pembelajaran',
        'metode_pembelajaran',
        'jumlah_siswa_hadir',
        'jumlah_siswa_tidak_hadir',
        'kendala',
        'solusi',
        'catatan',
        'status',
        'submitted_at',
        'approved_at',
        'approved_by'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'jumlah_siswa_hadir' => 'integer',
        'jumlah_siswa_tidak_hadir' => 'integer'
    ];

    // Relationship dengan jadwal
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class);
    }

    // Relationship dengan guru
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    // Relationship dengan user yang approve
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scope untuk status
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    // Scope untuk guru
    public function scopeByGuru(Builder $query, int $guruId): Builder
    {
        return $query->where('guru_id', $guruId);
    }

    // Scope untuk tanggal
    public function scopeByDate(Builder $query, string $date): Builder
    {
        return $query->whereDate('tanggal', $date);
    }

    // Scope untuk bulan
    public function scopeByMonth(Builder $query, int $month, int $year): Builder
    {
        return $query->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
    }

    // Scope untuk tahun pelajaran
    public function scopeByTahunPelajaran(Builder $query, int $tahunPelajaranId): Builder
    {
        return $query->whereHas('jadwal', function ($q) use ($tahunPelajaranId) {
            $q->where('tahun_pelajaran_id', $tahunPelajaranId);
        });
    }

    // Scope untuk filter berdasarkan status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Accessor untuk status badge class
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'draft' => 'bg-secondary',
            'submitted' => 'bg-warning',
            'approved' => 'bg-success',
            default => 'bg-secondary'
        };
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            default => 'Tidak Diketahui'
        };
    }

    // Accessor untuk total siswa
    public function getTotalSiswaAttribute(): int
    {
        return $this->jumlah_siswa_hadir + $this->jumlah_siswa_tidak_hadir;
    }

    // Accessor untuk persentase kehadiran
    public function getPersentaseKehadiranAttribute(): float
    {
        $total = $this->total_siswa;
        return $total > 0 ? round(($this->jumlah_siswa_hadir / $total) * 100, 1) : 0;
    }

    // Accessor untuk format jam
    public function getJamFormatAttribute(): string
    {
        return $this->jam_mulai->format('H:i') . ' - ' . $this->jam_selesai->format('H:i');
    }

    // Method untuk submit jurnal
    public function submit(): bool
    {
        if ($this->status === 'draft') {
            $this->update([
                'status' => 'submitted',
                'submitted_at' => now()
            ]);
            return true;
        }
        return false;
    }

    // Method untuk approve jurnal
    public function approve(int $userId): bool
    {
        if ($this->status === 'submitted') {
            $this->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $userId
            ]);
            return true;
        }
        return false;
    }

    // Method untuk auto-fill data presensi
    public function autoFillPresensi(): void
    {
        $presensi = Presensi::where('jadwal_id', $this->jadwal_id)
            ->whereDate('tanggal', $this->tanggal)
            ->get();

        $hadir = $presensi->whereIn('status', ['hadir', 'terlambat'])->count();
        $tidakHadir = $presensi->whereIn('status', ['alpha', 'izin', 'sakit'])->count();

        $this->update([
            'jumlah_siswa_hadir' => $hadir,
            'jumlah_siswa_tidak_hadir' => $tidakHadir
        ]);
    }
}