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
        Schema::create('presensi_qr', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('secure_code', 25);
            $table->enum('jenis_presensi', ['masuk', 'pulang']);
            $table->timestamp('waktu_presensi');
            $table->string('keterangan')->nullable();
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index(['user_id', 'jenis_presensi']);
            $table->index(['secure_code']);
            $table->index(['waktu_presensi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_qr');
    }
};
