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
        Schema::create('license_settings', function (Blueprint $table) {
            $table->id();
            $table->string('license_key')->unique();
            $table->string('domain');
            $table->string('app_name')->default('DigiClass');
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->json('features')->nullable(); // untuk fitur yang diizinkan
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['domain', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_settings');
    }
};
