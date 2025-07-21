<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = [
        'tugas_id',
        'siswa_id',
        'nilai',
        'status_pengumpulan',
        'tanggal_pengumpulan',
        'catatan_guru',
        'catatan_siswa',
        'file_tugas'
    ];

    protected $casts = [
        'tanggal_pengumpulan' => 'datetime',
        'nilai' => 'decimal:2'
    ];

    // Relasi ke tugas
    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Accessor untuk status badge class
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status_pengumpulan) {
            'tepat_waktu' => 'bg-success',
            'terlambat' => 'bg-warning',
            'belum_mengumpulkan' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute()
    {
        return match($this->status_pengumpulan) {
            'tepat_waktu' => 'Tepat Waktu',
            'terlambat' => 'Terlambat',
            'belum_mengumpulkan' => 'Belum Mengumpulkan',
            default => ucfirst(str_replace('_', ' ', $this->status_pengumpulan))
        };
    }

    // Accessor untuk grade/huruf nilai
    public function getGradeAttribute()
    {
        if (is_null($this->nilai)) {
            return '-';
        }

        return match(true) {
            $this->nilai >= 90 => 'A',
            $this->nilai >= 80 => 'B',
            $this->nilai >= 70 => 'C',
            $this->nilai >= 60 => 'D',
            default => 'E'
        };
    }

    // Accessor untuk warna grade
    public function getGradeColorAttribute()
    {
        return match($this->grade) {
            'A' => 'text-success',
            'B' => 'text-info',
            'C' => 'text-warning',
            'D' => 'text-danger',
            'E' => 'text-dark',
            default => 'text-muted'
        };
    }

    // Format nilai untuk tampilan
    public function getFormattedNilaiAttribute()
    {
        return is_null($this->nilai) ? '-' : number_format($this->nilai, 1);
    }
}