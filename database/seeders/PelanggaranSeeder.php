<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriPelanggaran;
use App\Models\JenisPelanggaran;
use App\Models\SanksiPelanggaran;

class PelanggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Kategori Pelanggaran
        $kategoris = [
            ['kode_kategori' => 'I', 'nama_kategori' => 'Keterlambatan Masuk', 'deskripsi' => 'Pelanggaran terkait keterlambatan masuk kelas'],
            ['kode_kategori' => 'II', 'nama_kategori' => 'Kehadiran dan Tatap Muka', 'deskripsi' => 'Pelanggaran terkait kehadiran dan partisipasi di kelas'],
            ['kode_kategori' => 'III', 'nama_kategori' => 'Pakaian dan Penampilan', 'deskripsi' => 'Pelanggaran terkait seragam dan penampilan siswa'],
            ['kode_kategori' => 'IV', 'nama_kategori' => 'Kepribadian dan Etika', 'deskripsi' => 'Pelanggaran terkait sikap dan perilaku siswa'],
            ['kode_kategori' => 'V', 'nama_kategori' => 'Kebersihan dan Kedisiplinan', 'deskripsi' => 'Pelanggaran terkait kebersihan dan kedisiplinan sekolah'],
            ['kode_kategori' => 'VI', 'nama_kategori' => 'Rokok dan Sejenisnya', 'deskripsi' => 'Pelanggaran terkait merokok dan benda terlarang'],
            ['kode_kategori' => 'VII', 'nama_kategori' => 'Media dan Konten Terlarang', 'deskripsi' => 'Pelanggaran terkait media dan konten yang tidak pantas']
        ];

        foreach ($kategoris as $kategori) {
            KategoriPelanggaran::create($kategori);
        }

        // Seed Jenis Pelanggaran
        $jenisPelanggarans = [
            // Kategori I - Keterlambatan Masuk
            ['kategori_kode' => 'I', 'kode_pelanggaran' => '1.1', 'nama_pelanggaran' => 'Terlambat masuk kelas 5-15 menit', 'deskripsi_pelanggaran' => 'Masuk kelas terlambat 5-15 menit tanpa surat keterangan', 'poin_pelanggaran' => 3, 'tingkat_pelanggaran' => 'ringan'],
            ['kategori_kode' => 'I', 'kode_pelanggaran' => '1.2', 'nama_pelanggaran' => 'Terlambat masuk kelas lebih dari 15 menit', 'deskripsi_pelanggaran' => 'Masuk kelas terlambat lebih dari 15 menit tanpa surat keterangan', 'poin_pelanggaran' => 5, 'tingkat_pelanggaran' => 'ringan'],
            ['kategori_kode' => 'I', 'kode_pelanggaran' => '1.3', 'nama_pelanggaran' => 'Tidak kembali setelah izin keluar', 'deskripsi_pelanggaran' => 'Tidak kembali ke kelas setelah izin keluar atau melebihi waktu yang ditentukan', 'poin_pelanggaran' => 5, 'tingkat_pelanggaran' => 'ringan'],

            // Kategori II - Kehadiran dan Tatap Muka
            ['kategori_kode' => 'II', 'kode_pelanggaran' => '2.1', 'nama_pelanggaran' => 'Tidak masuk tanpa keterangan (Alfa)', 'deskripsi_pelanggaran' => 'Tidak masuk sekolah tanpa surat keterangan atau pemberitahuan', 'poin_pelanggaran' => 3, 'tingkat_pelanggaran' => 'ringan'],
            ['kategori_kode' => 'II', 'kode_pelanggaran' => '2.2', 'nama_pelanggaran' => 'Alfa 3 hari berturut-turut', 'deskripsi_pelanggaran' => 'Tidak masuk sekolah 3 hari berturut-turut tanpa keterangan', 'poin_pelanggaran' => 10, 'tingkat_pelanggaran' => 'sedang'],
            ['kategori_kode' => 'II', 'kode_pelanggaran' => '2.3', 'nama_pelanggaran' => 'Tidak mengumpulkan tugas tepat waktu', 'deskripsi_pelanggaran' => 'Tidak mengumpulkan tugas sesuai waktu yang telah disepakati', 'poin_pelanggaran' => 5, 'tingkat_pelanggaran' => 'ringan'],
            ['kategori_kode' => 'II', 'kode_pelanggaran' => '2.4', 'nama_pelanggaran' => 'Meninggalkan kelas tanpa izin (Cabut)', 'deskripsi_pelanggaran' => 'Meninggalkan kelas atau sekolah tanpa izin guru', 'poin_pelanggaran' => 25, 'tingkat_pelanggaran' => 'sedang'],

            // Kategori III - Pakaian dan Penampilan
            ['kategori_kode' => 'III', 'kode_pelanggaran' => '3.1', 'nama_pelanggaran' => 'Seragam tidak rapi', 'deskripsi_pelanggaran' => 'Memakai seragam yang tidak rapi atau tidak sesuai ketentuan', 'poin_pelanggaran' => 5, 'tingkat_pelanggaran' => 'ringan'],
            ['kategori_kode' => 'III', 'kode_pelanggaran' => '3.2', 'nama_pelanggaran' => 'Tidak memakai atribut lengkap', 'deskripsi_pelanggaran' => 'Tidak memakai atribut sekolah yang lengkap', 'poin_pelanggaran' => 3, 'tingkat_pelanggaran' => 'ringan'],
            ['kategori_kode' => 'III', 'kode_pelanggaran' => '3.3', 'nama_pelanggaran' => 'Rambut tidak sesuai ketentuan', 'deskripsi_pelanggaran' => 'Rambut terlalu panjang, dicat, atau tidak sesuai ketentuan sekolah', 'poin_pelanggaran' => 10, 'tingkat_pelanggaran' => 'ringan'],
            ['kategori_kode' => 'III', 'kode_pelanggaran' => '3.4', 'nama_pelanggaran' => 'Memakai perhiasan berlebihan', 'deskripsi_pelanggaran' => 'Siswa putra memakai perhiasan atau siswa putri memakai perhiasan berlebihan', 'poin_pelanggaran' => 10, 'tingkat_pelanggaran' => 'ringan'],

            // Kategori IV - Kepribadian dan Etika
            ['kategori_kode' => 'IV', 'kode_pelanggaran' => '4.1', 'nama_pelanggaran' => 'Berkata tidak sopan', 'deskripsi_pelanggaran' => 'Berkata tidak sopan, tidak santun, atau berkata kasar', 'poin_pelanggaran' => 10, 'tingkat_pelanggaran' => 'ringan'],
            ['kategori_kode' => 'IV', 'kode_pelanggaran' => '4.2', 'nama_pelanggaran' => 'Mengganggu ketertiban belajar', 'deskripsi_pelanggaran' => 'Mengganggu atau menciptakan keributan saat pembelajaran', 'poin_pelanggaran' => 15, 'tingkat_pelanggaran' => 'sedang'],
            ['kategori_kode' => 'IV', 'kode_pelanggaran' => '4.3', 'nama_pelanggaran' => 'Melakukan penghinaan', 'deskripsi_pelanggaran' => 'Melakukan penghinaan dengan lisan atau tulisan terhadap sesama', 'poin_pelanggaran' => 25, 'tingkat_pelanggaran' => 'sedang'],
            ['kategori_kode' => 'IV', 'kode_pelanggaran' => '4.4', 'nama_pelanggaran' => 'Berkelahi dengan sesama siswa', 'deskripsi_pelanggaran' => 'Berkelahi atau terlibat perkelahian dengan sesama siswa', 'poin_pelanggaran' => 50, 'tingkat_pelanggaran' => 'berat'],
            ['kategori_kode' => 'IV', 'kode_pelanggaran' => '4.5', 'nama_pelanggaran' => 'Berbuat asusila ringan', 'deskripsi_pelanggaran' => 'Melakukan perbuatan asusila dalam kategori ringan', 'poin_pelanggaran' => 50, 'tingkat_pelanggaran' => 'berat'],
            ['kategori_kode' => 'IV', 'kode_pelanggaran' => '4.6', 'nama_pelanggaran' => 'Berbuat asusila berat', 'deskripsi_pelanggaran' => 'Melakukan perbuatan asusila yang mencemarkan nama baik sekolah', 'poin_pelanggaran' => 100, 'tingkat_pelanggaran' => 'berat'],

            // Kategori V - Kebersihan dan Kedisiplinan
            ['kategori_kode' => 'V', 'kode_pelanggaran' => '5.1', 'nama_pelanggaran' => 'Membuang sampah sembarangan', 'deskripsi_pelanggaran' => 'Membuang sampah tidak pada tempatnya', 'poin_pelanggaran' => 5, 'tingkat_pelanggaran' => 'ringan'],
            ['kategori_kode' => 'V', 'kode_pelanggaran' => '5.2', 'nama_pelanggaran' => 'Mencoret-coret fasilitas sekolah', 'deskripsi_pelanggaran' => 'Mengotori atau mencoret-coret lingkungan dan fasilitas sekolah', 'poin_pelanggaran' => 25, 'tingkat_pelanggaran' => 'sedang'],
            ['kategori_kode' => 'V', 'kode_pelanggaran' => '5.3', 'nama_pelanggaran' => 'Merusak fasilitas sekolah', 'deskripsi_pelanggaran' => 'Merusak atau menghilangkan barang milik sekolah', 'poin_pelanggaran' => 50, 'tingkat_pelanggaran' => 'berat'],
            ['kategori_kode' => 'V', 'kode_pelanggaran' => '5.4', 'nama_pelanggaran' => 'Tidak mengindahkan protokol kesehatan', 'deskripsi_pelanggaran' => 'Tidak mengindahkan protokol kesehatan yang ditetapkan sekolah', 'poin_pelanggaran' => 15, 'tingkat_pelanggaran' => 'sedang'],

            // Kategori VI - Rokok dan Sejenisnya
            ['kategori_kode' => 'VI', 'kode_pelanggaran' => '6.1', 'nama_pelanggaran' => 'Membawa rokok ke sekolah', 'deskripsi_pelanggaran' => 'Membawa rokok atau alat hisap ke lingkungan sekolah', 'poin_pelanggaran' => 25, 'tingkat_pelanggaran' => 'sedang'],
            ['kategori_kode' => 'VI', 'kode_pelanggaran' => '6.2', 'nama_pelanggaran' => 'Merokok di lingkungan sekolah', 'deskripsi_pelanggaran' => 'Merokok di dalam atau di lingkungan sekolah (radius 1 km)', 'poin_pelanggaran' => 50, 'tingkat_pelanggaran' => 'berat'],
            ['kategori_kode' => 'VI', 'kode_pelanggaran' => '6.3', 'nama_pelanggaran' => 'Membawa benda berbahaya', 'deskripsi_pelanggaran' => 'Membawa senjata tajam atau benda berbahaya lainnya', 'poin_pelanggaran' => 75, 'tingkat_pelanggaran' => 'berat'],

            // Kategori VII - Media dan Konten Terlarang
            ['kategori_kode' => 'VII', 'kode_pelanggaran' => '7.1', 'nama_pelanggaran' => 'Membawa HP saat pembelajaran', 'deskripsi_pelanggaran' => 'Menggunakan handphone saat jam pembelajaran tanpa izin guru', 'poin_pelanggaran' => 15, 'tingkat_pelanggaran' => 'sedang'],
            ['kategori_kode' => 'VII', 'kode_pelanggaran' => '7.2', 'nama_pelanggaran' => 'Membawa konten pornografi', 'deskripsi_pelanggaran' => 'Membawa dan memperlihatkan gambar, video, atau konten pornografi', 'poin_pelanggaran' => 70, 'tingkat_pelanggaran' => 'berat'],
            ['kategori_kode' => 'VII', 'kode_pelanggaran' => '7.3', 'nama_pelanggaran' => 'Menyebarkan konten negatif', 'deskripsi_pelanggaran' => 'Menyebarkan konten negatif atau hoaks melalui media sosial', 'poin_pelanggaran' => 40, 'tingkat_pelanggaran' => 'berat']
        ];

        foreach ($jenisPelanggarans as $jenis) {
            $kategori = KategoriPelanggaran::where('kode_kategori', $jenis['kategori_kode'])->first();
            if ($kategori) {
                JenisPelanggaran::create([
                    'kategori_pelanggaran_id' => $kategori->id,
                    'kode_pelanggaran' => $jenis['kode_pelanggaran'],
                    'nama_pelanggaran' => $jenis['nama_pelanggaran'],
                    'deskripsi_pelanggaran' => $jenis['deskripsi_pelanggaran'],
                    'poin_pelanggaran' => $jenis['poin_pelanggaran'],
                    'tingkat_pelanggaran' => $jenis['tingkat_pelanggaran'],
                    'is_active' => true
                ]);
            }
        }

        // Seed Sanksi Pelanggaran
        $sanksis = [
            // Kelas VII (Batas maksimal: 100 poin)
            ['tingkat_kelas' => 7, 'poin_minimum' => 1, 'poin_maksimum' => 25, 'jenis_sanksi' => 'Peringatan Lisan', 'deskripsi_sanksi' => 'Peringatan lisan dari wali kelas', 'penanggungjawab' => 'Wali Kelas'],
            ['tingkat_kelas' => 7, 'poin_minimum' => 26, 'poin_maksimum' => 50, 'jenis_sanksi' => 'Peringatan Tertulis', 'deskripsi_sanksi' => 'Peringatan tertulis dan panggilan orang tua', 'penanggungjawab' => 'Wali Kelas'],
            ['tingkat_kelas' => 7, 'poin_minimum' => 51, 'poin_maksimum' => 75, 'jenis_sanksi' => 'Pembinaan Khusus', 'deskripsi_sanksi' => 'Pembinaan khusus oleh guru BK dan panggilan orang tua', 'penanggungjawab' => 'Guru BK'],
            ['tingkat_kelas' => 7, 'poin_minimum' => 76, 'poin_maksimum' => 100, 'jenis_sanksi' => 'Skorsing', 'deskripsi_sanksi' => 'Skorsing 1-3 hari dan surat perjanjian', 'penanggungjawab' => 'Kesiswaan'],
            ['tingkat_kelas' => 7, 'poin_minimum' => 101, 'poin_maksimum' => 999999, 'jenis_sanksi' => 'Dikembalikan ke Orang Tua', 'deskripsi_sanksi' => 'Siswa dikembalikan kepada orang tua', 'penanggungjawab' => 'Kepala Sekolah'],

            // Kelas VIII (Batas maksimal: 200 poin)
            ['tingkat_kelas' => 8, 'poin_minimum' => 1, 'poin_maksimum' => 50, 'jenis_sanksi' => 'Peringatan Lisan', 'deskripsi_sanksi' => 'Peringatan lisan dari wali kelas', 'penanggungjawab' => 'Wali Kelas'],
            ['tingkat_kelas' => 8, 'poin_minimum' => 51, 'poin_maksimum' => 100, 'jenis_sanksi' => 'Peringatan Tertulis', 'deskripsi_sanksi' => 'Peringatan tertulis dan panggilan orang tua', 'penanggungjawab' => 'Wali Kelas'],
            ['tingkat_kelas' => 8, 'poin_minimum' => 101, 'poin_maksimum' => 150, 'jenis_sanksi' => 'Pembinaan Khusus', 'deskripsi_sanksi' => 'Pembinaan khusus oleh guru BK dan panggilan orang tua', 'penanggungjawab' => 'Guru BK'],
            ['tingkat_kelas' => 8, 'poin_minimum' => 151, 'poin_maksimum' => 200, 'jenis_sanksi' => 'Skorsing', 'deskripsi_sanksi' => 'Skorsing 3-5 hari dan surat perjanjian', 'penanggungjawab' => 'Kesiswaan'],
            ['tingkat_kelas' => 8, 'poin_minimum' => 201, 'poin_maksimum' => 999999, 'jenis_sanksi' => 'Dikembalikan ke Orang Tua', 'deskripsi_sanksi' => 'Siswa dikembalikan kepada orang tua', 'penanggungjawab' => 'Kepala Sekolah'],

            // Kelas IX (Batas maksimal: 300 poin)
            ['tingkat_kelas' => 9, 'poin_minimum' => 1, 'poin_maksimum' => 75, 'jenis_sanksi' => 'Peringatan Lisan', 'deskripsi_sanksi' => 'Peringatan lisan dari wali kelas', 'penanggungjawab' => 'Wali Kelas'],
            ['tingkat_kelas' => 9, 'poin_minimum' => 76, 'poin_maksimum' => 150, 'jenis_sanksi' => 'Peringatan Tertulis', 'deskripsi_sanksi' => 'Peringatan tertulis dan panggilan orang tua', 'penanggungjawab' => 'Wali Kelas'],
            ['tingkat_kelas' => 9, 'poin_minimum' => 151, 'poin_maksimum' => 225, 'jenis_sanksi' => 'Pembinaan Khusus', 'deskripsi_sanksi' => 'Pembinaan khusus oleh guru BK dan panggilan orang tua', 'penanggungjawab' => 'Guru BK'],
            ['tingkat_kelas' => 9, 'poin_minimum' => 226, 'poin_maksimum' => 300, 'jenis_sanksi' => 'Skorsing', 'deskripsi_sanksi' => 'Skorsing 5-7 hari dan surat perjanjian', 'penanggungjawab' => 'Kesiswaan'],
            ['tingkat_kelas' => 9, 'poin_minimum' => 301, 'poin_maksimum' => 999999, 'jenis_sanksi' => 'Dikembalikan ke Orang Tua', 'deskripsi_sanksi' => 'Siswa dikembalikan kepada orang tua', 'penanggungjawab' => 'Kepala Sekolah']
        ];

        foreach ($sanksis as $sanksi) {
            SanksiPelanggaran::create($sanksi);
        }
    }
}