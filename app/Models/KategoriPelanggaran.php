<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriPelanggaran extends Model
{
    protected $table = 'kategori_pelanggaran';
    
    protected $fillable = [
        'kode_kategori',
        'nama_kategori',
        'deskripsi'
    ];

    // Relationship dengan jenis pelanggaran
    public function jenisPelanggaran(): HasMany
    {
        return $this->hasMany(JenisPelanggaran::class);
    }

    // Scope untuk kategori aktif (yang memiliki jenis pelanggaran aktif)
    public function scopeActive($query)
    {
        return $query->whereHas('jenisPelanggaran', function ($q) {
            $q->where('is_active', true);
        });
    }

    // Method untuk mendapatkan jumlah jenis pelanggaran dalam kategori
    public function getJumlahJenisPelanggaranAttribute()
    {
        return $this->jenisPelanggaran()->where('is_active', true)->count();
    }

    // Method untuk mendapatkan total poin maksimum dalam kategori
    public function getTotalPoinMaksimumAttribute()
    {
        return $this->jenisPelanggaran()->where('is_active', true)->max('poin_pelanggaran') ?? 0;
    }

    // Method untuk mendapatkan total poin minimum dalam kategori
    public function getTotalPoinMinimumAttribute()
    {
        return $this->jenisPelanggaran()->where('is_active', true)->min('poin_pelanggaran') ?? 0;
    }
}