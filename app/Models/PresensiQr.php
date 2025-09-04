<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use App\Models\JamPresensi;
use App\Models\SecureCode;

class PresensiQr extends Model
{
    use HasFactory;

    protected $table = 'presensi_qr';

    protected $fillable = [
        'user_id',
        'secure_code',
        'jenis_presensi',
        'waktu_presensi',
        'keterangan',
        'foto_path',
        'is_terlambat',
        'menit_keterlambatan',
        'is_pulang_awal',
        'menit_kepulangan_awal',
        'jam_masuk_standar',
        'jam_pulang_standar',
    ];

    protected $casts = [
        'waktu_presensi' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_terlambat' => 'boolean',
        'is_pulang_awal' => 'boolean',
        'jam_masuk_standar' => 'datetime:H:i:s',
        'jam_pulang_standar' => 'datetime:H:i:s',
    ];

    /**
     * Relationship dengan User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cek apakah user sudah presensi masuk hari ini
     */
    public static function sudahPresensiMasukHariIni(int $userId): bool
    {
        return self::where('user_id', $userId)
                   ->where('jenis_presensi', 'masuk')
                   ->whereDate('waktu_presensi', Carbon::today())
                   ->exists();
    }

    /**
     * Cek apakah user sudah presensi pulang hari ini
     */
    public static function sudahPresensiPulangHariIni(int $userId): bool
    {
        return self::where('user_id', $userId)
                   ->where('jenis_presensi', 'pulang')
                   ->whereDate('waktu_presensi', Carbon::today())
                   ->exists();
    }

    /**
     * Buat presensi baru berdasarkan secure code
     */
    public static function buatPresensi(string $secureCode, string $jenisPresensi, ?string $fotoPath = null): self
    {
        // Cari user berdasarkan secure code
        $secureCodeModel = SecureCode::where('secure_code', $secureCode)->first();
        
        if (!$secureCodeModel) {
            throw new \Exception('QR Code tidak valid atau tidak ditemukan.');
        }

        $userId = $secureCodeModel->user_id;
        
        // Validasi jenis presensi
        if (!in_array($jenisPresensi, ['masuk', 'pulang'])) {
            throw new \Exception('Jenis presensi tidak valid.');
        }

        // Validasi jam presensi berdasarkan pengaturan admin
        $validasiJam = JamPresensi::validasiJamPresensi($jenisPresensi);
        $isTerlambat = !$validasiJam['valid'];
        
        // Ambil pengaturan jam presensi untuk menghitung keterlambatan
        $jamPresensi = JamPresensi::getJamPresensiHari();
        $waktuSekarang = Carbon::now();
        $menitKeterlambatan = null;
        $isPulangAwal = false;
        $menitKepulanganAwal = null;
        $jamMasukStandar = '07:00:00';
        $jamPulangStandar = '15:00:00';
        
        if ($jamPresensi) {
            $jamMasukStandar = Carbon::parse($jamPresensi->jam_masuk_selesai)->format('H:i:s');
            $jamPulangStandar = Carbon::parse($jamPresensi->jam_pulang_mulai)->format('H:i:s');
            
            if ($jenisPresensi === 'masuk' && $isTerlambat) {
                $jamBatasMasuk = Carbon::parse($jamPresensi->jam_masuk_selesai);
                $menitKeterlambatan = $waktuSekarang->diffInMinutes($jamBatasMasuk);
            } elseif ($jenisPresensi === 'pulang') {
                $jamMinimalPulang = Carbon::parse($jamPresensi->jam_pulang_mulai);
                if ($waktuSekarang->lt($jamMinimalPulang)) {
                    $isPulangAwal = true;
                    $menitKepulanganAwal = $jamMinimalPulang->diffInMinutes($waktuSekarang);
                }
            }
        }

        // Cek apakah sudah presensi sesuai jenis hari ini
        if ($jenisPresensi === 'masuk' && self::sudahPresensiMasukHariIni($userId)) {
            throw new \Exception('Anda sudah melakukan presensi masuk hari ini.');
        }

        if ($jenisPresensi === 'pulang' && self::sudahPresensiPulangHariIni($userId)) {
            throw new \Exception('Anda sudah melakukan presensi pulang hari ini.');
        }

        // Jika presensi pulang, pastikan sudah presensi masuk dulu
        if ($jenisPresensi === 'pulang' && !self::sudahPresensiMasukHariIni($userId)) {
            throw new \Exception('Anda harus melakukan presensi masuk terlebih dahulu.');
        }

        return self::create([
            'user_id' => $userId,
            'secure_code' => $secureCode,
            'jenis_presensi' => $jenisPresensi,
            'waktu_presensi' => $waktuSekarang,
            'foto_path' => $fotoPath,
            'is_terlambat' => $isTerlambat,
            'menit_keterlambatan' => $menitKeterlambatan,
            'is_pulang_awal' => $isPulangAwal,
            'menit_kepulangan_awal' => $menitKepulanganAwal,
            'jam_masuk_standar' => $jamMasukStandar,
            'jam_pulang_standar' => $jamPulangStandar,
        ]);
    }

    /**
     * Get presensi hari ini untuk user
     */
    public static function presensiHariIni(int $userId): array
    {
        $presensiMasuk = self::where('user_id', $userId)
                            ->where('jenis_presensi', 'masuk')
                            ->whereDate('waktu_presensi', Carbon::today())
                            ->first();

        $presensiPulang = self::where('user_id', $userId)
                             ->where('jenis_presensi', 'pulang')
                             ->whereDate('waktu_presensi', Carbon::today())
                             ->first();

        return [
            'masuk' => $presensiMasuk,
            'pulang' => $presensiPulang,
        ];
    }
}
