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
        Schema::create('brand_api_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('brands_referral')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('token_hash', 255);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_api_tokens');
    }
};
