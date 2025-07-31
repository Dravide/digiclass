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
        Schema::create('sanksi_pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->integer('tingkat_kelas'); // 7, 8, 9
            $table->integer('poin_minimum');
            $table->integer('poin_maksimum');
            $table->string('jenis_sanksi');
            $table->text('deskripsi_sanksi');
            $table->string('penanggungjawab'); // Wali Kelas, Guru BK, Kesiswaan, dll
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['tingkat_kelas', 'poin_minimum', 'poin_maksimum'], 'idx_sanksi_kelas_poin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sanksi_pelanggaran');
    }
};