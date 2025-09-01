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
            $table->string('foto_path')->nullable()->after('secure_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_qr', function (Blueprint $table) {
            $table->dropColumn('foto_path');
        });
    }
};