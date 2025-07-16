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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_pelajaran_id')->constrained('tahun_pelajarans')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'])->comment('Hari dalam seminggu');
            $table->time('jam_mulai')->comment('Jam mulai pelajaran');
            $table->time('jam_selesai')->comment('Jam selesai pelajaran');
            $table->integer('jam_ke')->comment('Jam pelajaran ke berapa (1-10)');
            $table->text('keterangan')->nullable()->comment('Keterangan tambahan');
            $table->boolean('is_active')->default(true)->comment('Status aktif jadwal');
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['tahun_pelajaran_id', 'is_active']);
            $table->index(['guru_id', 'hari']);
            $table->index(['kelas_id', 'hari']);
            $table->index(['hari', 'jam_ke']);
            
            // Unique constraint untuk mencegah bentrok jadwal
            $table->unique(['tahun_pelajaran_id', 'kelas_id', 'hari', 'jam_ke'], 'unique_kelas_jadwal');
            $table->unique(['tahun_pelajaran_id', 'guru_id', 'hari', 'jam_mulai'], 'unique_guru_jadwal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
