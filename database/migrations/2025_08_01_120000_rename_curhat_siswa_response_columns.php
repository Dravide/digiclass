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
        Schema::table('curhat_siswa', function (Blueprint $table) {
            // Rename response-related columns to handling-related columns
            $table->renameColumn('tanggal_respon', 'tanggal_penanganan');
            $table->renameColumn('respon_bk', 'penanganan');
            $table->renameColumn('petugas_bk', 'ditangani_oleh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('curhat_siswa', function (Blueprint $table) {
            // Revert column names back to original
            $table->renameColumn('tanggal_penanganan', 'tanggal_respon');
            $table->renameColumn('penanganan', 'respon_bk');
            $table->renameColumn('ditangani_oleh', 'petugas_bk');
        });
    }
};