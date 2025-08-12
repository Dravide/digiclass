<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LibraryBook extends Model
{
    protected $fillable = [
        'kode_buku',
        'judul_buku',
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'kategori',
        'deskripsi',
        'jumlah_total',
        'jumlah_tersedia',
        'lokasi_rak',
        'kondisi',
        'is_active'
    ];

    protected $casts = [
        'tahun_terbit' => 'integer',
        'jumlah_total' => 'integer',
        'jumlah_tersedia' => 'integer',
        'is_active' => 'boolean'
    ];

    public function borrowings(): HasMany
    {
        return $this->hasMany(LibraryBorrowing::class);
    }

    public function activeBorrowings(): HasMany
    {
        return $this->hasMany(LibraryBorrowing::class)->where('status', 'dipinjam');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('jumlah_tersedia', '>', 0);
    }
}
