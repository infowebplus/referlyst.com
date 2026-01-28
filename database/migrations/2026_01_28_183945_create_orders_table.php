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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained('brands_referral')->cascadeOnDelete();
            $table->date('date');
            $table->unsignedBigInteger('model_id');
            $table->string('model_type');
            $table->decimal('price', 10, 2);
            $table->unsignedTinyInteger('paid')->default(0);
            $table->date('paid_date')->nullable();
            $table->text('paid_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
