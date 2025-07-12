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
        // Remove foreign key constraint from kelas table first
        Schema::table('kelas', function (Blueprint $table) {
            if (Schema::hasColumn('kelas', 'wali_kelas_id')) {
                $table->dropForeign(['wali_kelas_id']);
                $table->dropColumn('wali_kelas_id');
            }
        });

        // Drop wali_kelas table
        Schema::dropIfExists('wali_kelas');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate wali_kelas table
        Schema::create('wali_kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_wali');
            $table->string('nip')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('telepon')->nullable();
            $table->timestamps();
        });

        // Add wali_kelas_id back to kelas table
        Schema::table('kelas', function (Blueprint $table) {
            $table->unsignedBigInteger('wali_kelas_id')->nullable()->after('guru_id');
            $table->foreign('wali_kelas_id')->references('id')->on('wali_kelas')->onDelete('set null');
        });
    }
};
