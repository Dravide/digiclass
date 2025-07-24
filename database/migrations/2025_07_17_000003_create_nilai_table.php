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
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->decimal('nilai', 5, 2)->nullable(); // Nilai dengan 2 desimal (0.00 - 100.00)
            $table->enum('status_pengumpulan', ['belum_mengumpulkan', 'terlambat', 'tepat_waktu'])->default('belum_mengumpulkan');
            $table->datetime('tanggal_pengumpulan')->nullable();
            $table->text('catatan_guru')->nullable();
            $table->text('catatan_siswa')->nullable();
            $table->string('file_tugas')->nullable(); // Path file tugas yang dikumpulkan
            $table->timestamps();
            
            // Unique constraint untuk mencegah duplikasi nilai per siswa per tugas
            $table->unique(['tugas_id', 'siswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};