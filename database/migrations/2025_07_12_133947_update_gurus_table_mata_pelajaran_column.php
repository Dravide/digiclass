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
        Schema::table('gurus', function (Blueprint $table) {
            // Drop the old mata_pelajaran column if it exists
            if (Schema::hasColumn('gurus', 'mata_pelajaran')) {
                $table->dropColumn('mata_pelajaran');
            }
            
            // Add new mata_pelajaran_id column as foreign key if it doesn't exist
            if (!Schema::hasColumn('gurus', 'mata_pelajaran_id')) {
                $table->unsignedBigInteger('mata_pelajaran_id')->nullable()->after('is_wali_kelas');
                $table->foreign('mata_pelajaran_id')->references('id')->on('mata_pelajaran')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['mata_pelajaran_id']);
            $table->dropColumn('mata_pelajaran_id');
            
            // Restore old mata_pelajaran column
            $table->string('mata_pelajaran', 100)->nullable()->after('is_wali_kelas');
        });
    }
};
