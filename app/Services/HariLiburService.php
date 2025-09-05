<?php

namespace App\Services;

use App\Models\HariLibur;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HariLiburService
{
    private const API_URL = 'https://dayoffapi.vercel.app/api';
    private const TIMEOUT = 30; // seconds

    /**
     * Ambil data hari libur dari API untuk tahun tertentu
     */
    public function ambilDataDariApi(int $tahun = null): array
    {
        if (!$tahun) {
            $tahun = Carbon::now()->year;
        }

        try {
            Log::info('Mengambil data hari libur dari API', ['tahun' => $tahun]);

            $response = Http::timeout(self::TIMEOUT)
                           ->get(self::API_URL, ['year' => $tahun]);

            if (!$response->successful()) {
                throw new \Exception('API response tidak berhasil: ' . $response->status());
            }

            $data = $response->json();

            if (!is_array($data)) {
                throw new \Exception('Format data API tidak valid');
            }

            Log::info('Data hari libur berhasil diambil', [
                'tahun' => $tahun,
                'jumlah_data' => count($data)
            ]);

            return $data;

        } catch (\Exception $e) {
            Log::error('Gagal mengambil data hari libur dari API', [
                'tahun' => $tahun,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Sinkronisasi data hari libur dari API ke database
     */
    public function sinkronisasiHariLibur(int $tahun = null): array
    {
        try {
            $dataApi = $this->ambilDataDariApi($tahun);
            
            $jumlahDisimpan = HariLibur::sinkronisasiDariApi($dataApi);
            $jumlahTotal = count($dataApi);
            $jumlahDiupdate = $jumlahTotal - $jumlahDisimpan;

            Log::info('Sinkronisasi hari libur selesai', [
                'tahun' => $tahun ?? Carbon::now()->year,
                'total_data' => $jumlahTotal,
                'data_baru' => $jumlahDisimpan,
                'data_diupdate' => $jumlahDiupdate
            ]);

            return [
                'success' => true,
                'total_data' => $jumlahTotal,
                'data_baru' => $jumlahDisimpan,
                'data_diupdate' => $jumlahDiupdate,
                'message' => "Sinkronisasi berhasil: {$jumlahDisimpan} data baru, {$jumlahDiupdate} data diupdate"
            ];

        } catch (\Exception $e) {
            Log::error('Gagal sinkronisasi hari libur', [
                'tahun' => $tahun,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal sinkronisasi: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cek apakah tanggal tertentu adalah hari libur
     */
    public function isHariLibur(Carbon $tanggal): bool
    {
        return HariLibur::isHariLibur($tanggal);
    }

    /**
     * Cek apakah hari ini adalah hari libur
     */
    public function isHariIniLibur(): bool
    {
        return HariLibur::isHariIniLibur();
    }

    /**
     * Get pesan untuk hari libur
     */
    public function getPesanHariLibur(Carbon $tanggal = null): ?string
    {
        if (!$tanggal) {
            $tanggal = Carbon::today();
        }

        return HariLibur::getPesanHariLibur($tanggal);
    }

    /**
     * Get semua hari libur untuk tahun tertentu
     */
    public function getHariLiburTahun(int $tahun): \Illuminate\Database\Eloquent\Collection
    {
        return HariLibur::getHariLiburTahun($tahun);
    }

    /**
     * Get hari libur bulan ini
     */
    public function getHariLiburBulanIni(): \Illuminate\Database\Eloquent\Collection
    {
        return HariLibur::getHariLiburBulanIni();
    }

    /**
     * Validasi apakah presensi dapat dilakukan hari ini
     */
    public function validasiPresensiHariIni(): array
    {
        $today = Carbon::today();
        $pesanLibur = $this->getPesanHariLibur($today);

        if ($pesanLibur) {
            return [
                'dapat_presensi' => false,
                'pesan' => $pesanLibur,
                'is_weekend' => in_array($today->dayOfWeek, [0, 6]),
                'is_hari_libur_nasional' => HariLibur::where('tanggal', $today->format('Y-m-d'))
                                                    ->where('is_aktif', true)
                                                    ->exists()
            ];
        }

        return [
            'dapat_presensi' => true,
            'pesan' => 'Presensi dapat dilakukan hari ini',
            'is_weekend' => false,
            'is_hari_libur_nasional' => false
        ];
    }

    /**
     * Auto sinkronisasi untuk tahun berjalan jika belum ada data
     */
    public function autoSinkronisasiJikaBelumAda(): array
    {
        $tahunSekarang = Carbon::now()->year;
        
        // Cek apakah sudah ada data untuk tahun ini
        $jumlahData = HariLibur::whereYear('tanggal', $tahunSekarang)->count();
        
        if ($jumlahData === 0) {
            Log::info('Tidak ada data hari libur untuk tahun ini, melakukan auto sinkronisasi', [
                'tahun' => $tahunSekarang
            ]);
            
            return $this->sinkronisasiHariLibur($tahunSekarang);
        }
        
        return [
            'success' => true,
            'message' => 'Data hari libur sudah tersedia',
            'total_data' => $jumlahData
        ];
    }
}