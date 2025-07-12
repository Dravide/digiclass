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
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mapel', 10)->unique()->comment('Kode mata pelajaran (contoh: MTK, IPA, IPS)');
            $table->string('nama_mapel', 100)->comment('Nama mata pelajaran');
            $table->text('deskripsi')->nullable()->comment('Deskripsi mata pelajaran');
            $table->integer('jam_pelajaran')->default(2)->comment('Jumlah jam pelajaran per minggu');
            $table->enum('kategori', ['wajib', 'pilihan', 'muatan_lokal'])->default('wajib')->comment('Kategori mata pelajaran');
            $table->boolean('is_active')->default(true)->comment('Status aktif mata pelajaran');
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['is_active']);
            $table->index(['kategori']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_pelajaran');
    }
};
