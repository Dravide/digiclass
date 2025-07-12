<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class TahunPelajaran extends Model
{
    protected $fillable = [
        'nama_tahun_pelajaran',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean'
    ];

    // Relasi dengan kelas
    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }

    // Relasi dengan kelas siswa
    public function kelasSiswa(): HasMany
    {
        return $this->hasMany(KelasSiswa::class);
    }

    // Scope untuk tahun pelajaran aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Method untuk mengaktifkan tahun pelajaran ini
    public function activate()
    {
        // Nonaktifkan semua tahun pelajaran lain
        static::where('is_active', true)->update(['is_active' => false]);
        
        // Aktifkan tahun pelajaran ini
        $this->update(['is_active' => true]);
    }

    // Method untuk mendapatkan tahun pelajaran aktif
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    // Accessor untuk status
    public function getStatusAttribute(): string
    {
        if ($this->is_active) {
            return 'Aktif';
        }
        
        $now = Carbon::now();
        if ($now->lt($this->tanggal_mulai)) {
            return 'Belum Dimulai';
        } elseif ($now->gt($this->tanggal_selesai)) {
            return 'Selesai';
        } else {
            return 'Tidak Aktif';
        }
    }

    // Accessor untuk badge class
    public function getBadgeClassAttribute(): string
    {
        return match($this->status) {
            'Aktif' => 'badge-success',
            'Belum Dimulai' => 'badge-info',
            'Selesai' => 'badge-secondary',
            default => 'badge-warning'
        };
    }
}
