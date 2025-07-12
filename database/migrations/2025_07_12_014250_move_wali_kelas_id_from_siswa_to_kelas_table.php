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
        // Add wali_kelas_id to kelas table if it doesn't exist
        Schema::table('kelas', function (Blueprint $table) {
            if (!Schema::hasColumn('kelas', 'wali_kelas_id')) {
                $table->unsignedBigInteger('wali_kelas_id')->nullable()->after('guru_id');
            }
        });

        // Add foreign key constraint for wali_kelas_id in kelas table
        Schema::table('kelas', function (Blueprint $table) {
            if (Schema::hasColumn('kelas', 'wali_kelas_id') && Schema::hasTable('wali_kelas')) {
                $table->foreign('wali_kelas_id')->references('id')->on('wali_kelas')->onDelete('set null');
            }
        });

        // Remove wali_kelas_id from siswa table if it exists
        Schema::table('siswa', function (Blueprint $table) {
            if (Schema::hasColumn('siswa', 'wali_kelas_id')) {
                $table->dropForeign(['wali_kelas_id']);
                $table->dropColumn('wali_kelas_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add wali_kelas_id back to siswa table
        Schema::table('siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('siswa', 'wali_kelas_id')) {
                $table->unsignedBigInteger('wali_kelas_id')->after('kelas_baru_id');
            }
        });

        // Add foreign key constraint for wali_kelas_id in siswa table
        Schema::table('siswa', function (Blueprint $table) {
            if (Schema::hasColumn('siswa', 'wali_kelas_id') && Schema::hasTable('wali_kelas')) {
                $table->foreign('wali_kelas_id')->references('id')->on('wali_kelas')->onDelete('cascade');
            }
        });

        // Remove wali_kelas_id from kelas table
        Schema::table('kelas', function (Blueprint $table) {
            if (Schema::hasColumn('kelas', 'wali_kelas_id')) {
                $table->dropForeign(['wali_kelas_id']);
                $table->dropColumn('wali_kelas_id');
            }
        });
    }
};
