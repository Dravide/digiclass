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
        // Add link_wa to kelas table
        Schema::table('kelas', function (Blueprint $table) {
            $table->string('link_wa')->nullable()->after('guru_id');
        });
        
        // Remove link_wa from siswa table
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn('link_wa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add link_wa back to siswa table
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('link_wa')->nullable()->after('tahun_pelajaran_id');
        });
        
        // Remove link_wa from kelas table
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('link_wa');
        });
    }
};
