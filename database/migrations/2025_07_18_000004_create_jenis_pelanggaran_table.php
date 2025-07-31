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
        Schema::create('jenis_pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_pelanggaran_id')->constrained('kategori_pelanggaran')->onDelete('cascade');
            $table->string('kode_pelanggaran', 10); // 1.1, 1.2, 2.1, dst
            $table->string('nama_pelanggaran');
            $table->text('deskripsi_pelanggaran');
            $table->integer('poin_pelanggaran');
            $table->enum('tingkat_pelanggaran', ['ringan', 'sedang', 'berat'])->default('ringan');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique('kode_pelanggaran');
            $table->index('kategori_pelanggaran_id', 'idx_jenis_kategori');
            $table->index('tingkat_pelanggaran', 'idx_jenis_tingkat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_pelanggaran');
    }
};