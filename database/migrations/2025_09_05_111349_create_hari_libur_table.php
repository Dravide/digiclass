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
        Schema::create('hari_libur', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique()->comment('Tanggal hari libur');
            $table->string('tanggal_display')->comment('Format tanggal untuk display');
            $table->string('keterangan')->comment('Keterangan hari libur');
            $table->boolean('is_cuti')->default(false)->comment('Apakah hari cuti bersama');
            $table->boolean('is_aktif')->default(true)->comment('Status aktif hari libur');
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['tanggal', 'is_aktif']);
            $table->index('is_aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hari_libur');
    }
};
