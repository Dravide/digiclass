<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanksiPelanggaran extends Model
{
    protected $table = 'sanksi_pelanggaran';
    
    protected $fillable = [
        'tingkat_kelas',
        'poin_minimum',
        'poin_maksimum',
        'jenis_sanksi',
        'deskripsi_sanksi',
        'penanggungjawab',
        'is_active'
    ];

    protected $casts = [
        'tingkat_kelas' => 'integer',
        'poin_minimum' => 'integer',
        'poin_maksimum' => 'integer',
        'is_active' => 'boolean'
    ];

    // Scope untuk sanksi aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk sanksi berdasarkan tingkat kelas
    public function scopeByTingkatKelas($query, $tingkatKelas)
    {
        return $query->where('tingkat_kelas', $tingkatKelas);
    }

    // Scope untuk sanksi berdasarkan poin tertentu
    public function scopeByPoin($query, $poin)
    {
        return $query->where('poin_minimum', '<=', $poin)
                    ->where('poin_maksimum', '>=', $poin);
    }

    // Method untuk mendapatkan sanksi yang sesuai berdasarkan tingkat kelas dan total poin
    public static function getSanksiByPoin($tingkatKelas, $totalPoin)
    {
        return self::active()
                  ->byTingkatKelas($tingkatKelas)
                  ->byPoin($totalPoin)
                  ->orderBy('poin_minimum', 'desc')
                  ->first();
    }

    // Method untuk mendapatkan semua sanksi berdasarkan tingkat kelas
    public static function getSanksiByTingkatKelas($tingkatKelas)
    {
        return self::active()
                  ->byTingkatKelas($tingkatKelas)
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

    // Accessor untuk mendapatkan tingkat kelas dalam format string
    public function getTingkatKelasLabelAttribute()
    {
        return 'Kelas ' . $this->tingkat_kelas;
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

    // Method untuk mendapatkan daftar tingkat kelas yang tersedia
    public static function getAvailableTingkatKelas()
    {
        return [
            7 => 'Kelas VII',
            8 => 'Kelas VIII', 
            9 => 'Kelas IX'
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