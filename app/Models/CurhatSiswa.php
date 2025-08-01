<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CurhatSiswa extends Model
{
    use HasFactory;

    protected $table = 'curhat_siswa';

    protected $fillable = [
        'siswa_id',
        'tahun_pelajaran_id',
        'kategori',
        'judul',
        'isi_curhat',
        'is_anonim',
        'status',
        'tanggal_curhat',
        'nama_pengirim',
        'kelas_pengirim',
        'tanggal_penanganan',
        'penanganan',
        'ditangani_oleh',
        'catatan_internal'
    ];

    protected $casts = [
        'tanggal_curhat' => 'datetime',
        'tanggal_penanganan' => 'datetime',
        'is_anonim' => 'boolean'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_DIPROSES = 'diproses';
    const STATUS_SELESAI = 'selesai';
    const STATUS_DITUTUP = 'ditutup';

    // Kategori constants
    const KATEGORI_AKADEMIK = 'akademik';
    const KATEGORI_SOSIAL = 'sosial';
    const KATEGORI_KELUARGA = 'keluarga';
    const KATEGORI_PRIBADI = 'pribadi';
    const KATEGORI_BULLYING = 'bullying';
    const KATEGORI_KESEHATAN = 'kesehatan';
    const KATEGORI_KARIR = 'karir';
    const KATEGORI_LAINNYA = 'lainnya';

    /**
     * Relationship with Siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Relationship with TahunPelajaran
     */
    public function tahunPelajaran()
    {
        return $this->belongsTo(TahunPelajaran::class);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope untuk curhat yang belum direspon
     */
    public function scopeBelumDirespon($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_DIPROSES]);
    }

    /**
     * Scope untuk curhat anonim
     */
    public function scopeAnonim($query)
    {
        return $query->where('is_anonim', true);
    }

    /**
     * Scope untuk curhat non-anonim
     */
    public function scopeNonAnonim($query)
    {
        return $query->where('is_anonim', false);
    }

    /**
     * Accessor untuk mendapatkan label status
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_DIPROSES => 'Diproses',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DITUTUP => 'Ditutup'
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    /**
     * Accessor untuk mendapatkan label kategori
     */
    public function getKategoriLabelAttribute()
    {
        $labels = [
            self::KATEGORI_AKADEMIK => 'Masalah Akademik',
            self::KATEGORI_SOSIAL => 'Masalah Sosial/Pertemanan',
            self::KATEGORI_KELUARGA => 'Masalah Keluarga',
            self::KATEGORI_PRIBADI => 'Masalah Pribadi',
            self::KATEGORI_BULLYING => 'Bullying/Intimidasi',
            self::KATEGORI_KESEHATAN => 'Masalah Kesehatan Mental',
            self::KATEGORI_KARIR => 'Konsultasi Karir/Masa Depan',
            self::KATEGORI_LAINNYA => 'Lainnya'
        ];

        return $labels[$this->kategori] ?? 'Unknown';
    }

    /**
     * Accessor untuk mendapatkan warna badge status
     */
    public function getStatusBadgeColorAttribute()
    {
        $colors = [
            self::STATUS_PENDING => 'warning',
            self::STATUS_DIPROSES => 'info',
            self::STATUS_SELESAI => 'success',
            self::STATUS_DITUTUP => 'secondary'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Accessor untuk mendapatkan warna badge kategori
     */
    public function getKategoriBadgeColorAttribute()
    {
        $colors = [
            self::KATEGORI_AKADEMIK => 'primary',
            self::KATEGORI_SOSIAL => 'info',
            self::KATEGORI_KELUARGA => 'success',
            self::KATEGORI_PRIBADI => 'warning',
            self::KATEGORI_BULLYING => 'danger',
            self::KATEGORI_KESEHATAN => 'purple',
            self::KATEGORI_KARIR => 'dark',
            self::KATEGORI_LAINNYA => 'secondary'
        ];

        return $colors[$this->kategori] ?? 'secondary';
    }

    /**
     * Method untuk mendapatkan semua status yang tersedia
     */
    public static function getAvailableStatuses()
    {
        return [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_DIPROSES => 'Diproses',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DITUTUP => 'Ditutup'
        ];
    }

    /**
     * Method untuk mendapatkan semua kategori yang tersedia
     */
    public static function getAvailableKategori()
    {
        return [
            self::KATEGORI_AKADEMIK => 'Masalah Akademik',
            self::KATEGORI_SOSIAL => 'Masalah Sosial/Pertemanan',
            self::KATEGORI_KELUARGA => 'Masalah Keluarga',
            self::KATEGORI_PRIBADI => 'Masalah Pribadi',
            self::KATEGORI_BULLYING => 'Bullying/Intimidasi',
            self::KATEGORI_KESEHATAN => 'Masalah Kesehatan Mental',
            self::KATEGORI_KARIR => 'Konsultasi Karir/Masa Depan',
            self::KATEGORI_LAINNYA => 'Lainnya'
        ];
    }

    /**
     * Method untuk menandai curhat sebagai sudah direspon
     */
    public function markAsResponded($respon, $petugasBk, $catatanInternal = null)
    {
        $this->update([
            'status' => self::STATUS_SELESAI,
            'tanggal_respon' => Carbon::now(),
            'respon_bk' => $respon,
            'petugas_bk' => $petugasBk,
            'catatan_internal' => $catatanInternal
        ]);
    }

    /**
     * Method untuk mengubah status curhat
     */
    public function changeStatus($status)
    {
        $this->update(['status' => $status]);
    }
}