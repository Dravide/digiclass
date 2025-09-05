<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update enum untuk menambahkan 'lembur'
        DB::statement("ALTER TABLE presensi_qr MODIFY COLUMN jenis_presensi ENUM('masuk', 'pulang', 'lembur') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus data lembur terlebih dahulu sebelum mengubah enum
        DB::table('presensi_qr')->where('jenis_presensi', 'lembur')->delete();
        
        // Kembalikan enum ke nilai semula
        DB::statement("ALTER TABLE presensi_qr MODIFY COLUMN jenis_presensi ENUM('masuk', 'pulang') NOT NULL");
    }
};