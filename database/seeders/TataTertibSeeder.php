<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriPelanggaran;
use App\Models\JenisPelanggaran;

class TataTertibSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Kategori Tata Tertib (Positive Rules)
        $kategoriTataTertib = [
            [
                'kode_kategori' => 'TT-I', 
                'nama_kategori' => 'Ketaqwaan dan Akhlak Mulia', 
                'deskripsi' => 'Aturan terkait ketaqwaan kepada Tuhan Yang Maha Esa dan pembentukan akhlak mulia'
            ],
            [
                'kode_kategori' => 'TT-II', 
                'nama_kategori' => 'Kedisiplinan dan Kehadiran', 
                'deskripsi' => 'Aturan terkait kedisiplinan waktu, kehadiran, dan ketepatan dalam kegiatan sekolah'
            ],
            [
                'kode_kategori' => 'TT-III', 
                'nama_kategori' => 'Seragam dan Penampilan', 
                'deskripsi' => 'Aturan terkait seragam sekolah, penampilan, dan kerapian siswa'
            ],
            [
                'kode_kategori' => 'TT-IV', 
                'nama_kategori' => 'Sopan Santun dan Etika', 
                'deskripsi' => 'Aturan terkait sopan santun, etika pergaulan, dan tata krama di sekolah'
            ],
            [
                'kode_kategori' => 'TT-V', 
                'nama_kategori' => 'Kebersihan dan Lingkungan', 
                'deskripsi' => 'Aturan terkait kebersihan diri, kelas, dan lingkungan sekolah'
            ],
            [
                'kode_kategori' => 'TT-VI', 
                'nama_kategori' => 'Keamanan dan Keselamatan', 
                'deskripsi' => 'Aturan terkait keamanan, keselamatan, dan pencegahan bahaya di sekolah'
            ],
            [
                'kode_kategori' => 'TT-VII', 
                'nama_kategori' => 'Teknologi dan Media', 
                'deskripsi' => 'Aturan terkait penggunaan teknologi, media sosial, dan perangkat elektronik'
            ],
            [
                'kode_kategori' => 'TT-VIII', 
                'nama_kategori' => 'Prestasi dan Pembelajaran', 
                'deskripsi' => 'Aturan terkait semangat belajar, prestasi akademik, dan kegiatan pembelajaran'
            ]
        ];

        foreach ($kategoriTataTertib as $kategori) {
            KategoriPelanggaran::updateOrCreate(
                ['kode_kategori' => $kategori['kode_kategori']],
                $kategori
            );
        }

        // Seed Jenis Tata Tertib (Positive Rules)
        $jenisTataTertib = [
            // TT-I: Ketaqwaan dan Akhlak Mulia
            [
                'kategori_kode' => 'TT-I',
                'kode_pelanggaran' => 'TT-1.1',
                'nama_pelanggaran' => 'Beriman dan Bertaqwa kepada Tuhan Yang Maha Esa',
                'deskripsi_pelanggaran' => 'Setiap siswa wajib beriman dan bertaqwa kepada Tuhan Yang Maha Esa sesuai dengan agama dan kepercayaan masing-masing',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-I',
                'kode_pelanggaran' => 'TT-1.2',
                'nama_pelanggaran' => 'Melaksanakan Ibadah Sesuai Agama',
                'deskripsi_pelanggaran' => 'Siswa wajib melaksanakan ibadah sesuai dengan agama dan kepercayaan masing-masing',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-I',
                'kode_pelanggaran' => 'TT-1.3',
                'nama_pelanggaran' => 'Menjunjung Tinggi Tata Susila',
                'deskripsi_pelanggaran' => 'Siswa wajib menjunjung tinggi tata susila dan norma kesusilaan dimanapun berada',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],

            // TT-II: Kedisiplinan dan Kehadiran
            [
                'kategori_kode' => 'TT-II',
                'kode_pelanggaran' => 'TT-2.1',
                'nama_pelanggaran' => 'Hadir Tepat Waktu',
                'deskripsi_pelanggaran' => 'Siswa wajib hadir di sekolah tepat waktu sesuai jadwal yang telah ditetapkan (pukul 07.00 WIB)',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-II',
                'kode_pelanggaran' => 'TT-2.2',
                'nama_pelanggaran' => 'Mengikuti Upacara Bendera',
                'deskripsi_pelanggaran' => 'Siswa wajib mengikuti upacara bendera setiap hari Senin dan hari-hari nasional dengan khidmat',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-II',
                'kode_pelanggaran' => 'TT-2.3',
                'nama_pelanggaran' => 'Mengikuti Pembelajaran dengan Aktif',
                'deskripsi_pelanggaran' => 'Siswa wajib mengikuti seluruh kegiatan pembelajaran dengan aktif dan penuh perhatian',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-II',
                'kode_pelanggaran' => 'TT-2.4',
                'nama_pelanggaran' => 'Izin Keluar dengan Prosedur',
                'deskripsi_pelanggaran' => 'Siswa yang perlu keluar kelas atau sekolah wajib meminta izin kepada guru dan mengikuti prosedur yang berlaku',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],

            // TT-III: Seragam dan Penampilan
            [
                'kategori_kode' => 'TT-III',
                'kode_pelanggaran' => 'TT-3.1',
                'nama_pelanggaran' => 'Memakai Seragam Sesuai Ketentuan',
                'deskripsi_pelanggaran' => 'Siswa wajib memakai seragam sekolah sesuai dengan ketentuan dan jadwal yang telah ditetapkan',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-III',
                'kode_pelanggaran' => 'TT-3.2',
                'nama_pelanggaran' => 'Memakai Atribut Lengkap',
                'deskripsi_pelanggaran' => 'Siswa wajib memakai atribut sekolah lengkap (badge, topi, dasi, ikat pinggang, kaos kaki, sepatu)',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-III',
                'kode_pelanggaran' => 'TT-3.3',
                'nama_pelanggaran' => 'Menjaga Kerapian Rambut',
                'deskripsi_pelanggaran' => 'Siswa wajib menjaga kerapian rambut sesuai ketentuan sekolah (rapi, tidak dicat, panjang sesuai aturan)',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-III',
                'kode_pelanggaran' => 'TT-3.4',
                'nama_pelanggaran' => 'Berpenampilan Sederhana',
                'deskripsi_pelanggaran' => 'Siswa wajib berpenampilan sederhana, tidak berlebihan dalam berhias atau memakai perhiasan',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],

            // TT-IV: Sopan Santun dan Etika
            [
                'kategori_kode' => 'TT-IV',
                'kode_pelanggaran' => 'TT-4.1',
                'nama_pelanggaran' => 'Menghormati Guru dan Karyawan',
                'deskripsi_pelanggaran' => 'Siswa wajib menghormati dan mentaati guru, karyawan, dan seluruh warga sekolah',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-IV',
                'kode_pelanggaran' => 'TT-4.2',
                'nama_pelanggaran' => 'Berbicara dengan Sopan',
                'deskripsi_pelanggaran' => 'Siswa wajib berbicara dengan sopan, santun, dan menggunakan bahasa yang baik dan benar',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-IV',
                'kode_pelanggaran' => 'TT-4.3',
                'nama_pelanggaran' => 'Menjaga Persatuan dan Kekeluargaan',
                'deskripsi_pelanggaran' => 'Siswa wajib menjaga rasa persatuan dan kekeluargaan dengan sesama siswa dan warga sekolah',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-IV',
                'kode_pelanggaran' => 'TT-4.4',
                'nama_pelanggaran' => 'Menyelesaikan Konflik dengan Damai',
                'deskripsi_pelanggaran' => 'Siswa wajib menyelesaikan setiap konflik atau perselisihan dengan cara yang damai dan bijaksana',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],

            // TT-V: Kebersihan dan Lingkungan
            [
                'kategori_kode' => 'TT-V',
                'kode_pelanggaran' => 'TT-5.1',
                'nama_pelanggaran' => 'Menjaga Kebersihan Diri',
                'deskripsi_pelanggaran' => 'Siswa wajib menjaga kebersihan diri, termasuk kebersihan badan, gigi, dan kuku',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-V',
                'kode_pelanggaran' => 'TT-5.2',
                'nama_pelanggaran' => 'Membuang Sampah pada Tempatnya',
                'deskripsi_pelanggaran' => 'Siswa wajib membuang sampah pada tempat yang telah disediakan dan menjaga kebersihan lingkungan',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-V',
                'kode_pelanggaran' => 'TT-5.3',
                'nama_pelanggaran' => 'Menjaga Fasilitas Sekolah',
                'deskripsi_pelanggaran' => 'Siswa wajib menjaga, merawat, dan menggunakan fasilitas sekolah dengan baik dan bertanggung jawab',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-V',
                'kode_pelanggaran' => 'TT-5.4',
                'nama_pelanggaran' => 'Mengikuti Protokol Kesehatan',
                'deskripsi_pelanggaran' => 'Siswa wajib mengikuti protokol kesehatan yang ditetapkan sekolah untuk menjaga kesehatan bersama',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],

            // TT-VI: Keamanan dan Keselamatan
            [
                'kategori_kode' => 'TT-VI',
                'kode_pelanggaran' => 'TT-6.1',
                'nama_pelanggaran' => 'Menjaga Keamanan Sekolah',
                'deskripsi_pelanggaran' => 'Siswa wajib menjaga keamanan sekolah dan melaporkan hal-hal yang mencurigakan kepada guru',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-VI',
                'kode_pelanggaran' => 'TT-6.2',
                'nama_pelanggaran' => 'Tidak Membawa Benda Berbahaya',
                'deskripsi_pelanggaran' => 'Siswa dilarang membawa senjata tajam, benda berbahaya, atau barang terlarang ke sekolah',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-VI',
                'kode_pelanggaran' => 'TT-6.3',
                'nama_pelanggaran' => 'Menjauhi Rokok dan Narkoba',
                'deskripsi_pelanggaran' => 'Siswa wajib menjauhi rokok, minuman keras, narkoba, dan zat adiktif lainnya',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],

            // TT-VII: Teknologi dan Media
            [
                'kategori_kode' => 'TT-VII',
                'kode_pelanggaran' => 'TT-7.1',
                'nama_pelanggaran' => 'Menggunakan Teknologi dengan Bijak',
                'deskripsi_pelanggaran' => 'Siswa wajib menggunakan teknologi dan media sosial dengan bijak dan bertanggung jawab',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-VII',
                'kode_pelanggaran' => 'TT-7.2',
                'nama_pelanggaran' => 'Menggunakan HP Sesuai Aturan',
                'deskripsi_pelanggaran' => 'Siswa hanya boleh menggunakan handphone di luar jam pembelajaran atau atas izin guru',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-VII',
                'kode_pelanggaran' => 'TT-7.3',
                'nama_pelanggaran' => 'Menjauhi Konten Negatif',
                'deskripsi_pelanggaran' => 'Siswa wajib menjauhi dan tidak menyebarkan konten negatif, pornografi, atau hoaks',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],

            // TT-VIII: Prestasi dan Pembelajaran
            [
                'kategori_kode' => 'TT-VIII',
                'kode_pelanggaran' => 'TT-8.1',
                'nama_pelanggaran' => 'Bersemangat dalam Belajar',
                'deskripsi_pelanggaran' => 'Siswa wajib menunjukkan semangat belajar yang tinggi dan berusaha meraih prestasi terbaik',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-VIII',
                'kode_pelanggaran' => 'TT-8.2',
                'nama_pelanggaran' => 'Mengerjakan Tugas dengan Jujur',
                'deskripsi_pelanggaran' => 'Siswa wajib mengerjakan tugas dan ujian dengan jujur, tidak menyontek atau melakukan kecurangan',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-VIII',
                'kode_pelanggaran' => 'TT-8.3',
                'nama_pelanggaran' => 'Mengumpulkan Tugas Tepat Waktu',
                'deskripsi_pelanggaran' => 'Siswa wajib mengumpulkan tugas sesuai dengan waktu yang telah ditentukan oleh guru',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ],
            [
                'kategori_kode' => 'TT-VIII',
                'kode_pelanggaran' => 'TT-8.4',
                'nama_pelanggaran' => 'Mengikuti Kegiatan Ekstrakurikuler',
                'deskripsi_pelanggaran' => 'Siswa dianjurkan mengikuti kegiatan ekstrakurikuler untuk mengembangkan bakat dan minat',
                'poin_pelanggaran' => 0,
                'tingkat_pelanggaran' => 'ringan'
            ]
        ];

        foreach ($jenisTataTertib as $jenis) {
            $kategori = KategoriPelanggaran::where('kode_kategori', $jenis['kategori_kode'])->first();
            if ($kategori) {
                JenisPelanggaran::updateOrCreate(
                    ['kode_pelanggaran' => $jenis['kode_pelanggaran']],
                    [
                        'kategori_pelanggaran_id' => $kategori->id,
                        'kode_pelanggaran' => $jenis['kode_pelanggaran'],
                        'nama_pelanggaran' => $jenis['nama_pelanggaran'],
                        'deskripsi_pelanggaran' => $jenis['deskripsi_pelanggaran'],
                        'poin_pelanggaran' => $jenis['poin_pelanggaran'],
                        'tingkat_pelanggaran' => $jenis['tingkat_pelanggaran'],
                        'is_active' => true
                    ]
                );
            }
        }
    }
}