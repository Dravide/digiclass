<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Jadwal extends Model
{
    protected $table = 'jadwal';
    
    protected $fillable = [
        'tahun_pelajaran_id',
        'guru_id',
        'mata_pelajaran_id',
        'kelas_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'jam_ke',
        'keterangan',
        'is_active'
    ];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'jam_ke' => 'integer',
        'is_active' => 'boolean'
    ];

    // Relationship dengan tahun pelajaran
    public function tahunPelajaran(): BelongsTo
    {
        return $this->belongsTo(TahunPelajaran::class);
    }

    // Relationship dengan guru
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    // Relationship dengan mata pelajaran
    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    // Relationship dengan kelas
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    // Scope untuk jadwal aktif
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // Scope berdasarkan hari
    public function scopeByHari(Builder $query, string $hari): Builder
    {
        return $query->where('hari', $hari);
    }

    // Scope berdasarkan guru
    public function scopeByGuru(Builder $query, int $guruId): Builder
    {
        return $query->where('guru_id', $guruId);
    }

    // Scope berdasarkan kelas
    public function scopeByKelas(Builder $query, int $kelasId): Builder
    {
        return $query->where('kelas_id', $kelasId);
    }

    // Scope berdasarkan tahun pelajaran aktif
    public function scopeCurrentYear(Builder $query): Builder
    {
        return $query->whereHas('tahunPelajaran', function ($q) {
            $q->where('is_active', true);
        });
    }

    // Accessor untuk nama hari dalam bahasa Indonesia
    public function getHariIndonesiaAttribute(): string
    {
        $hari = [
            'senin' => 'Senin',
            'selasa' => 'Selasa',
            'rabu' => 'Rabu',
            'kamis' => 'Kamis',
            'jumat' => 'Jumat',
            'sabtu' => 'Sabtu'
        ];
        
        return $hari[$this->hari] ?? $this->hari;
    }

    // Accessor untuk format jam
    public function getJamFormatAttribute(): string
    {
        return $this->jam_mulai->format('H:i') . ' - ' . $this->jam_selesai->format('H:i');
    }

    // Method untuk cek bentrok jadwal
    public static function checkBentrok($data, $excludeId = null)
    {
        $query = self::where('tahun_pelajaran_id', $data['tahun_pelajaran_id'])
            ->where('hari', $data['hari'])
            ->where('is_active', true)
            ->where(function ($q) use ($data) {
                // Cek bentrok guru
                $q->where('guru_id', $data['guru_id'])
                  ->where(function ($subQ) use ($data) {
                      $subQ->whereBetween('jam_mulai', [$data['jam_mulai'], $data['jam_selesai']])
                           ->orWhereBetween('jam_selesai', [$data['jam_mulai'], $data['jam_selesai']])
                           ->orWhere(function ($innerQ) use ($data) {
                               $innerQ->where('jam_mulai', '<=', $data['jam_mulai'])
                                      ->where('jam_selesai', '>=', $data['jam_selesai']);
                           });
                  });
            })
            ->orWhere(function ($q) use ($data) {
                // Cek bentrok kelas
                $q->where('kelas_id', $data['kelas_id'])
                  ->where('jam_ke', $data['jam_ke']);
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
