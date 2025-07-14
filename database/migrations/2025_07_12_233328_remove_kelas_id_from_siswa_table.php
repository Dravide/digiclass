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
            // Drop foreign key constraint first if it exists
            $table->dropForeign(['kelas_id']);
            // Then drop the column
            $table->dropColumn('kelas_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Add kelas_id column back
            $table->unsignedBigInteger('kelas_id')->nullable()->after('nis');
            // Add foreign key constraint
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
        });
    }
};
