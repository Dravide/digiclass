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
        Schema::table('presensi_qr', function (Blueprint $table) {
            // Add missing columns that are referenced in the model but don't exist in production
            if (!Schema::hasColumn('presensi_qr', 'foto_path')) {
                $table->string('foto_path')->nullable()->after('keterangan');
            }
            if (!Schema::hasColumn('presensi_qr', 'is_terlambat')) {
                $table->boolean('is_terlambat')->default(false)->after('foto_path');
            }
            if (!Schema::hasColumn('presensi_qr', 'menit_keterlambatan')) {
                $table->integer('menit_keterlambatan')->nullable()->after('is_terlambat');
            }
            if (!Schema::hasColumn('presensi_qr', 'is_pulang_awal')) {
                $table->boolean('is_pulang_awal')->default(false)->after('menit_keterlambatan');
            }
            if (!Schema::hasColumn('presensi_qr', 'menit_kepulangan_awal')) {
                $table->integer('menit_kepulangan_awal')->nullable()->after('is_pulang_awal');
            }
            if (!Schema::hasColumn('presensi_qr', 'jam_masuk_standar')) {
                $table->time('jam_masuk_standar')->nullable()->after('menit_kepulangan_awal');
            }
            if (!Schema::hasColumn('presensi_qr', 'jam_pulang_standar')) {
                $table->time('jam_pulang_standar')->nullable()->after('jam_masuk_standar');
            }
            
            // Add lembur fields
            if (!Schema::hasColumn('presensi_qr', 'is_lembur')) {
                $table->boolean('is_lembur')->default(false)->after('jam_pulang_standar');
            }
            if (!Schema::hasColumn('presensi_qr', 'menit_lembur')) {
                $table->integer('menit_lembur')->nullable()->after('is_lembur');
            }
            if (!Schema::hasColumn('presensi_qr', 'jam_lembur_standar')) {
                $table->time('jam_lembur_standar')->nullable()->after('menit_lembur');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_qr', function (Blueprint $table) {
            // Drop all columns that were added in this migration
            $columnsToCheck = [
                'jam_lembur_standar',
                'menit_lembur', 
                'is_lembur',
                'jam_pulang_standar',
                'jam_masuk_standar',
                'menit_kepulangan_awal',
                'is_pulang_awal',
                'menit_keterlambatan',
                'is_terlambat',
                'foto_path'
            ];
            
            $existingColumns = [];
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('presensi_qr', $column)) {
                    $existingColumns[] = $column;
                }
            }
            
            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};
