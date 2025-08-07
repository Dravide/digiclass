<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PelanggaranSiswa extends Model
{
    protected $table = 'pelanggaran_siswa';
    
    protected $fillable = [
        'siswa_id',
        'tahun_pelajaran_id',
        'jenis_pelanggaran',
        'deskripsi_pelanggaran',
        'poin_pelanggaran',
        'tanggal_pelanggaran',
        'pelapor',
        'tindak_lanjut',
        'status_penanganan',
        'catatan'
    ];

    protected $casts = [
        'tanggal_pelanggaran' => 'date',
        'poin_pelanggaran' => 'integer'
    ];

    // Constants untuk status penanganan
    const STATUS_BELUM_DITANGANI = 'belum_ditangani';
    const STATUS_DALAM_PROSES = 'dalam_proses';
    const STATUS_SELESAI = 'selesai';

    // Relationship dengan siswa
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relationship dengan tahun pelajaran
    public function tahunPelajaran(): BelongsTo
    {
        return $this->belongsTo(TahunPelajaran::class);
    }

    // Relationship dengan jenis pelanggaran
    public function jenisPelanggaran(): BelongsTo
    {
        return $this->belongsTo(JenisPelanggaran::class, 'jenis_pelanggaran', 'nama_pelanggaran');
    }

    // Scope untuk pelanggaran berdasarkan tahun pelajaran aktif
    public function scopeActive($query)
    {
        return $query->whereHas('tahunPelajaran', function ($q) {
            $q->where('is_active', true);
        });
    }

    // Scope untuk pelanggaran berdasarkan tahun pelajaran tertentu
    public function scopeByTahunPelajaran($query, $tahunPelajaranId)
    {
        return $query->where('tahun_pelajaran_id', $tahunPelajaranId);
    }

    // Scope untuk pelanggaran berdasarkan siswa
    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    // Scope untuk pelanggaran berdasarkan status penanganan
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_penanganan', $status);
    }

    // Scope untuk pelanggaran dalam rentang tanggal
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_pelanggaran', [$startDate, $endDate]);
    }

    // Method untuk mendapatkan total poin pelanggaran siswa dalam tahun pelajaran tertentu
    public static function getTotalPoinSiswa($siswaId, $tahunPelajaranId = null)
    {
        $query = self::where('siswa_id', $siswaId);
        
        if ($tahunPelajaranId) {
            $query->where('tahun_pelajaran_id', $tahunPelajaranId);
        } else {
            $query->whereHas('tahunPelajaran', function ($q) {
                $q->where('is_active', true);
            });
        }
        
        return $query->sum('poin_pelanggaran');
    }

    // Method untuk mendapatkan daftar status yang tersedia
    public static function getAvailableStatuses()
    {
        return [
            self::STATUS_BELUM_DITANGANI => 'Belum Ditangani',
            self::STATUS_DALAM_PROSES => 'Dalam Proses',
            self::STATUS_SELESAI => 'Selesai'
        ];
    }

    // Accessor untuk mendapatkan label status
    public function getStatusLabelAttribute()
    {
        $statuses = self::getAvailableStatuses();
        return $statuses[$this->status_penanganan] ?? $this->status_penanganan;
    }

    // Accessor untuk format tanggal pelanggaran
    public function getTanggalPelanggaranFormattedAttribute()
    {
        return $this->tanggal_pelanggaran ? $this->tanggal_pelanggaran->format('d/m/Y') : '-';
    }

    // Method untuk mengecek apakah pelanggaran sudah ditangani
    public function isSudahDitangani()
    {
        return $this->status_penanganan === self::STATUS_SELESAI;
    }

    // Method untuk mengecek apakah pelanggaran dalam proses penanganan
    public function isDalamProses()
    {
        return $this->status_penanganan === self::STATUS_DALAM_PROSES;
    }

    // Method untuk mengecek apakah pelanggaran belum ditangani
    public function isBelumDitangani()
    {
        return $this->status_penanganan === self::STATUS_BELUM_DITANGANI;
    }
}