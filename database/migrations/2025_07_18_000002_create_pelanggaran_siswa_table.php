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
        Schema::create('pelanggaran_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('tahun_pelajaran_id')->constrained('tahun_pelajarans')->onDelete('cascade');
            $table->string('jenis_pelanggaran');
            $table->text('deskripsi_pelanggaran');
            $table->integer('poin_pelanggaran');
            $table->date('tanggal_pelanggaran');
            $table->string('pelapor'); // Nama guru/staff yang melaporkan
            $table->text('tindak_lanjut')->nullable();
            $table->enum('status_penanganan', ['belum_ditangani', 'dalam_proses', 'selesai'])->default('belum_ditangani');
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            $table->index(['siswa_id', 'tahun_pelajaran_id']);
            $table->index('tanggal_pelanggaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggaran_siswa');
    }
};