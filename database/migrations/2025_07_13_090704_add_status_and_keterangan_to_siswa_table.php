<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif')->after('tahun_pelajaran_id');
            $table->enum('keterangan', ['siswa_baru', 'pindahan', 'mengundurkan_diri', 'keluar', 'meninggal_dunia', 'alumni'])->default('siswa_baru')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn(['status', 'keterangan']);
        });
    }
};
