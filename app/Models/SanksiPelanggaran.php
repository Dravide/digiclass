<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanksiPelanggaran extends Model
{
    protected $table = 'sanksi_pelanggaran';
    
    protected $fillable = [
        'tingkat_pelanggaran',
        'poin_minimum',
        'poin_maksimum',
        'jenis_sanksi',
        'deskripsi_sanksi',
        'penanggungjawab',
        'is_active'
    ];

    protected $casts = [
        'poin_minimum' => 'integer',
        'poin_maksimum' => 'integer',
        'is_active' => 'boolean'
    ];

    // Scope untuk sanksi aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk sanksi berdasarkan tingkat pelanggaran
    public function scopeByTingkatPelanggaran($query, $tingkatPelanggaran)
    {
        return $query->where('tingkat_pelanggaran', $tingkatPelanggaran);
    }

    // Scope untuk sanksi berdasarkan poin tertentu
    public function scopeByPoin($query, $poin)
    {
        return $query->where('poin_minimum', '<=', $poin)
                    ->where('poin_maksimum', '>=', $poin);
    }

    // Method untuk mendapatkan sanksi yang sesuai berdasarkan tingkat pelanggaran dan total poin
    public static function getSanksiByPoin($tingkatPelanggaran, $totalPoin)
    {
        return self::active()
                  ->byTingkatPelanggaran($tingkatPelanggaran)
                  ->byPoin($totalPoin)
                  ->orderBy('poin_minimum', 'desc')
                  ->first();
    }

    // Method untuk mendapatkan semua sanksi berdasarkan tingkat pelanggaran
    public static function getSanksiByTingkatPelanggaran($tingkatPelanggaran)
    {
        return self::active()
                  ->byTingkatPelanggaran($tingkatPelanggaran)
                  ->orderBy('poin_minimum')
                  ->get();
    }

    // Method untuk mengecek apakah poin tertentu masuk dalam rentang sanksi ini
    public function isPoinInRange($poin)
    {
        return $poin >= $this->poin_minimum && $poin <= $this->poin_maksimum;
    }

    // Accessor untuk mendapatkan rentang poin dalam format string
    public function getRentangPoinAttribute()
    {
        if ($this->poin_maksimum == 999999) {
            return $this->poin_minimum . '+';
        }
        return $this->poin_minimum . ' - ' . $this->poin_maksimum;
    }

    // Accessor untuk mendapatkan tingkat pelanggaran dalam format string
    public function getTingkatPelanggaranLabelAttribute()
    {
        $labels = [
            'ringan' => 'Ringan',
            'sedang' => 'Sedang', 
            'berat' => 'Berat',
            'sangat_berat' => 'Sangat Berat'
        ];
        return $labels[$this->tingkat_pelanggaran] ?? $this->tingkat_pelanggaran;
    }

    // Method untuk mendapatkan warna badge berdasarkan tingkat sanksi
    public function getBadgeColorAttribute()
    {
        if ($this->poin_minimum <= 50) {
            return 'info';
        } elseif ($this->poin_minimum <= 100) {
            return 'warning';
        } elseif ($this->poin_minimum <= 200) {
            return 'danger';
        } else {
            return 'dark';
        }
    }

    // Method untuk mendapatkan daftar tingkat pelanggaran yang tersedia
    public static function getAvailableTingkatPelanggaran()
    {
        return [
            'ringan' => 'Ringan',
            'sedang' => 'Sedang',
            'berat' => 'Berat',
            'sangat_berat' => 'Sangat Berat'
        ];
    }

    // Method untuk mendapatkan daftar penanggungjawab yang tersedia
    public static function getAvailablePenanggungjawab()
    {
        return [
            'Wali Kelas' => 'Wali Kelas',
            'Guru BK' => 'Guru BK',
            'Wali Kelas & Guru BK' => 'Wali Kelas & Guru BK',
            'Kesiswaan' => 'Kesiswaan',
            'Kepala Sekolah' => 'Kepala Sekolah',
            'Komite Sekolah' => 'Komite Sekolah'
        ];
    }
}