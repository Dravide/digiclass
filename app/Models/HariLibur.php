<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class HariLibur extends Model
{
    use HasFactory;

    protected $table = 'hari_libur';

    protected $fillable = [
        'tanggal',
        'tanggal_display',
        'keterangan',
        'is_cuti',
        'is_aktif'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_cuti' => 'boolean',
        'is_aktif' => 'boolean'
    ];

    /**
     * Cek apakah tanggal tertentu adalah hari libur
     */
    public static function isHariLibur(Carbon $tanggal): bool
    {
        // Cek hari Sabtu (6) dan Minggu (0)
        if (in_array($tanggal->dayOfWeek, [0, 6])) {
            return true;
        }

        // Cek hari libur nasional dari database
        return self::where('tanggal', $tanggal->format('Y-m-d'))
                   ->where('is_aktif', true)
                   ->exists();
    }

    /**
     * Cek apakah hari ini adalah hari libur
     */
    public static function isHariIniLibur(): bool
    {
        return self::isHariLibur(Carbon::today());
    }

    /**
     * Get semua hari libur untuk tahun tertentu
     */
    public static function getHariLiburTahun(int $tahun): \Illuminate\Database\Eloquent\Collection
    {
        return self::whereYear('tanggal', $tahun)
                   ->where('is_aktif', true)
                   ->orderBy('tanggal')
                   ->get();
    }

    /**
     * Get hari libur bulan ini
     */
    public static function getHariLiburBulanIni(): \Illuminate\Database\Eloquent\Collection
    {
        $now = Carbon::now();
        return self::whereYear('tanggal', $now->year)
                   ->whereMonth('tanggal', $now->month)
                   ->where('is_aktif', true)
                   ->orderBy('tanggal')
                   ->get();
    }

    /**
     * Sinkronisasi data dari API dayoffapi.vercel.app
     */
    public static function sinkronisasiDariApi(array $dataApi): int
    {
        $jumlahDisimpan = 0;

        foreach ($dataApi as $item) {
            $tanggal = Carbon::createFromFormat('Y-n-j', $item['tanggal']);
            
            $hariLibur = self::updateOrCreate(
                ['tanggal' => $tanggal->format('Y-m-d')],
                [
                    'tanggal_display' => $item['tanggal_display'],
                    'keterangan' => $item['keterangan'],
                    'is_cuti' => $item['is_cuti'],
                    'is_aktif' => true
                ]
            );

            if ($hariLibur->wasRecentlyCreated) {
                $jumlahDisimpan++;
            }
        }

        return $jumlahDisimpan;
    }

    /**
     * Get pesan untuk hari libur
     */
    public static function getPesanHariLibur(Carbon $tanggal): ?string
    {
        // Cek weekend
        if ($tanggal->dayOfWeek === 0) {
            return 'Presensi tidak tersedia pada hari Minggu';
        }
        if ($tanggal->dayOfWeek === 6) {
            return 'Presensi tidak tersedia pada hari Sabtu';
        }

        // Cek hari libur nasional
        $hariLibur = self::where('tanggal', $tanggal->format('Y-m-d'))
                          ->where('is_aktif', true)
                          ->first();

        if ($hariLibur) {
            return 'Presensi tidak tersedia: ' . $hariLibur->keterangan;
        }

        return null;
    }

    /**
     * Scope untuk hari libur aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    /**
     * Scope untuk tahun tertentu
     */
    public function scopeTahun($query, int $tahun)
    {
        return $query->whereYear('tanggal', $tahun);
    }
}