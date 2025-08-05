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
        Schema::create('pakta_integritas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_file');
            $table->string('file_path');
            $table->string('file_type')->default('pdf');
            $table->integer('file_size'); // in bytes
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('uploaded_by')->nullable(); // user who uploaded
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pakta_integritas');
    }
};