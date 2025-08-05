<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the enum column to include 'sangat_berat'
        DB::statement("ALTER TABLE jenis_pelanggaran MODIFY COLUMN tingkat_pelanggaran ENUM('ringan', 'sedang', 'berat', 'sangat_berat') DEFAULT 'ringan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE jenis_pelanggaran MODIFY COLUMN tingkat_pelanggaran ENUM('ringan', 'sedang', 'berat') DEFAULT 'ringan'");
    }
};
