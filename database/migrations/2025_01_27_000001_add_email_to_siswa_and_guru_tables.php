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
        // Add email field to siswa table if it doesn't exist
        if (!Schema::hasColumn('siswa', 'email')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->string('email')->unique()->nullable()->after('nis');
            });
        }
        
        // Add email field to gurus table if it doesn't exist
        if (!Schema::hasColumn('gurus', 'email')) {
            Schema::table('gurus', function (Blueprint $table) {
                $table->string('email')->unique()->nullable()->after('nip');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove email field from siswa table if it exists
        if (Schema::hasColumn('siswa', 'email')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }
        
        // Remove email field from gurus table if it exists
        if (Schema::hasColumn('gurus', 'email')) {
            Schema::table('gurus', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }
    }
};