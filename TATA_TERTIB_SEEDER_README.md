# Tata Tertib Seeder Documentation

## Overview
Tata Tertib Seeder (`TataTertibSeeder.php`) adalah seeder yang dibuat untuk mengisi database dengan data tata tertib siswa yang positif dan komprehensif. Seeder ini menggunakan struktur database yang sama dengan sistem pelanggaran yang sudah ada, namun dengan konten yang berfokus pada aturan-aturan positif yang harus diikuti siswa.

## Struktur Data

### Kategori Tata Tertib (8 Kategori)
1. **TT-I: Ketaqwaan dan Akhlak Mulia**
   - Aturan terkait ketaqwaan kepada Tuhan Yang Maha Esa dan pembentukan akhlak mulia

2. **TT-II: Kedisiplinan dan Kehadiran**
   - Aturan terkait kedisiplinan waktu, kehadiran, dan ketepatan dalam kegiatan sekolah

3. **TT-III: Seragam dan Penampilan**
   - Aturan terkait seragam sekolah, penampilan, dan kerapian siswa

4. **TT-IV: Sopan Santun dan Etika**
   - Aturan terkait sopan santun, etika pergaulan, dan tata krama di sekolah

5. **TT-V: Kebersihan dan Lingkungan**
   - Aturan terkait kebersihan diri, kelas, dan lingkungan sekolah

6. **TT-VI: Keamanan dan Keselamatan**
   - Aturan terkait keamanan, keselamatan, dan pencegahan bahaya di sekolah

7. **TT-VII: Teknologi dan Media**
   - Aturan terkait penggunaan teknologi, media sosial, dan perangkat elektronik

8. **TT-VIII: Prestasi dan Pembelajaran**
   - Aturan terkait semangat belajar, prestasi akademik, dan kegiatan pembelajaran

### Detail Aturan per Kategori

#### TT-I: Ketaqwaan dan Akhlak Mulia
- TT-1.1: Beriman dan Bertaqwa kepada Tuhan Yang Maha Esa
- TT-1.2: Melaksanakan Ibadah Sesuai Agama
- TT-1.3: Menjunjung Tinggi Tata Susila

#### TT-II: Kedisiplinan dan Kehadiran
- TT-2.1: Hadir Tepat Waktu
- TT-2.2: Mengikuti Upacara Bendera
- TT-2.3: Mengikuti Pembelajaran dengan Aktif
- TT-2.4: Izin Keluar dengan Prosedur

#### TT-III: Seragam dan Penampilan
- TT-3.1: Memakai Seragam Sesuai Ketentuan
- TT-3.2: Memakai Atribut Lengkap
- TT-3.3: Menjaga Kerapian Rambut
- TT-3.4: Berpenampilan Sederhana

#### TT-IV: Sopan Santun dan Etika
- TT-4.1: Menghormati Guru dan Karyawan
- TT-4.2: Berbicara dengan Sopan
- TT-4.3: Menjaga Persatuan dan Kekeluargaan
- TT-4.4: Menyelesaikan Konflik dengan Damai

#### TT-V: Kebersihan dan Lingkungan
- TT-5.1: Menjaga Kebersihan Diri
- TT-5.2: Membuang Sampah pada Tempatnya
- TT-5.3: Menjaga Fasilitas Sekolah
- TT-5.4: Mengikuti Protokol Kesehatan

#### TT-VI: Keamanan dan Keselamatan
- TT-6.1: Menjaga Keamanan Sekolah
- TT-6.2: Tidak Membawa Benda Berbahaya
- TT-6.3: Menjauhi Rokok dan Narkoba

#### TT-VII: Teknologi dan Media
- TT-7.1: Menggunakan Teknologi dengan Bijak
- TT-7.2: Menggunakan HP Sesuai Aturan
- TT-7.3: Menjauhi Konten Negatif

#### TT-VIII: Prestasi dan Pembelajaran
- TT-8.1: Bersemangat dalam Belajar
- TT-8.2: Mengerjakan Tugas dengan Jujur
- TT-8.3: Mengumpulkan Tugas Tepat Waktu
- TT-8.4: Mengikuti Kegiatan Ekstrakurikuler

## Karakteristik Data

### Poin Pelanggaran
- Semua aturan tata tertib memiliki `poin_pelanggaran = 0`
- Ini menandakan bahwa data ini adalah aturan positif, bukan pelanggaran

### Tingkat Pelanggaran
- Semua aturan menggunakan `tingkat_pelanggaran = 'ringan'`
- Disesuaikan dengan enum database yang hanya menerima: 'ringan', 'sedang', 'berat'

### Kode Kategori
- Menggunakan prefix "TT-" untuk membedakan dari kategori pelanggaran biasa
- Format: TT-I, TT-II, TT-III, dst.

### Kode Pelanggaran
- Format: TT-X.Y (contoh: TT-1.1, TT-2.3)
- Memudahkan identifikasi dan pengurutan

## Cara Menjalankan Seeder

```bash
# Menjalankan seeder khusus tata tertib
php artisan db:seed --class=TataTertibSeeder

# Atau menjalankan semua seeder (termasuk tata tertib)
php artisan db:seed
```

## Integrasi dengan Sistem

### TataTertibSiswa Component
Seeder ini terintegrasi dengan komponen `TataTertibSiswa.php` yang:
- Memfilter data berdasarkan `kode_kategori LIKE 'TT-%'`
- Memfilter data berdasarkan `poin_pelanggaran = 0`
- Menampilkan aturan dalam format halaman yang dapat dibaca siswa dan orang tua

### Fitur Pakta Integritas
Setelah membaca semua aturan, sistem akan:
- Menghasilkan PDF "Pakta Integritas Siswa"
- Memungkinkan download sebagai komitmen untuk mematuhi tata tertib

## Keunggulan Implementasi

1. **Reuse Database Structure**: Menggunakan struktur database yang sudah ada
2. **Flexible Filtering**: Mudah dibedakan dari data pelanggaran dengan filter sederhana
3. **Comprehensive Content**: Mencakup 8 aspek penting kehidupan sekolah
4. **Positive Approach**: Fokus pada aturan yang harus diikuti, bukan yang dilanggar
5. **Easy Maintenance**: Menggunakan `updateOrCreate` untuk menghindari duplikasi data

## Sumber Referensi
Data tata tertib disusun berdasarkan:
- Standar tata tertib sekolah menengah di Indonesia
- Best practices dari berbagai sekolah
- Peraturan Pemerintah tentang Standar Pengelolaan Pendidikan
- Nilai-nilai karakter yang ingin ditanamkan pada siswa

## Maintenance
Untuk memperbarui atau menambah aturan tata tertib:
1. Edit file `TataTertibSeeder.php`
2. Tambahkan kategori atau aturan baru sesuai format yang ada
3. Jalankan seeder ulang: `php artisan db:seed --class=TataTertibSeeder`

Seeder menggunakan `updateOrCreate` sehingga aman untuk dijalankan berulang kali tanpa membuat data duplikat.