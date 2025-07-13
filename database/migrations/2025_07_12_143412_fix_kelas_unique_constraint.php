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
        Schema::table('kelas', function (Blueprint $table) {
            // Drop the existing unique constraint on nama_kelas
            $table->dropUnique(['nama_kelas']);
            
            // Add new unique constraint for nama_kelas + tahun_pelajaran_id
            $table->unique(['nama_kelas', 'tahun_pelajaran_id'], 'kelas_nama_tahun_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Drop the combined unique constraint
            $table->dropUnique('kelas_nama_tahun_unique');
            
            // Restore the original unique constraint on nama_kelas
            $table->unique('nama_kelas');
        });
    }
};
