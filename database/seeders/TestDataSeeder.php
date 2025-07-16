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
            ['nama_guru' => 'Dra. Siti Aminah', 'nip' => '196501011990032001', 'email' => 'siti.aminah@smpn1cipanas.sch.id', 'telepon' => '081234567801', 'mata_pelajaran_kode' => 'MTK', 'is_wali_kelas' => true],
            ['nama_guru' => 'Ahmad Fauzi, S.Pd', 'nip' => '197203151998021001', 'email' => 'ahmad.fauzi@smpn1cipanas.sch.id', 'telepon' => '081234567802', 'mata_pelajaran_kode' => 'FIS', 'is_wali_kelas' => true],
            ['nama_guru' => 'Dr. Rina Susanti, M.Pd', 'nip' => '198005102005012002', 'email' => 'rina.susanti@smpn1cipanas.sch.id', 'telepon' => '081234567803', 'mata_pelajaran_kode' => 'KIM', 'is_wali_kelas' => true],
            ['nama_guru' => 'Budi Santoso, S.Pd', 'nip' => '197812252003121001', 'email' => 'budi.santoso@smpn1cipanas.sch.id', 'telepon' => '081234567804', 'mata_pelajaran_kode' => 'BIO', 'is_wali_kelas' => true],
            ['nama_guru' => 'Dewi Kartika, S.Pd', 'nip' => '198209182006042001', 'email' => 'dewi.kartika@smpn1cipanas.sch.id', 'telepon' => '081234567805', 'mata_pelajaran_kode' => 'BIND', 'is_wali_kelas' => true],
            ['nama_guru' => 'Hendra Wijaya, S.Pd', 'nip' => '197506301999031002', 'email' => 'hendra.wijaya@smpn1cipanas.sch.id', 'telepon' => '081234567806', 'mata_pelajaran_kode' => 'BING', 'is_wali_kelas' => true],
            ['nama_guru' => 'Indah Permata, M.Pd', 'nip' => '198403152008012001', 'email' => 'indah.permata@smpn1cipanas.sch.id', 'telepon' => '081234567807', 'mata_pelajaran_kode' => 'SEJ', 'is_wali_kelas' => true],
            ['nama_guru' => 'Joko Prasetyo, S.Pd', 'nip' => '197711202000121001', 'email' => 'joko.prasetyo@smpn1cipanas.sch.id', 'telepon' => '081234567808', 'mata_pelajaran_kode' => 'GEO', 'is_wali_kelas' => true],
            ['nama_guru' => 'Lestari Wulandari, S.Pd', 'nip' => '198601252010012002', 'email' => 'lestari.wulandari@smpn1cipanas.sch.id', 'telepon' => '081234567809', 'mata_pelajaran_kode' => 'EKO', 'is_wali_kelas' => true],
        ];

        foreach ($guruData as $guruItem) {
            // Find mata pelajaran by kode
            $mataPelajaran = \App\Models\MataPelajaran::where('kode_mapel', $guruItem['mata_pelajaran_kode'])->first();
            
            if ($mataPelajaran) {
                 $guruDataToSave = [
                     'nama_guru' => $guruItem['nama_guru'],
                     'nip' => $guruItem['nip'],
                     'email' => $guruItem['email'],
                     'telepon' => $guruItem['telepon'],
                     'mata_pelajaran_id' => $mataPelajaran->id,
                     'is_wali_kelas' => $guruItem['is_wali_kelas']
                 ];
                
                Guru::firstOrCreate(
                     ['nip' => $guruItem['nip']],
                     $guruDataToSave
                 );
            }
        }

        $this->command->info('Test data seeded successfully!');
    }
}
