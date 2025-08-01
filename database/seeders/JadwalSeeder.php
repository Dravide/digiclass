<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunPelajaran;

class JadwalSeeder extends Seeder
{
    public function run()
    {
        $tahunPelajaran = TahunPelajaran::where('is_active', true)->first();
        $gurus = Guru::take(5)->get();
        $kelas = Kelas::take(5)->get();
        $mataPelajarans = MataPelajaran::take(5)->get();
        
        if (!$tahunPelajaran || $gurus->isEmpty() || $kelas->isEmpty() || $mataPelajarans->isEmpty()) {
            $this->command->info('Data tidak lengkap untuk membuat jadwal');
            return;
        }

        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jamMulai = ['07:00', '08:00', '09:00', '10:00', '11:00'];
        $jamSelesai = ['07:45', '08:45', '09:45', '10:45', '11:45'];

        foreach ($hari as $hariIndex => $namaHari) {
            for ($i = 0; $i < 3; $i++) {
                Jadwal::create([
                    'tahun_pelajaran_id' => $tahunPelajaran->id,
                    'guru_id' => $gurus->random()->id,
                    'kelas_id' => $kelas->random()->id,
                    'mata_pelajaran_id' => $mataPelajarans->random()->id,
                    'hari' => $namaHari,
                    'jam_mulai' => $jamMulai[$i],
                    'jam_selesai' => $jamSelesai[$i],
                    'jam_ke' => $i + 1,
                    'is_active' => true
                ]);
            }
        }

        $this->command->info('Jadwal berhasil di-seed!');
    }
}