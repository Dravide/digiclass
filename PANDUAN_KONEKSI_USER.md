# Panduan Menghubungkan Akun User dengan Data Guru/Siswa

## Masalah yang Diselesaikan

Ketika user login dan mengakses halaman seperti "Kelas Saya" (untuk guru) atau "Nilai Saya" (untuk siswa), muncul pesan error:
- **Guru**: "Data Guru Tidak Ditemukan - Akun Anda belum terhubung dengan data guru"
- **Siswa**: "Data Siswa Tidak Ditemukan - Akun Anda belum terhubung dengan data siswa"

Hal ini terjadi karena sistem mencari data guru/siswa berdasarkan email user yang login, tetapi data guru/siswa di database belum memiliki email yang sesuai.

## Solusi yang Tersedia

### 1. Melalui Interface Web (Untuk Admin)

**Langkah-langkah:**

1. **Login sebagai Admin**
   - Pastikan Anda login dengan akun yang memiliki role `admin`

2. **Akses Halaman Manajemen Koneksi User**
   - Buka menu **Manajemen Data** → **Koneksi User**
   - Atau akses langsung: `http://localhost:8000/user-link-management`

3. **Hubungkan User dengan Data Guru/Siswa**
   - Cari user yang ingin dihubungkan menggunakan fitur pencarian
   - Klik tombol **"Hubungkan"** pada user yang belum terhubung
   - Pilih tipe koneksi: **Guru** atau **Siswa**
   - Pilih data guru/siswa yang sesuai dari dropdown
   - Klik **"Hubungkan"**

4. **Verifikasi Koneksi**
   - Status koneksi akan berubah menjadi "Terhubung"
   - Data yang terhubung akan ditampilkan di kolom "Data Terhubung"

### 2. Melalui Command Line (Untuk Developer/Admin Teknis)

**Sintaks Command:**
```bash
php artisan user:link {email} [--type=guru|siswa] [--target-email=email] [--nip=nip] [--nis=nis]
```

**Contoh Penggunaan:**

1. **Hubungkan user dengan guru berdasarkan NIP:**
   ```bash
   php artisan user:link guru@example.com --type=guru --nip=123456789
   ```

2. **Hubungkan user dengan siswa berdasarkan NIS:**
   ```bash
   php artisan user:link siswa@example.com --type=siswa --nis=987654321
   ```

3. **Hubungkan user dengan guru berdasarkan email guru:**
   ```bash
   php artisan user:link user@example.com --type=guru --target-email=guru.existing@school.com
   ```

4. **Mode interaktif (akan menampilkan pilihan):**
   ```bash
   php artisan user:link user@example.com
   ```

## Cara Kerja Sistem

### Untuk Guru:
1. User login dengan email (misal: `guru@example.com`)
2. Sistem mencari data di tabel `gurus` dengan `email = 'guru@example.com'`
3. Jika ditemukan, data guru ditampilkan
4. Jika tidak ditemukan, muncul pesan error

### Untuk Siswa:
1. User login dengan email (misal: `siswa@example.com`)
2. Sistem mencari data di tabel `siswa` dengan `email = 'siswa@example.com'`
3. Jika ditemukan, data siswa ditampilkan
4. Jika tidak ditemukan, muncul pesan error

## Struktur Database

### Tabel `users`
- `id` (Primary Key)
- `name`
- `email` (Unique)
- `role` (admin, guru, siswa, tatausaha)
- `password`

### Tabel `gurus`
- `id` (Primary Key)
- `nama_guru`
- `nip` (Unique)
- `email` (Unique, Nullable) ← **Field ini yang menghubungkan dengan user**
- `telepon`
- `mata_pelajaran_id`

### Tabel `siswa`
- `id` (Primary Key)
- `nama_siswa`
- `nis` (Unique)
- `nisn` (Unique)
- `email` (Unique, Nullable) ← **Field ini yang menghubungkan dengan user**
- `tahun_pelajaran_id`
- `status`

## Troubleshooting

### 1. Error "Route [user-link-management] not defined"
**Solusi:** Pastikan route sudah ditambahkan di `routes/web.php`

### 2. Error "Permission denied"
**Solusi:** Pastikan user memiliki permission `manage-users`

### 3. Data guru/siswa tidak muncul di dropdown
**Solusi:** 
- Pastikan data guru/siswa sudah ada di database
- Periksa apakah email sudah terisi (sistem hanya menampilkan data tanpa email atau dengan email berbeda)

### 4. Setelah dihubungkan masih muncul error
**Solusi:**
- Logout dan login kembali
- Periksa apakah email di tabel guru/siswa sudah sesuai dengan email user
- Pastikan role user sudah sesuai (guru/siswa)

## Fitur Tambahan

### Putus Koneksi
- Admin dapat memutus koneksi user dengan data guru/siswa
- Klik tombol **"Putus Koneksi"** pada user yang sudah terhubung
- Email di tabel guru/siswa akan dikosongkan

### Filter dan Pencarian
- Filter berdasarkan role user
- Pencarian berdasarkan nama atau email user
- Status koneksi (Terhubung/Belum Terhubung)

### Auto Role Update
- Ketika user dihubungkan dengan guru, role otomatis berubah menjadi 'guru'
- Ketika user dihubungkan dengan siswa, role otomatis berubah menjadi 'siswa'

## Catatan Penting

1. **Satu user hanya bisa terhubung dengan satu data guru atau siswa**
2. **Email di tabel guru/siswa harus unique**
3. **Pastikan data guru/siswa sudah ada sebelum menghubungkan**
4. **Backup database sebelum melakukan perubahan massal**
5. **Test koneksi setelah menghubungkan user**

## Contoh Skenario

### Skenario 1: Guru Baru
1. Admin membuat akun user baru dengan email `guru.baru@school.com` dan role `guru`
2. Admin menambahkan data guru baru di "Data Guru" tanpa mengisi email
3. Guru login dan mengakses "Kelas Saya" → muncul error
4. Admin masuk ke "Koneksi User" dan menghubungkan user dengan data guru
5. Guru login kembali → berhasil melihat kelasnya

### Skenario 2: Siswa Existing
1. Data siswa sudah ada di database tanpa email
2. Admin membuat akun user untuk siswa dengan email `siswa@school.com`
3. Admin menghubungkan user dengan data siswa menggunakan command:
   ```bash
   php artisan user:link siswa@school.com --type=siswa --nis=123456
   ```
4. Siswa login → berhasil melihat nilai dan presensinya

Dengan mengikuti panduan ini, masalah "Data Guru/Siswa Tidak Ditemukan" dapat diselesaikan dengan mudah.