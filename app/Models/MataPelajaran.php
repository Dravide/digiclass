<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';
    
    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'deskripsi',
        'jam_pelajaran',
        'kategori',
        'is_active'
    ];

    protected $casts = [
        'jam_pelajaran' => 'integer',
        'is_active' => 'boolean'
    ];

    // Scope untuk mata pelajaran aktif
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // Scope berdasarkan kategori
    public function scopeByKategori(Builder $query, string $kategori): Builder
    {
        return $query->where('kategori', $kategori);
    }

    // Accessor untuk nama lengkap (kode + nama)
    public function getNamaLengkapAttribute(): string
    {
        return $this->kode_mapel . ' - ' . $this->nama_mapel;
    }

    // Accessor untuk status aktif dalam bentuk text
    public function getStatusTextAttribute(): string
    {
        return $this->is_active ? 'Aktif' : 'Tidak Aktif';
    }

    // Accessor untuk kategori dalam bentuk text yang lebih readable
    public function getKategoriTextAttribute(): string
    {
        return match($this->kategori) {
            'wajib' => 'Mata Pelajaran Wajib',
            'pilihan' => 'Mata Pelajaran Pilihan',
            'muatan_lokal' => 'Muatan Lokal',
            default => ucfirst($this->kategori)
        };
    }

    // Method untuk mengecek apakah mata pelajaran wajib
    public function isWajib(): bool
    {
        return $this->kategori === 'wajib';
    }

    // Method untuk mengecek apakah mata pelajaran pilihan
    public function isPilihan(): bool
    {
        return $this->kategori === 'pilihan';
    }

    // Method untuk mengecek apakah muatan lokal
    public function isMuatanLokal(): bool
    {
        return $this->kategori === 'muatan_lokal';
    }
}
