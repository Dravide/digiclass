# Menu Management System

## Deskripsi
Sistem manajemen menu dinamis untuk DigiClass yang memungkinkan admin mengelola menu aplikasi melalui database tanpa perlu mengubah kode.

## Fitur
- ✅ CRUD menu (Create, Read, Update, Delete)
- ✅ Pengaturan role-based menu (admin, guru, siswa, tata_usaha, bk)
- ✅ Pengaturan permission untuk setiap menu
- ✅ Pengaturan urutan menu
- ✅ Support submenu/parent-child menu
- ✅ Toggle status aktif/nonaktif menu
- ✅ Filter berdasarkan seksi dan role
- ✅ Search menu berdasarkan nama, route, atau permission

## Komponen yang Dibuat

### 1. Database
- **Migration**: `2025_01_29_000001_create_menus_table.php`
- **Model**: `app/Models/Menu.php`
- **Seeder**: `database/seeders/MenuSeeder.php`

### 2. Backend
- **Livewire Component**: `app/Livewire/Admin/MenuManagement.php`
- **Route**: Ditambahkan ke `routes/admin/admin.php`
- **Permission**: `manage-menu` ditambahkan ke `RolePermissionSeeder.php`

### 3. Frontend
- **Blade View**: `resources/views/livewire/admin/menu-management.blade.php`
- **Menu Item**: Ditambahkan ke `app/Helpers/MenuHelper.php`

## Cara Menggunakan

### Akses Menu Management
1. Login sebagai admin
2. Buka sidebar menu "Manajemen Data"
3. Klik "Manajemen Menu"
4. URL: `http://localhost:8000/menu-management`

### Menambah Menu Baru
1. Klik tombol "Tambah Menu"
2. Isi form:
   - **Nama Menu**: Nama yang akan ditampilkan
   - **Route**: Route Laravel (opsional untuk parent menu)
   - **Icon**: Class icon Remix Icon (contoh: `ri-dashboard-line`)
   - **Permission**: Permission yang diperlukan
   - **Seksi**: Kategori menu (contoh: "Menu Utama", "Manajemen Data")
   - **Role**: Pilih role yang dapat mengakses menu
   - **Urutan**: Urutan tampil menu
   - **Parent Menu**: Pilih parent jika ini submenu
   - **Status**: Aktif/nonaktif
   - **Deskripsi**: Deskripsi menu (opsional)
3. Klik "Simpan"

### Mengedit Menu
1. Klik tombol edit (ikon pensil) pada menu yang ingin diedit
2. Ubah data yang diperlukan
3. Klik "Perbarui"

### Menghapus Menu
1. Klik tombol hapus (ikon tempat sampah)
2. Konfirmasi penghapusan
3. **Catatan**: Menu yang memiliki submenu tidak dapat dihapus

### Toggle Status Menu
- Klik switch toggle pada kolom "Status" untuk mengaktifkan/menonaktifkan menu

## Struktur Database

```sql
CREATE TABLE menus (
    id BIGINT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    route VARCHAR(255) NULL,
    icon VARCHAR(255) NOT NULL,
    permission VARCHAR(255) NOT NULL,
    section VARCHAR(255) NOT NULL,
    roles JSON NOT NULL,
    order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    has_submenu BOOLEAN DEFAULT FALSE,
    parent_id BIGINT NULL,
    description TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES menus(id) ON DELETE CASCADE
);
```

## Permission
- **manage-menu**: Permission untuk mengakses menu management
- Hanya role `admin` yang memiliki akses ke fitur ini

## Catatan Penting
1. **Backup Data**: Selalu backup database sebelum melakukan perubahan besar
2. **Permission**: Pastikan permission yang digunakan sudah ada di sistem
3. **Route**: Pastikan route yang dimasukkan sudah terdaftar di Laravel
4. **Icon**: Gunakan class icon dari Remix Icon (https://remixicon.com/)
5. **Role**: Role yang tersedia: admin, guru, siswa, tata_usaha, bk

## Troubleshooting

### Menu tidak muncul di sidebar
1. Periksa apakah menu sudah diaktifkan
2. Periksa apakah user memiliki role yang sesuai
3. Periksa apakah user memiliki permission yang diperlukan
4. Periksa apakah route sudah terdaftar

### Error saat menghapus menu
- Pastikan menu tidak memiliki submenu
- Hapus submenu terlebih dahulu sebelum menghapus parent menu

### Error permission denied
- Pastikan user memiliki permission `manage-menu`
- Pastikan user memiliki role `admin`

## Pengembangan Selanjutnya
- [ ] Drag & drop untuk mengubah urutan menu
- [ ] Import/export konfigurasi menu
- [ ] Preview menu sebelum disimpan
- [ ] Bulk operations (aktifkan/nonaktifkan multiple menu)
- [ ] Menu versioning/history