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
        Schema::table('presensi_qr', function (Blueprint $table) {
            $table->boolean('is_lembur')->default(false)->after('jam_pulang_standar');
            $table->integer('menit_lembur')->nullable()->after('is_lembur');
            $table->time('jam_lembur_standar')->nullable()->after('menit_lembur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_qr', function (Blueprint $table) {
            $table->dropColumn(['is_lembur', 'menit_lembur', 'jam_lembur_standar']);
        });
    }
};
