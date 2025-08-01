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
        Schema::table('pelanggaran_siswa', function (Blueprint $table) {
            $table->string('jam_pelanggaran', 5)->nullable()->after('tanggal_pelanggaran');
            $table->string('lokasi', 100)->nullable()->after('deskripsi_pelanggaran');
            $table->enum('pelapor_type', ['guru', 'siswa', 'staff', 'lainnya'])->nullable()->after('pelapor');
            $table->string('qr_area_code', 50)->nullable()->after('pelapor_type');
            $table->index(['pelapor_type']);
            $table->index(['qr_area_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelanggaran_siswa', function (Blueprint $table) {
            $table->dropIndex(['pelapor_type']);
            $table->dropIndex(['qr_area_code']);
            $table->dropColumn(['jam_pelanggaran', 'lokasi', 'pelapor_type', 'qr_area_code']);
        });
    }
};
