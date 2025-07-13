<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataPelajaranData = [
            // Mata Pelajaran Wajib
            ['kode_mapel' => 'MTK', 'nama_mapel' => 'Matematika', 'kategori' => 'Wajib', 'jam_pelajaran' => 4, 'deskripsi' => 'Mata pelajaran matematika wajib'],
            ['kode_mapel' => 'BIN', 'nama_mapel' => 'Bahasa Indonesia', 'kategori' => 'Wajib', 'jam_pelajaran' => 4, 'deskripsi' => 'Mata pelajaran bahasa Indonesia'],
            ['kode_mapel' => 'BING', 'nama_mapel' => 'Bahasa Inggris', 'kategori' => 'Wajib', 'jam_pelajaran' => 3, 'deskripsi' => 'Mata pelajaran bahasa Inggris'],
            ['kode_mapel' => 'FIS', 'nama_mapel' => 'Fisika', 'kategori' => 'Peminatan IPA', 'jam_pelajaran' => 4, 'deskripsi' => 'Mata pelajaran fisika untuk peminatan IPA'],
            ['kode_mapel' => 'KIM', 'nama_mapel' => 'Kimia', 'kategori' => 'Peminatan IPA', 'jam_pelajaran' => 4, 'deskripsi' => 'Mata pelajaran kimia untuk peminatan IPA'],
            ['kode_mapel' => 'BIO', 'nama_mapel' => 'Biologi', 'kategori' => 'Peminatan IPA', 'jam_pelajaran' => 4, 'deskripsi' => 'Mata pelajaran biologi untuk peminatan IPA'],
            ['kode_mapel' => 'SEJ', 'nama_mapel' => 'Sejarah', 'kategori' => 'Peminatan IPS', 'jam_pelajaran' => 3, 'deskripsi' => 'Mata pelajaran sejarah untuk peminatan IPS'],
            ['kode_mapel' => 'GEO', 'nama_mapel' => 'Geografi', 'kategori' => 'Peminatan IPS', 'jam_pelajaran' => 3, 'deskripsi' => 'Mata pelajaran geografi untuk peminatan IPS'],
            ['kode_mapel' => 'EKO', 'nama_mapel' => 'Ekonomi', 'kategori' => 'Peminatan IPS', 'jam_pelajaran' => 4, 'deskripsi' => 'Mata pelajaran ekonomi untuk peminatan IPS'],
            ['kode_mapel' => 'SOS', 'nama_mapel' => 'Sosiologi', 'kategori' => 'Peminatan IPS', 'jam_pelajaran' => 3, 'deskripsi' => 'Mata pelajaran sosiologi untuk peminatan IPS'],
            
            // Mata Pelajaran Umum
            ['kode_mapel' => 'PKN', 'nama_mapel' => 'Pendidikan Kewarganegaraan', 'kategori' => 'Wajib', 'jam_pelajaran' => 2, 'deskripsi' => 'Mata pelajaran PKN'],
            ['kode_mapel' => 'PAI', 'nama_mapel' => 'Pendidikan Agama Islam', 'kategori' => 'Wajib', 'jam_pelajaran' => 3, 'deskripsi' => 'Mata pelajaran agama Islam'],
            ['kode_mapel' => 'PJOK', 'nama_mapel' => 'Pendidikan Jasmani dan Kesehatan', 'kategori' => 'Wajib', 'jam_pelajaran' => 3, 'deskripsi' => 'Mata pelajaran olahraga'],
            ['kode_mapel' => 'SBK', 'nama_mapel' => 'Seni Budaya', 'kategori' => 'Wajib', 'jam_pelajaran' => 2, 'deskripsi' => 'Mata pelajaran seni dan budaya'],
            ['kode_mapel' => 'PKWU', 'nama_mapel' => 'Prakarya dan Kewirausahaan', 'kategori' => 'Wajib', 'jam_pelajaran' => 2, 'deskripsi' => 'Mata pelajaran prakarya dan kewirausahaan'],
        ];

        foreach ($mataPelajaranData as $mapel) {
            MataPelajaran::firstOrCreate(
                ['kode_mapel' => $mapel['kode_mapel']],
                $mapel
            );
        }

        $this->command->info('Mata Pelajaran seeded successfully!');
    }
}