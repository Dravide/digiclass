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
        Schema::create('tata_usaha', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tata_usaha');
            $table->string('nip')->unique();
            $table->string('email')->nullable();
            $table->string('telepon')->nullable();
            $table->string('jabatan');
            $table->text('bidang_tugas')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tata_usaha');
    }
};
