<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TahunPelajaran;
use Carbon\Carbon;

class TahunPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunPelajarans = [
            [
                'nama_tahun_pelajaran' => '2023/2024',
                'tanggal_mulai' => '2023-07-01',
                'tanggal_selesai' => '2024-06-30',
                'is_active' => false,
                'keterangan' => 'Tahun pelajaran 2023/2024'
            ],
            [
                'nama_tahun_pelajaran' => '2024/2025',
                'tanggal_mulai' => '2024-07-01',
                'tanggal_selesai' => '2025-06-30',
                'is_active' => true,
                'keterangan' => 'Tahun pelajaran aktif 2024/2025'
            ],
            [
                'nama_tahun_pelajaran' => '2025/2026',
                'tanggal_mulai' => '2025-07-01',
                'tanggal_selesai' => '2026-06-30',
                'is_active' => false,
                'keterangan' => 'Tahun pelajaran mendatang 2025/2026'
            ]
        ];

        foreach ($tahunPelajarans as $tahunPelajaran) {
            TahunPelajaran::create($tahunPelajaran);
        }
    }
}
