# Panduan Reset Database dan Import Data

Panduan ini menjelaskan cara mereset database dan memulai import data dari awal untuk sistem DigiClass.

## 🔄 Reset Database

### 1. Reset Semua Data
```bash
php artisan migrate:fresh
```
Perintah ini akan:
- Menghapus semua tabel
- Menjalankan ulang semua migrasi
- Membuat struktur database yang bersih

### 2. Seed Data Dasar (Wajib)
```bash
# Buat user admin
php artisan db:seed --class=AdminUserSeeder

# Buat tahun pelajaran
php artisan db:seed --class=TahunPelajaranSeeder
```

## 📊 Import Data Siswa

### Melalui Dashboard Admin
1. Login ke sistem dengan:
   - **Email**: admin@digiclass.com
   - **Password**: password

2. Akses menu **Import Management**

3. Upload file Excel dengan format:
   - **NIS** (Nomor Induk Siswa)
   - **Nama** (Nama lengkap siswa)
   - **Email** (Email siswa)
   - **Tahun Pelajaran** (contoh: 2024/2025)

### Format File Excel
| NIS | Nama | Email | Tahun Pelajaran |
|-----|------|-------|----------------|
| 2024001 | Ahmad Rizki | ahmad@email.com | 2024/2025 |
| 2024002 | Siti Nurhaliza | siti@email.com | 2024/2025 |

## 🏫 Setup Kelas

### 1. Buat Kelas Baru
- Akses **Kelas Management**
- Tambah kelas dengan informasi:
  - Nama kelas (contoh: 10 IPA 1)
  - Tahun pelajaran
  - Wali kelas (opsional)
  - Link WhatsApp grup (opsional)

### 2. Assign Siswa ke Kelas
- Gunakan fitur **Class Management**
- Pilih siswa dan assign ke kelas yang sesuai

## 📚 Setup Data Perpustakaan

### Melalui Perpustakaan Management
- Akses **Perpustakaan Management**
- Set status perpustakaan untuk setiap siswa:
  - **Terpenuhi**: Siswa dapat akses WhatsApp grup
  - **Tidak Terpenuhi**: Siswa tidak dapat akses WhatsApp grup

## 🔐 Kredensial Default

### Admin User
- **Email**: admin@digiclass.com
- **Password**: password
- **Role**: Administrator

⚠️ **PENTING**: Ganti password default setelah login pertama!

## 📋 Checklist Setelah Reset

- [ ] Database berhasil direset
- [ ] Admin user berhasil dibuat
- [ ] Tahun pelajaran aktif tersedia
- [ ] Data siswa berhasil diimport
- [ ] Kelas-kelas sudah dibuat
- [ ] Siswa sudah diassign ke kelas
- [ ] Status perpustakaan sudah diset
- [ ] Password admin sudah diganti

## 🚀 Testing

### Test Halaman Pengumuman
1. Buka: `http://localhost:8000/pengumuman`
2. Masukkan NIS siswa yang sudah diimport
3. Verifikasi:
   - Nama siswa muncul
   - Kelas muncul (jika sudah diassign)
   - Link WhatsApp muncul (jika perpustakaan terpenuhi)

### Test Dashboard Admin
1. Login dengan kredensial admin
2. Test semua fitur management:
   - Import Management
   - Kelas Management
   - Class Management
   - Perpustakaan Management
   - Guru Management
   - Tahun Pelajaran Management

## 🛠️ Troubleshooting

### Error saat Import
- Pastikan format Excel sesuai
- Periksa tahun pelajaran sudah ada
- Pastikan NIS unik

### Siswa tidak muncul di pengumuman
- Periksa NIS sudah benar
- Pastikan tahun pelajaran aktif
- Cek data di database

### WhatsApp link tidak muncul
- Periksa status perpustakaan siswa
- Pastikan kelas memiliki link WhatsApp
- Verifikasi siswa sudah diassign ke kelas

---

**Catatan**: Panduan ini dibuat untuk memudahkan proses reset dan setup ulang sistem DigiClass.