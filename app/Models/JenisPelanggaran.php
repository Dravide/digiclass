<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JenisPelanggaran extends Model
{
    protected $table = 'jenis_pelanggaran';
    
    protected $fillable = [
        'kategori_pelanggaran_id',
        'kode_pelanggaran',
        'nama_pelanggaran',
        'deskripsi_pelanggaran',
        'poin_pelanggaran',
        'tingkat_pelanggaran',
        'is_active'
    ];

    protected $casts = [
        'poin_pelanggaran' => 'integer',
        'is_active' => 'boolean'
    ];

    // Constants untuk tingkat pelanggaran
    const TINGKAT_RINGAN = 'ringan';
    const TINGKAT_SEDANG = 'sedang';
    const TINGKAT_BERAT = 'berat';
    const TINGKAT_SANGAT_BERAT = 'sangat_berat';

    // Relationship dengan kategori pelanggaran
    public function kategoriPelanggaran(): BelongsTo
    {
        return $this->belongsTo(KategoriPelanggaran::class);
    }

    // Scope untuk jenis pelanggaran aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk jenis pelanggaran berdasarkan tingkat
    public function scopeByTingkat($query, $tingkat)
    {
        return $query->where('tingkat_pelanggaran', $tingkat);
    }

    // Scope untuk jenis pelanggaran berdasarkan kategori
    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_pelanggaran_id', $kategoriId);
    }

    // Scope untuk jenis pelanggaran berdasarkan rentang poin
    public function scopeByPoinRange($query, $minPoin, $maxPoin)
    {
        return $query->whereBetween('poin_pelanggaran', [$minPoin, $maxPoin]);
    }

    // Method untuk mendapatkan daftar tingkat pelanggaran yang tersedia
    public static function getAvailableTingkats()
    {
        return [
            self::TINGKAT_RINGAN => 'Ringan',
            self::TINGKAT_SEDANG => 'Sedang',
            self::TINGKAT_BERAT => 'Berat',
            self::TINGKAT_SANGAT_BERAT => 'Sangat Berat'
        ];
    }

    // Accessor untuk mendapatkan label tingkat pelanggaran
    public function getTingkatLabelAttribute()
    {
        $tingkats = self::getAvailableTingkats();
        return $tingkats[$this->tingkat_pelanggaran] ?? $this->tingkat_pelanggaran;
    }

    // Accessor untuk mendapatkan kode lengkap (kategori + kode)
    public function getKodeLengkapAttribute()
    {
        return $this->kategoriPelanggaran->kode_kategori . '.' . $this->kode_pelanggaran;
    }

    // Method untuk mengecek apakah pelanggaran termasuk kategori ringan
    public function isRingan()
    {
        return $this->tingkat_pelanggaran === self::TINGKAT_RINGAN;
    }

    // Method untuk mengecek apakah pelanggaran termasuk kategori sedang
    public function isSedang()
    {
        return $this->tingkat_pelanggaran === self::TINGKAT_SEDANG;
    }

    // Method untuk mengecek apakah pelanggaran termasuk kategori berat
    public function isBerat()
    {
        return $this->tingkat_pelanggaran === self::TINGKAT_BERAT;
    }

    // Method untuk mengecek apakah pelanggaran termasuk kategori sangat berat
    public function isSangatBerat()
    {
        return $this->tingkat_pelanggaran === self::TINGKAT_SANGAT_BERAT;
    }

    // Method untuk mendapatkan warna badge berdasarkan tingkat
    public function getBadgeColorAttribute()
    {
        switch ($this->tingkat_pelanggaran) {
            case self::TINGKAT_RINGAN:
                return 'success';
            case self::TINGKAT_SEDANG:
                return 'warning';
            case self::TINGKAT_BERAT:
                return 'danger';
            case self::TINGKAT_SANGAT_BERAT:
                return 'dark';
            default:
                return 'secondary';
        }
    }
}