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
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->string('jenis_surat');
            $table->string('perihal');
            $table->text('isi_surat');
            $table->string('penerima');
            $table->string('jabatan_penerima')->nullable();
            $table->date('tanggal_surat');
            $table->text('signature_data')->nullable(); // Base64 signature data
            $table->string('qr_code_path')->nullable(); // Path to QR code image
            $table->enum('status', ['draft', 'signed', 'validated'])->default('draft');
            $table->timestamp('signed_at')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};