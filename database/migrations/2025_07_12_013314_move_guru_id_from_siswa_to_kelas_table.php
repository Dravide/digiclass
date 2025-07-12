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
        // Add guru_id to kelas table if it doesn't exist
        if (!Schema::hasColumn('kelas', 'guru_id')) {
            Schema::table('kelas', function (Blueprint $table) {
                $table->unsignedBigInteger('guru_id')->nullable()->after('kapasitas');
                $table->foreign('guru_id')->references('id')->on('gurus')->onDelete('set null');
            });
        }

        // Remove guru_id from siswa table if it exists
        if (Schema::hasColumn('siswa', 'guru_id')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->dropForeign(['guru_id']);
                $table->dropColumn('guru_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add guru_id back to siswa table if it doesn't exist
        if (!Schema::hasColumn('siswa', 'guru_id')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->unsignedBigInteger('guru_id')->nullable()->after('kelas_baru_id');
                $table->foreign('guru_id')->references('id')->on('gurus')->onDelete('set null');
            });
        }

        // Remove guru_id from kelas table if it exists
        if (Schema::hasColumn('kelas', 'guru_id')) {
            Schema::table('kelas', function (Blueprint $table) {
                $table->dropForeign(['guru_id']);
                $table->dropColumn('guru_id');
            });
        }
    }
};
