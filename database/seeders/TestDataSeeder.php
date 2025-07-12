<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Guru;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample Kelas data
        $kelasData = [
            ['nama_kelas' => '10 IPA 1', 'tingkat' => 10],
            ['nama_kelas' => '10 IPA 2', 'tingkat' => 10],
            ['nama_kelas' => '10 IPS 1', 'tingkat' => 10],
            ['nama_kelas' => '11 IPA 1', 'tingkat' => 11],
            ['nama_kelas' => '11 IPA 2', 'tingkat' => 11],
            ['nama_kelas' => '11 IPS 1', 'tingkat' => 11],
            ['nama_kelas' => '12 IPA 1', 'tingkat' => 12],
            ['nama_kelas' => '12 IPA 2', 'tingkat' => 12],
            ['nama_kelas' => '12 IPS 1', 'tingkat' => 12],
        ];

        foreach ($kelasData as $kelas) {
            Kelas::firstOrCreate(
                ['nama_kelas' => $kelas['nama_kelas']],
                $kelas
            );
        }

        // Create sample Guru data
        $guruData = [
            ['nama_guru' => 'Dra. Siti Aminah', 'nip' => '196501011990032001', 'mata_pelajaran' => 'Matematika', 'is_wali_kelas' => true],
            ['nama_guru' => 'Ahmad Fauzi, S.Pd', 'nip' => '197203151998021001', 'mata_pelajaran' => 'Fisika', 'is_wali_kelas' => true],
            ['nama_guru' => 'Dr. Rina Susanti, M.Pd', 'nip' => '198005102005012002', 'mata_pelajaran' => 'Kimia', 'is_wali_kelas' => true],
            ['nama_guru' => 'Budi Santoso, S.Pd', 'nip' => '197812252003121001', 'mata_pelajaran' => 'Biologi', 'is_wali_kelas' => true],
            ['nama_guru' => 'Dewi Kartika, S.Pd', 'nip' => '198209182006042001', 'mata_pelajaran' => 'Bahasa Indonesia', 'is_wali_kelas' => true],
            ['nama_guru' => 'Hendra Wijaya, S.Pd', 'nip' => '197506301999031002', 'mata_pelajaran' => 'Bahasa Inggris', 'is_wali_kelas' => true],
            ['nama_guru' => 'Indah Permata, M.Pd', 'nip' => '198403152008012001', 'mata_pelajaran' => 'Sejarah', 'is_wali_kelas' => true],
            ['nama_guru' => 'Joko Prasetyo, S.Pd', 'nip' => '197711202000121001', 'mata_pelajaran' => 'Geografi', 'is_wali_kelas' => true],
            ['nama_guru' => 'Lestari Wulandari, S.Pd', 'nip' => '198601252010012002', 'mata_pelajaran' => 'Ekonomi', 'is_wali_kelas' => true],
        ];

        foreach ($guruData as $guru) {
            Guru::firstOrCreate(
                ['nip' => $guru['nip']],
                $guru
            );
        }

        $this->command->info('Test data seeded successfully!');
    }
}
