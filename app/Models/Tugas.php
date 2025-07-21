<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';

    protected $fillable = [
        'judul',
        'deskripsi',
        'mata_pelajaran_id',
        'kelas_id',
        'guru_id',
        'tanggal_pemberian',
        'tanggal_deadline',
        'jenis',
        'bobot',
        'status',
        'catatan'
    ];

    protected $casts = [
        'tanggal_pemberian' => 'date',
        'tanggal_deadline' => 'date',
    ];

    // Relasi ke mata pelajaran
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    // Relasi ke kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi ke guru
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    // Relasi ke nilai
    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    // Accessor untuk status badge class
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'aktif' => 'bg-success',
            'selesai' => 'bg-secondary',
            'draft' => 'bg-warning',
            default => 'bg-secondary'
        };
    }

    // Accessor untuk jenis badge class
    public function getJenisBadgeClassAttribute()
    {
        return match($this->jenis) {
            'tugas_harian' => 'bg-primary',
            'ulangan_harian' => 'bg-info',
            'uts' => 'bg-warning',
            'uas' => 'bg-danger',
            'praktikum' => 'bg-success',
            'project' => 'bg-dark',
            default => 'bg-secondary'
        };
    }

    // Accessor untuk jenis label
    public function getJenisLabelAttribute()
    {
        return match($this->jenis) {
            'tugas_harian' => 'Tugas Harian',
            'ulangan_harian' => 'Ulangan Harian',
            'uts' => 'UTS',
            'uas' => 'UAS',
            'praktikum' => 'Praktikum',
            'project' => 'Project',
            default => ucfirst($this->jenis)
        };
    }

    // Cek apakah tugas sudah melewati deadline
    public function getIsOverdueAttribute()
    {
        return Carbon::now()->isAfter($this->tanggal_deadline);
    }

    // Hitung rata-rata nilai untuk tugas ini
    public function getRataNilaiAttribute()
    {
        return $this->nilai()->whereNotNull('nilai')->avg('nilai') ?? 0;
    }

    // Hitung jumlah siswa yang sudah mengumpulkan
    public function getJumlahSudahMengumpulkanAttribute()
    {
        return $this->nilai()->where('status_pengumpulan', '!=', 'belum_mengumpulkan')->count();
    }

    // Hitung total siswa di kelas
    public function getTotalSiswaAttribute()
    {
        return $this->kelas->kelasSiswa()->count();
    }
}