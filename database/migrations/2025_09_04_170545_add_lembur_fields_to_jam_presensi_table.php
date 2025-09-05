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
        Schema::table('jam_presensi', function (Blueprint $table) {
            $table->time('jam_lembur_mulai')->nullable()->after('jam_pulang_selesai');
            $table->time('jam_lembur_selesai')->nullable()->after('jam_lembur_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jam_presensi', function (Blueprint $table) {
            $table->dropColumn(['jam_lembur_mulai', 'jam_lembur_selesai']);
        });
    }
};
