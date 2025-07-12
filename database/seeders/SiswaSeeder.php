<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Perpustakaan;
use App\Models\TahunPelajaran;
use App\Models\KelasSiswa;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get active academic year or first one if none is active
        $tahunPelajaran = TahunPelajaran::where('is_active', true)->first() 
                         ?? TahunPelajaran::first();
        
        $kelas = Kelas::all();
        $gurus = Guru::all();
        
        $siswaData = [
            // Kelas 10 IPA 1
            ['nama' => 'Ahmad Rizki Pratama', 'jk' => 'L', 'nisn' => '0051234567', 'nis' => '2024001', 'status' => 'aktif'],
            ['nama' => 'Siti Nurhaliza', 'jk' => 'P', 'nisn' => '0051234568', 'nis' => '2024002', 'status' => 'aktif'],
            ['nama' => 'Budi Santoso', 'jk' => 'L', 'nisn' => '0051234569', 'nis' => '2024003', 'status' => 'tidak_aktif'],
            ['nama' => 'Dewi Sartika', 'jk' => 'P', 'nisn' => '0051234570', 'nis' => '2024004', 'status' => 'aktif'],
            ['nama' => 'Andi Wijaya', 'jk' => 'L', 'nisn' => '0051234571', 'nis' => '2024005', 'status' => 'tidak_aktif'],
            
            // Kelas 10 IPA 2
            ['nama' => 'Rina Maharani', 'jk' => 'P', 'nisn' => '0051234572', 'nis' => '2024006', 'status' => 'aktif'],
            ['nama' => 'Doni Setiawan', 'jk' => 'L', 'nisn' => '0051234573', 'nis' => '2024007', 'status' => 'aktif'],
            ['nama' => 'Maya Sari', 'jk' => 'P', 'nisn' => '0051234574', 'nis' => '2024008', 'status' => 'tidak_aktif'],
            ['nama' => 'Rudi Hermawan', 'jk' => 'L', 'nisn' => '0051234575', 'nis' => '2024009', 'status' => 'aktif'],
            ['nama' => 'Lina Marlina', 'jk' => 'P', 'nisn' => '0051234576', 'nis' => '2024010', 'status' => 'aktif'],
            
            // Kelas 10 IPS 1
            ['nama' => 'Fajar Nugroho', 'jk' => 'L', 'nisn' => '0051234577', 'nis' => '2024011', 'status' => 'tidak_aktif'],
            ['nama' => 'Indira Putri', 'jk' => 'P', 'nisn' => '0051234578', 'nis' => '2024012', 'status' => 'aktif'],
            ['nama' => 'Hendra Gunawan', 'jk' => 'L', 'nisn' => '0051234579', 'nis' => '2024013', 'status' => 'aktif'],
            ['nama' => 'Sari Dewi', 'jk' => 'P', 'nisn' => '0051234580', 'nis' => '2024014', 'status' => 'tidak_aktif'],
            ['nama' => 'Agus Salim', 'jk' => 'L', 'nisn' => '0051234581', 'nis' => '2024015', 'status' => 'aktif'],
            
            // Kelas 11 IPA 1
            ['nama' => 'Putri Ayu', 'jk' => 'P', 'nisn' => '0051234582', 'nis' => '2023001', 'status' => 'aktif'],
            ['nama' => 'Bayu Aji', 'jk' => 'L', 'nisn' => '0051234583', 'nis' => '2023002', 'status' => 'aktif'],
            ['nama' => 'Citra Kirana', 'jk' => 'P', 'nisn' => '0051234584', 'nis' => '2023003', 'status' => 'tidak_aktif'],
            ['nama' => 'Dimas Pratama', 'jk' => 'L', 'nisn' => '0051234585', 'nis' => '2023004', 'status' => 'aktif'],
            ['nama' => 'Eka Sari', 'jk' => 'P', 'nisn' => '0051234586', 'nis' => '2023005', 'status' => 'aktif'],
            
            // Kelas 12 IPA 1
            ['nama' => 'Fandi Rahman', 'jk' => 'L', 'nisn' => '0051234587', 'nis' => '2022001', 'status' => 'tidak_aktif'],
            ['nama' => 'Gita Savitri', 'jk' => 'P', 'nisn' => '0051234588', 'nis' => '2022002', 'status' => 'aktif'],
            ['nama' => 'Hadi Susanto', 'jk' => 'L', 'nisn' => '0051234589', 'nis' => '2022003', 'status' => 'aktif'],
            ['nama' => 'Ira Wati', 'jk' => 'P', 'nisn' => '0051234590', 'nis' => '2022004', 'status' => 'aktif'],
            ['nama' => 'Joko Widodo', 'jk' => 'L', 'nisn' => '0051234591', 'nis' => '2022005', 'status' => 'tidak_aktif'],
        ];
        
        foreach ($siswaData as $index => $data) {
            $kelasIndex = intval($index / 5) % $kelas->count();
            
            // Check if student already exists
            $existingSiswa = Siswa::where('nis', $data['nis'])->first();
            
            if (!$existingSiswa) {
                $siswa = Siswa::create([
                    'nama_siswa' => $data['nama'],
                    'jk' => $data['jk'],
                    'nisn' => $data['nisn'],
                    'nis' => $data['nis'],
                    'tahun_pelajaran_id' => $tahunPelajaran->id
                ]);
                
                // Create KelasSiswa record
                KelasSiswa::create([
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $kelas[$kelasIndex]->id,
                    'tahun_pelajaran_id' => $tahunPelajaran->id
                ]);
                
                // Buat data perpustakaan berdasarkan status
                $terpenuhi = $data['status'] === 'aktif';
                Perpustakaan::create([
                    'siswa_id' => $siswa->id,
                    'terpenuhi' => $terpenuhi,
                    'keterangan' => $terpenuhi ? 'Persyaratan perpustakaan sudah terpenuhi' : 'Persyaratan perpustakaan belum terpenuhi',
                    'tanggal_pemenuhan' => $terpenuhi ? now() : null
                ]);
            }
        }
        
        $this->command->info('Student data seeded successfully!');
    }
}
