<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JamPresensi extends Model
{
    use HasFactory;

    protected $table = 'jam_presensi';

    protected $fillable = [
        'nama_hari',
        'jam_masuk_mulai',
        'jam_masuk_selesai',
        'jam_pulang_mulai',
        'jam_pulang_selesai',
        'jam_lembur_mulai',
        'jam_lembur_selesai',
        'is_active',
        'keterangan',
    ];

    protected $casts = [
        'jam_masuk_mulai' => 'datetime:H:i:s',
        'jam_masuk_selesai' => 'datetime:H:i:s',
        'jam_pulang_mulai' => 'datetime:H:i:s',
        'jam_pulang_selesai' => 'datetime:H:i:s',
        'jam_lembur_mulai' => 'datetime:H:i:s',
        'jam_lembur_selesai' => 'datetime:H:i:s',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Daftar hari dalam seminggu
     */
    public static function getDaftarHari(): array
    {
        return [
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu',
            'default' // Untuk pengaturan default semua hari
        ];
    }

    /**
     * Get jam presensi untuk hari tertentu atau default
     */
    public static function getJamPresensiHari(string $namaHari = null): ?self
    {
        if (!$namaHari) {
            $namaHari = self::getNamaHariIni();
        }

        // Cari pengaturan untuk hari spesifik
        $jamPresensi = self::where('nama_hari', $namaHari)
                          ->where('is_active', true)
                          ->first();

        // Jika tidak ada, cari pengaturan default
        if (!$jamPresensi) {
            $jamPresensi = self::where('nama_hari', 'default')
                              ->where('is_active', true)
                              ->first();
        }

        return $jamPresensi;
    }

    /**
     * Get nama hari ini dalam bahasa Indonesia
     */
    public static function getNamaHariIni(): string
    {
        $hariInggris = Carbon::now()->format('l');
        $hariIndonesia = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        return $hariIndonesia[$hariInggris] ?? 'Senin';
    }

    /**
     * Cek apakah waktu saat ini dalam rentang presensi masuk
     */
    public function bisaPresensiMasuk(): bool
    {
        $sekarang = Carbon::now()->format('H:i');
        $jamMasukMulai = Carbon::parse($this->jam_masuk_mulai)->format('H:i');
        $jamMasukSelesai = Carbon::parse($this->jam_masuk_selesai)->format('H:i');

        return $sekarang >= $jamMasukMulai && $sekarang <= $jamMasukSelesai;
    }

    /**
     * Cek apakah waktu saat ini dalam rentang presensi pulang
     */
    public function bisaPresensiPulang(): bool
    {
        $sekarang = Carbon::now()->format('H:i');
        $jamPulangMulai = Carbon::parse($this->jam_pulang_mulai)->format('H:i');
        $jamPulangSelesai = Carbon::parse($this->jam_pulang_selesai)->format('H:i');

        return $sekarang >= $jamPulangMulai && $sekarang <= $jamPulangSelesai;
    }

    /**
     * Cek apakah waktu saat ini dalam rentang presensi lembur
     */
    public function bisaPresensiLembur(): bool
    {
        if (!$this->jam_lembur_mulai || !$this->jam_lembur_selesai) {
            return false;
        }

        $sekarang = Carbon::now()->format('H:i');
        $jamLemburMulai = Carbon::parse($this->jam_lembur_mulai)->format('H:i');
        $jamLemburSelesai = Carbon::parse($this->jam_lembur_selesai)->format('H:i');

        return $sekarang >= $jamLemburMulai && $sekarang <= $jamLemburSelesai;
    }

    /**
     * Validasi jam presensi berdasarkan jenis
     */
    public static function validasiJamPresensi(string $jenisPresensi): array
    {
        $jamPresensi = self::getJamPresensiHari();
        
        if (!$jamPresensi) {
            return [
                'valid' => false,
                'pesan' => 'Pengaturan jam presensi belum dikonfigurasi untuk hari ini.'
            ];
        }

        $sekarang = Carbon::now();
        $waktuSekarang = $sekarang->format('H:i');

        if ($jenisPresensi === 'masuk') {
            if (!$jamPresensi->bisaPresensiMasuk()) {
                $jamMulai = Carbon::parse($jamPresensi->jam_masuk_mulai)->format('H:i');
                $jamSelesai = Carbon::parse($jamPresensi->jam_masuk_selesai)->format('H:i');
                
                return [
                    'valid' => false,
                    'pesan' => "Presensi masuk hanya dapat dilakukan antara jam {$jamMulai} - {$jamSelesai}. Waktu sekarang: {$waktuSekarang}"
                ];
            }
        } elseif ($jenisPresensi === 'pulang') {
            if (!$jamPresensi->bisaPresensiPulang()) {
                $jamMulai = Carbon::parse($jamPresensi->jam_pulang_mulai)->format('H:i');
                $jamSelesai = Carbon::parse($jamPresensi->jam_pulang_selesai)->format('H:i');
                
                return [
                    'valid' => false,
                    'pesan' => "Presensi pulang hanya dapat dilakukan antara jam {$jamMulai} - {$jamSelesai}. Waktu sekarang: {$waktuSekarang}"
                ];
            }
        } elseif ($jenisPresensi === 'lembur') {
            if (!$jamPresensi->bisaPresensiLembur()) {
                if (!$jamPresensi->jam_lembur_mulai || !$jamPresensi->jam_lembur_selesai) {
                    return [
                        'valid' => false,
                        'pesan' => 'Jam lembur belum dikonfigurasi untuk hari ini.'
                    ];
                }
                
                $jamMulai = Carbon::parse($jamPresensi->jam_lembur_mulai)->format('H:i');
                $jamSelesai = Carbon::parse($jamPresensi->jam_lembur_selesai)->format('H:i');
                
                return [
                    'valid' => false,
                    'pesan' => "Presensi lembur hanya dapat dilakukan antara jam {$jamMulai} - {$jamSelesai}. Waktu sekarang: {$waktuSekarang}"
                ];
            }
        }

        return [
            'valid' => true,
            'pesan' => 'Waktu presensi valid.'
        ];
    }

    /**
     * Buat pengaturan default untuk semua hari
     */
    public static function buatPengaturanDefault(): self
    {
        return self::create([
            'nama_hari' => 'default',
            'jam_masuk_mulai' => '07:00',
            'jam_masuk_selesai' => '08:00',
            'jam_pulang_mulai' => '15:00',
            'jam_pulang_selesai' => '17:00',
            'is_active' => true,
            'keterangan' => 'Pengaturan default untuk semua hari'
        ]);
    }
}