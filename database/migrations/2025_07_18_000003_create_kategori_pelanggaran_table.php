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
        Schema::create('kategori_pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kategori', 10); // I, II, III, IV, V, VI, VII
            $table->string('nama_kategori');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            
            $table->unique('kode_kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_pelanggaran');
    }
};