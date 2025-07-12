<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\KelasSiswa;
use App\Models\TahunPelajaran;
use App\Models\Guru;

class KelasSiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get active tahun pelajaran
        $tahunPelajaran = TahunPelajaran::where('is_active', true)->first();
        if (!$tahunPelajaran) {
            $tahunPelajaran = TahunPelajaran::first();
        }

        // Get first guru for wali kelas
        $guru = Guru::first();
        if (!$guru) {
            // Create a default guru if none exists
            $guru = Guru::create([
                'nama_guru' => 'Guru Default',
                'nip' => '123456789',
                'email' => 'guru@example.com',
                'telepon' => '081234567890'
            ]);
        }

        // Create classes for grades 7, 8, and 9
        $kelasData = [
            ['nama_kelas' => '7A', 'tingkat' => 7, 'jurusan' => 'Umum'],
            ['nama_kelas' => '7B', 'tingkat' => 7, 'jurusan' => 'Umum'],
            ['nama_kelas' => '7C', 'tingkat' => 7, 'jurusan' => 'Umum'],
            ['nama_kelas' => '8A', 'tingkat' => 8, 'jurusan' => 'Umum'],
            ['nama_kelas' => '8B', 'tingkat' => 8, 'jurusan' => 'Umum'],
            ['nama_kelas' => '8C', 'tingkat' => 8, 'jurusan' => 'Umum'],
            ['nama_kelas' => '9A', 'tingkat' => 9, 'jurusan' => 'Umum'],
            ['nama_kelas' => '9B', 'tingkat' => 9, 'jurusan' => 'Umum'],
            ['nama_kelas' => '9C', 'tingkat' => 9, 'jurusan' => 'Umum'],
        ];

        $createdKelas = [];
        foreach ($kelasData as $data) {
            $kelas = Kelas::create([
                'tahun_pelajaran_id' => $tahunPelajaran->id,
                'nama_kelas' => $data['nama_kelas'],
                'tingkat' => $data['tingkat'],
                'jurusan' => $data['jurusan'],
                'kapasitas' => 30,
                'guru_id' => $guru->id,
                'link_wa' => 'https://chat.whatsapp.com/' . strtoupper(substr(md5($data['nama_kelas']), 0, 22))
            ]);
            $createdKelas[] = $kelas;
        }

        // Create sample students for each class
        $siswaNames = [
            'Ahmad Fauzi', 'Siti Aminah', 'Budi Hartono', 'Dewi Lestari', 'Andi Pratama',
            'Rina Sari', 'Doni Kurniawan', 'Maya Putri', 'Rudi Setiawan', 'Lina Wati',
            'Fajar Ramadhan', 'Indira Sari', 'Bayu Wijaya', 'Citra Dewi', 'Eko Prasetyo',
            'Fitri Handayani', 'Gilang Ramadhan', 'Hana Maharani', 'Irfan Maulana', 'Jihan Putri'
        ];

        $siswaCounter = 1;
        foreach ($createdKelas as $kelas) {
            // Create 5-8 students per class
            $jumlahSiswa = rand(5, 8);
            
            for ($i = 0; $i < $jumlahSiswa; $i++) {
                $namaIndex = ($siswaCounter - 1) % count($siswaNames);
                $nama = $siswaNames[$namaIndex] . ' ' . $siswaCounter;
                
                $siswa = Siswa::create([
                    'nama_siswa' => $nama,
                    'jk' => $siswaCounter % 2 == 0 ? 'P' : 'L',
                    'nisn' => '2024' . str_pad($siswaCounter, 6, '0', STR_PAD_LEFT),
                    'nis' => str_pad($siswaCounter, 4, '0', STR_PAD_LEFT),
                    'tahun_pelajaran_id' => $tahunPelajaran->id
                ]);

                // Create kelas_siswa record
                KelasSiswa::create([
                    'tahun_pelajaran_id' => $tahunPelajaran->id,
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $kelas->id
                ]);

                $siswaCounter++;
            }
        }

        $this->command->info('Created ' . count($createdKelas) . ' classes for grades 7, 8, and 9');
        $this->command->info('Created ' . ($siswaCounter - 1) . ' students and kelas_siswa records');
    }
}
