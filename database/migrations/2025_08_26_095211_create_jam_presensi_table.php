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
        Schema::create('jam_presensi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_hari'); // Senin, Selasa, dst atau 'default' untuk semua hari
            $table->time('jam_masuk_mulai'); // Jam mulai bisa presensi masuk
            $table->time('jam_masuk_selesai'); // Jam terakhir bisa presensi masuk
            $table->time('jam_pulang_mulai'); // Jam mulai bisa presensi pulang
            $table->time('jam_pulang_selesai'); // Jam terakhir bisa presensi pulang
            $table->boolean('is_active')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index(['nama_hari', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_presensi');
    }
};
