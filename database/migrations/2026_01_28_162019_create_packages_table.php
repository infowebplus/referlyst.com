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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->longText('description');
            $table->unsignedTinyInteger('email_notifications');
            $table->unsignedInteger('no_of_referrers')->default(0);
            $table->unsignedInteger('no_of_referrers_brands')->default(0);
            $table->unsignedTinyInteger('commission_tracking');
            $table->unsignedTinyInteger('export_csv');
            $table->unsignedTinyInteger('custom_branding');
            $table->json('included_addons')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
