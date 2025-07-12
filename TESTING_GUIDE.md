# Testing Guide - Halaman Pengumuman Kelas

## URL Pengumuman
Buka browser dan akses: **http://localhost:8000/pengumuman**

## Data Test untuk NIS
Berikut adalah beberapa NIS siswa yang dapat digunakan untuk testing:

### Siswa dengan Status Perpustakaan Aktif (Dapat Akses WhatsApp)
- **NIS: 2024001** - Ahmad Rizki Pratama (Kelas: 10 IPA 1)
- **NIS: 2024002** - Siti Nurhaliza (Kelas: 10 IPA 1)
- **NIS: 2024004** - Dewi Sartika (Kelas: 10 IPA 1)
- **NIS: 2024006** - Rina Maharani (Kelas: 10 IPA 2)
- **NIS: 2024007** - Doni Setiawan (Kelas: 10 IPA 2)
- **NIS: 2023001** - Putri Ayu (Kelas: 11 IPA 1)
- **NIS: 2022002** - Gita Savitri (Kelas: 12 IPA 1)

### Siswa dengan Status Perpustakaan Tidak Aktif (Tidak Dapat Akses WhatsApp)
- **NIS: 2024003** - Budi Santoso (Kelas: 10 IPA 1)
- **NIS: 2024005** - Andi Wijaya (Kelas: 10 IPA 1)
- **NIS: 2024008** - Maya Sari (Kelas: 10 IPA 2)
- **NIS: 2023003** - Citra Kirana (Kelas: 11 IPA 1)
- **NIS: 2022001** - Fandi Rahman (Kelas: 12 IPA 1)

## Cara Testing
1. Buka halaman pengumuman di browser
2. Masukkan salah satu NIS di atas
3. Klik tombol "Cari Kelas"
4. Sistem akan menampilkan:
   
   **Jika status perpustakaan AKTIF:**
   - Nama siswa
   - Nama kelas
   - Tahun pelajaran aktif
   - Link WhatsApp grup kelas
   
   **Jika status perpustakaan TIDAK AKTIF:**
   - Nama siswa
   - Tahun pelajaran aktif
   - Pesan peringatan bahwa persyaratan perpustakaan belum terpenuhi
   - **TIDAK menampilkan** informasi kelas dan link WhatsApp

## Fitur yang Diimplementasi
- ✅ Halaman publik tanpa login
- ✅ Input NIS siswa
- ✅ Pencarian berdasarkan tahun ajaran aktif
- ✅ Tampilan nama kelas (hanya jika perpustakaan aktif)
- ✅ Link WhatsApp grup kelas (hanya jika perpustakaan aktif)
- ✅ Validasi status perpustakaan
- ✅ Penyembunyian informasi kelas jika perpustakaan belum terpenuhi
- ✅ UI/UX sesuai template sample
- ✅ Responsive design
- ✅ Error handling untuk NIS tidak ditemukan

## Catatan
- **KEAMANAN INFORMASI**: Siswa dengan status perpustakaan "tidak aktif" tidak dapat melihat informasi kelas dan link WhatsApp sama sekali
- Hanya siswa dengan status perpustakaan "aktif" yang dapat melihat nama kelas dan mengakses link WhatsApp
- Sistem menggunakan tahun pelajaran aktif (2024/2025)
- Link WhatsApp adalah contoh dan tidak mengarah ke grup sebenarnya
- Siswa yang belum memenuhi persyaratan perpustakaan akan mendapat pesan untuk menghubungi petugas perpustakaan