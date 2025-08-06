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
        Schema::table('sanksi_pelanggaran', function (Blueprint $table) {
            // Drop the old index first
            $table->dropIndex('idx_sanksi_kelas_poin');
            
            // Drop the old column
            $table->dropColumn('tingkat_kelas');
            
            // Add the new column for violation levels
            $table->enum('tingkat_pelanggaran', ['ringan', 'sedang', 'berat', 'sangat_berat'])->after('id');
            
            // Add new index
            $table->index(['tingkat_pelanggaran', 'poin_minimum', 'poin_maksimum'], 'idx_sanksi_pelanggaran_poin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sanksi_pelanggaran', function (Blueprint $table) {
            // Drop the new index
            $table->dropIndex('idx_sanksi_pelanggaran_poin');
            
            // Drop the new column
            $table->dropColumn('tingkat_pelanggaran');
            
            // Add back the old column
            $table->integer('tingkat_kelas')->after('id');
            
            // Add back the old index
            $table->index(['tingkat_kelas', 'poin_minimum', 'poin_maksimum'], 'idx_sanksi_kelas_poin');
        });
    }
};
