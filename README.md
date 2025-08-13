<p align="center"><a href="https://digiclass.smpn1cipanas.sch.id" target="_blank"><img src="https://digiclass.smpn1cipanas.sch.id/assets/images/logo-dark.png" width="400" alt="DigiClass Logo"></a></p>

<p align="center">
<img src="https://img.shields.io/badge/Laravel-11.x-red.svg" alt="Laravel Version">
<img src="https://img.shields.io/badge/PHP-8.2+-blue.svg" alt="PHP Version">
<img src="https://img.shields.io/badge/Livewire-3.x-green.svg" alt="Livewire Version">
<img src="https://img.shields.io/badge/License-MIT-yellow.svg" alt="License">
</p>

## Tentang DigiClass

DigiClass adalah sistem manajemen kelas digital yang dirancang khusus untuk SMPN 1 Cipanas. Platform ini menyediakan berbagai fitur untuk mendukung proses pembelajaran dan administrasi sekolah secara digital, meliputi:

- [Manajemen Siswa dan Kelas](https://digiclass.smpn1cipanas.sch.id).
- [Sistem Presensi dengan QR Code](https://digiclass.smpn1cipanas.sch.id).
- [Manajemen Nilai dan Rapor Digital](https://digiclass.smpn1cipanas.sch.id).
- [Sistem Pelanggaran dan Sanksi](https://digiclass.smpn1cipanas.sch.id).
- [Perpustakaan Digital](https://digiclass.smpn1cipanas.sch.id).
- [Platform Komunikasi Sekolah](https://digiclass.smpn1cipanas.sch.id).
- [Layanan Konseling Siswa](https://digiclass.smpn1cipanas.sch.id).

DigiClass mudah diakses, powerful, dan menyediakan tools yang dibutuhkan untuk manajemen sekolah yang efisien dan modern.

## Instalasi

### Persyaratan Sistem
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM
- MySQL 8.0 atau MariaDB 10.3+
- Web Server (Apache/Nginx)

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/smpn1cipanas/digiclass.git
   cd digiclass
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setup Database**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Build Assets**
   ```bash
   npm run build
   ```

6. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```

## Fitur Utama

### 👥 Manajemen Pengguna
- **Multi-Role System**: Admin, Guru, dan Siswa
- **Autentikasi Aman**: Login dengan validasi role
- **Profile Management**: Kelola data profil pengguna

### 📚 Akademik
- **Manajemen Kelas**: Organisasi kelas dan siswa
- **Jadwal Pelajaran**: Penjadwalan mata pelajaran
- **Nilai & Rapor**: Sistem penilaian digital
- **Tugas**: Pemberian dan pengumpulan tugas

### 📊 Presensi
- **QR Code Attendance**: Presensi dengan scan QR
- **Real-time Monitoring**: Pantau kehadiran siswa
- **Laporan Kehadiran**: Statistik presensi lengkap

### 🏛️ Perpustakaan Digital
- **Katalog Buku**: Database buku perpustakaan
- **Peminjaman Online**: Sistem peminjaman digital
- **Tracking**: Pelacakan status peminjaman

### ⚖️ Tata Tertib
- **Sistem Pelanggaran**: Pencatatan pelanggaran siswa
- **Sanksi Otomatis**: Penerapan sanksi sesuai aturan
- **Laporan Pelanggaran**: Monitoring perilaku siswa

### 💬 Konseling
- **Curhat Online**: Platform konseling siswa
- **Konsultasi Privat**: Komunikasi dengan konselor
- **Follow-up**: Tindak lanjut masalah siswa

## Teknologi yang Digunakan

- **Backend**: Laravel 11.x
- **Frontend**: Livewire 3.x, Alpine.js
- **Database**: MySQL/MariaDB
- **UI Framework**: Bootstrap 5
- **Icons**: Font Awesome
- **PDF Generator**: DomPDF
- **Authentication**: Laravel Sanctum
- **Permissions**: Spatie Laravel Permission

## Kontribusi

Kami menyambut kontribusi dari komunitas! Untuk berkontribusi:

1. Fork repository ini
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Tim Pengembang

- **SMPN 1 Cipanas** - *Inisiator Proyek*
- **Tim IT SMPN 1 Cipanas** - *Development & Maintenance*

## Keamanan

Jika Anda menemukan kerentanan keamanan dalam DigiClass, silakan kirim email ke [admin@smpn1cipanas.sch.id](mailto:admin@smpn1cipanas.sch.id). Semua kerentanan keamanan akan segera ditangani.

## Dukungan

Untuk dukungan teknis dan pertanyaan:
- **Email**: [support@smpn1cipanas.sch.id](mailto:support@smpn1cipanas.sch.id)
- **Website**: [https://digiclass.smpn1cipanas.sch.id](https://digiclass.smpn1cipanas.sch.id)
- **Alamat**: SMPN 1 Cipanas, Jl. Raya Cipanas, Cianjur, Jawa Barat

## Lisensi

DigiClass adalah software open-source yang dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).

---

<p align="center">
<strong>Dikembangkan dengan ❤️ untuk SMPN 1 Cipanas</strong>
</p>
