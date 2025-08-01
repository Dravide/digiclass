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
        Schema::create('curhat_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->nullable()->constrained('siswa')->onDelete('set null');
            $table->foreignId('tahun_pelajaran_id')->constrained('tahun_pelajarans')->onDelete('cascade');
            $table->enum('kategori', ['akademik', 'sosial', 'keluarga', 'pribadi', 'bullying', 'kesehatan', 'karir', 'lainnya']);
            $table->string('judul', 100);
            $table->text('isi_curhat');
            $table->boolean('is_anonim')->default(false);
            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditutup'])->default('pending');
            $table->datetime('tanggal_curhat');
            $table->string('nama_pengirim', 100)->nullable();
            $table->string('kelas_pengirim', 50)->nullable();
            $table->datetime('tanggal_respon')->nullable();
            $table->text('respon_bk')->nullable();
            $table->string('petugas_bk', 100)->nullable();
            $table->text('catatan_internal')->nullable();
            $table->timestamps();
            
            $table->index(['siswa_id', 'tahun_pelajaran_id']);
            $table->index(['status', 'tanggal_curhat']);
            $table->index(['kategori']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curhat_siswa');
    }
};
