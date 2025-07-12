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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nama_siswa');
            $table->enum('jk', ['L', 'P']); // Jenis Kelamin: L = Laki-laki, P = Perempuan
            $table->string('nisn')->unique();
            $table->string('nis')->unique();
            $table->unsignedBigInteger('kelas_id');
            $table->unsignedBigInteger('kelas_baru_id')->nullable();
            $table->unsignedBigInteger('wali_kelas_id');
            $table->string('link_wa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
