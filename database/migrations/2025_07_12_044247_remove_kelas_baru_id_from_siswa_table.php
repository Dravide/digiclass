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
        Schema::table('siswa', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['kelas_baru_id']);
            // Then drop the column
            $table->dropColumn('kelas_baru_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Add the column back
            $table->unsignedBigInteger('kelas_baru_id')->nullable()->after('kelas_id');
            // Add foreign key constraint back
            $table->foreign('kelas_baru_id')->references('id')->on('kelas')->onDelete('set null');
        });
    }
};
