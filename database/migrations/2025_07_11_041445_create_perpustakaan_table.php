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
        Schema::create('perpustakaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->boolean('terpenuhi')->default(false); // Status perpustakaan: true = terpenuhi, false = belum terpenuhi
            $table->text('keterangan')->nullable(); // Keterangan tambahan
            $table->timestamp('tanggal_pemenuhan')->nullable(); // Tanggal ketika persyaratan terpenuhi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perpustakaan');
    }
};
