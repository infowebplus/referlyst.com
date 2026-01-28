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
        Schema::create('referrers_brands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained('brands_referral')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->string('comission_type'); // fixed / percent
            $table->decimal('comission', 12, 2);
            $table->date('date');
            $table->string('recommended_to_name');
            $table->string('recommended_to_surname');
            $table->string('recommended_to_phone');
            $table->string('recommended_to_email');
            $table->unsignedTinyInteger('paid')->default(0);
            $table->unsignedInteger('paid_sum')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrers_brands');
    }
};
