<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\TahunPelajaran;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil tahun pelajaran aktif
        $tahunPelajaranAktif = TahunPelajaran::where('is_active', true)->first();
        
        if (!$tahunPelajaranAktif) {
            // Jika tidak ada tahun pelajaran aktif, ambil yang pertama
            $tahunPelajaranAktif = TahunPelajaran::first();
        }

        $kelasData = [
            // Kelas 10
            ['nama_kelas' => '10 IPA 1', 'tingkat' => 10, 'jurusan' => 'IPA', 'kapasitas' => 36, 'tahun_pelajaran_id' => $tahunPelajaranAktif->id],
            ['nama_kelas' => '10 IPA 2', 'tingkat' => 10, 'jurusan' => 'IPA', 'kapasitas' => 36, 'tahun_pelajaran_id' => $tahunPelajaranAktif->id],
            ['nama_kelas' => '10 IPS 1', 'tingkat' => 10, 'jurusan' => 'IPS', 'kapasitas' => 36, 'tahun_pelajaran_id' => $tahunPelajaranAktif->id],
            ['nama_kelas' => '10 IPS 2', 'tingkat' => 10, 'jurusan' => 'IPS', 'kapasitas' => 36, 'tahun_pelajaran_id' => $tahunPelajaranAktif->id],
            
            // Kelas 11
            ['nama_kelas' => '11 IPA 1', 'tingkat' => 11, 'jurusan' => 'IPA', 'kapasitas' => 36, 'tahun_pelajaran_id' => $tahunPelajaranAktif->id],
            ['nama_kelas' => '11 IPA 2', 'tingkat' => 11, 'jurusan' => 'IPA', 'kapasitas' => 36, 'tahun_pelajaran_id' => $tahunPelajaranAktif->id],
            ['nama_kelas' => '11 IPS 1', 'tingkat' => 11, 'jurusan' => 'IPS', 'kapasitas' => 36, 'tahun_pelajaran_id' => $tahunPelajaranAktif->id],
            ['nama_kelas' => '11 IPS 2', 'tingkat' => 11, 'jurusan' => 'IPS', 'kapasitas' => 36, 'tahun_pelajaran_id' => $tahunPelajaranAktif->id],
            
            // Kelas 12
            ['nama_kelas' => '12 IPA 1', 'tingkat' => 12, 'jurusan' => 'IPA', 'kapasitas' => 36, 'tahun_pelajaran_id' => $tahunPelajaranAktif->id],
            ['nama_kelas' => '12 IPA 2', 'tingkat' => 12, 'jurusan' => 'IPA', 'kapasitas' => 36, 'tahun_pelajaran_id' => $tahunPelajaranAktif->id],
            ['nama_kelas' => '12 IPS 1', 'tingkat' => 12, 'jurusan' => 'IPS', 'kapasitas' => 36, 'tahun_pelajaran_id' => $tahunPelajaranAktif->id],
            ['nama_kelas' => '12 IPS 2', 'tingkat' => 12, 'jurusan' => 'IPS', 'kapasitas' => 36, 'tahun_pelajaran_id' => $tahunPelajaranAktif->id],
        ];

        foreach ($kelasData as $kelas) {
            Kelas::create($kelas);
        }
    }
}
