<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JurnalMengajar;
use App\Models\Jadwal;
use App\Models\Guru;
use Carbon\Carbon;

class JurnalMengajarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah sudah ada data guru dan jadwal
        $jadwalList = Jadwal::with(['guru', 'mataPelajaran', 'kelas'])->take(10)->get();
        $guruList = Guru::take(5)->get();

        if ($jadwalList->isEmpty() || $guruList->isEmpty()) {
            $this->command->info('Tidak ada data jadwal atau guru. Membuat data dummy sederhana...');
            
            // Buat data dummy guru jika belum ada
             if ($guruList->isEmpty()) {
                 // Pastikan ada mata pelajaran
                 $mataPelajaran = \App\Models\MataPelajaran::first();
                 if (!$mataPelajaran) {
                     $mataPelajaran = \App\Models\MataPelajaran::create([
                         'kode_mapel' => 'MTK',
                         'nama_mapel' => 'Matematika',
                         'deskripsi' => 'Mata pelajaran matematika',
                         'jam_pelajaran' => 4,
                         'kategori' => 'wajib'
                     ]);
                 }
                 
                 $guru1 = Guru::create([
                     'nama_guru' => 'Budi Santoso, S.Pd',
                     'nip' => '198501012010011001',
                     'email' => 'budi.santoso@sekolah.com',
                     'telepon' => '081234567890',
                     'mata_pelajaran_id' => $mataPelajaran->id
                 ]);
                 
                 $guru2 = Guru::create([
                     'nama_guru' => 'Siti Nurhaliza, S.Pd',
                     'nip' => '198502022010012002',
                     'email' => 'siti.nurhaliza@sekolah.com',
                     'telepon' => '081234567891',
                     'mata_pelajaran_id' => $mataPelajaran->id
                 ]);
                 
                 $guruList = collect([$guru1, $guru2]);
             }
            
            // Buat data dummy jadwal jika belum ada
             if ($jadwalList->isEmpty()) {
                 // Pastikan ada data kelas dan mata pelajaran
                 $kelas = \App\Models\Kelas::first();
                 $mataPelajaran = \App\Models\MataPelajaran::first();
                 $tahunPelajaran = \App\Models\TahunPelajaran::first();
                 
                 // Buat data dummy jika belum ada
                 if (!$kelas) {
                     $kelas = \App\Models\Kelas::create([
                         'nama_kelas' => 'X IPA 1',
                         'tingkat' => 10,
                         'kapasitas' => 30
                     ]);
                 }
                 
                 if (!$tahunPelajaran) {
                     $tahunPelajaran = \App\Models\TahunPelajaran::create([
                         'tahun_mulai' => 2024,
                         'tahun_selesai' => 2025,
                         'semester' => 1,
                         'is_active' => true
                     ]);
                 }
                
                $jadwal1 = Jadwal::create([
                      'guru_id' => $guruList->first()->id,
                      'mata_pelajaran_id' => $mataPelajaran->id,
                      'kelas_id' => $kelas->id,
                      'hari' => 'senin',
                      'jam_mulai' => '07:00:00',
                      'jam_selesai' => '08:30:00',
                      'jam_ke' => 1,
                      'tahun_pelajaran_id' => $tahunPelajaran->id,
                      'is_active' => true
                  ]);
                  
                  $jadwal2 = Jadwal::create([
                      'guru_id' => $guruList->last()->id,
                      'mata_pelajaran_id' => $mataPelajaran->id,
                      'kelas_id' => $kelas->id,
                      'hari' => 'selasa',
                      'jam_mulai' => '08:30:00',
                      'jam_selesai' => '10:00:00',
                      'jam_ke' => 2,
                      'tahun_pelajaran_id' => $tahunPelajaran->id,
                      'is_active' => true
                  ]);
                
                $jadwalList = collect([$jadwal1, $jadwal2]);
            }
        }

        $materiSamples = [
            'Pengenalan Konsep Dasar',
            'Latihan Soal dan Pembahasan',
            'Praktikum Laboratorium',
            'Diskusi Kelompok',
            'Presentasi Siswa',
            'Ulangan Harian',
            'Review Materi Sebelumnya',
            'Penjelasan Teori Baru',
            'Studi Kasus',
            'Evaluasi Pembelajaran'
        ];

        $kegiatanSamples = [
            'Pembukaan dengan doa dan presensi, penyampaian materi melalui ceramah interaktif, tanya jawab dengan siswa, dan penutup dengan rangkuman.',
            'Siswa dibagi dalam kelompok kecil untuk diskusi, presentasi hasil diskusi, dan evaluasi bersama.',
            'Praktikum di laboratorium dengan panduan modul, observasi langsung, dan pencatatan hasil.',
            'Latihan mengerjakan soal-soal, pembahasan bersama, dan penjelasan konsep yang sulit dipahami.',
            'Review materi minggu lalu, penjelasan materi baru dengan contoh konkret, dan pemberian tugas rumah.'
        ];

        $metodeSamples = [
            'Ceramah Interaktif',
            'Diskusi Kelompok',
            'Praktikum',
            'Tanya Jawab',
            'Presentasi',
            'Problem Based Learning',
            'Cooperative Learning',
            'Demonstrasi'
        ];

        $kendalaSamples = [
            'Beberapa siswa terlambat masuk kelas',
            'Proyektor tidak berfungsi dengan baik',
            'Siswa kurang aktif dalam diskusi',
            'Waktu pembelajaran terbatas',
            'Materi terlalu padat untuk satu pertemuan',
            null, // Tidak ada kendala
            'Cuaca hujan mengganggu konsentrasi',
            'Beberapa siswa tidak membawa buku'
        ];

        $solusiSamples = [
            'Memberikan teguran halus dan mengingatkan pentingnya kedisiplinan',
            'Menggunakan papan tulis sebagai alternatif media pembelajaran',
            'Memberikan pertanyaan pancingan untuk meningkatkan partisipasi',
            'Memprioritaskan materi inti dan memberikan tugas untuk materi tambahan',
            'Membagi materi menjadi dua pertemuan',
            null, // Tidak ada solusi karena tidak ada kendala
            'Menutup jendela dan menyalakan lampu untuk kenyamanan belajar',
            'Meminjamkan buku dari perpustakaan dan mengingatkan untuk pertemuan selanjutnya'
        ];

        // Generate data untuk 3 bulan terakhir
        $startDate = Carbon::now()->subMonths(3);
        $endDate = Carbon::now();

        foreach ($jadwalList as $jadwal) {
            // Generate 8-12 jurnal per jadwal (sekitar 2-3 per bulan)
            $jurnalCount = rand(8, 12);
            
            for ($i = 0; $i < $jurnalCount; $i++) {
                // Random tanggal dalam range
                $tanggal = Carbon::createFromTimestamp(
                    rand($startDate->timestamp, $endDate->timestamp)
                )->format('Y-m-d');

                // Pastikan tanggal sesuai dengan hari jadwal
                $tanggalCarbon = Carbon::parse($tanggal);
                $hariIndonesia = [
                    'Sunday' => 'Minggu',
                    'Monday' => 'Senin', 
                    'Tuesday' => 'Selasa',
                    'Wednesday' => 'Rabu',
                    'Thursday' => 'Kamis',
                    'Friday' => 'Jumat',
                    'Saturday' => 'Sabtu'
                ];
                
                $hariTanggal = $hariIndonesia[$tanggalCarbon->format('l')];
                
                // Skip jika hari tidak sesuai dengan jadwal
                if ($hariTanggal !== $jadwal->hari) {
                    continue;
                }

                $materiIndex = array_rand($materiSamples);
                $kegiatanIndex = array_rand($kegiatanSamples);
                $metodeIndex = array_rand($metodeSamples);
                $kendalaIndex = array_rand($kendalaSamples);
                $solusiIndex = array_rand($solusiSamples);

                // Random jumlah siswa (20-35 siswa per kelas)
                $totalSiswa = rand(20, 35);
                $siswaHadir = rand(15, $totalSiswa);
                $siswaTidakHadir = $totalSiswa - $siswaHadir;

                // Random status dengan distribusi realistis
                $statusRand = rand(1, 100);
                if ($statusRand <= 60) {
                    $status = 'approved';
                } elseif ($statusRand <= 85) {
                    $status = 'submitted';
                } else {
                    $status = 'draft';
                }

                $submittedAt = null;
                $approvedAt = null;
                $approvedBy = null;

                if ($status === 'submitted' || $status === 'approved') {
                    $submittedAt = Carbon::parse($tanggal)->addHours(rand(1, 6));
                }

                if ($status === 'approved') {
                    $approvedAt = Carbon::parse($tanggal)->addHours(rand(6, 24));
                    $approvedBy = 1; // Assuming user ID 1 exists
                }

                JurnalMengajar::create([
                    'jadwal_id' => $jadwal->id,
                    'guru_id' => $jadwal->guru_id,
                    'tanggal' => $tanggal,
                    'jam_mulai' => $jadwal->jam_mulai,
                    'jam_selesai' => $jadwal->jam_selesai,
                    'materi_ajar' => $materiSamples[$materiIndex],
                    'kegiatan_pembelajaran' => $kegiatanSamples[$kegiatanIndex],
                    'metode_pembelajaran' => $metodeSamples[$metodeIndex],
                    'jumlah_siswa_hadir' => $siswaHadir,
                    'jumlah_siswa_tidak_hadir' => $siswaTidakHadir,
                    'kendala' => $kendalaSamples[$kendalaIndex],
                    'solusi' => $solusiSamples[$solusiIndex],
                    'catatan' => rand(1, 3) === 1 ? 'Pembelajaran berjalan dengan baik dan siswa antusias mengikuti pelajaran.' : null,
                    'status' => $status,
                    'submitted_at' => $submittedAt,
                    'approved_at' => $approvedAt,
                    'approved_by' => $approvedBy,
                    'created_at' => Carbon::parse($tanggal),
                    'updated_at' => Carbon::parse($tanggal)
                ]);
            }
        }

        $this->command->info('Jurnal mengajar berhasil di-seed!');
    }
}