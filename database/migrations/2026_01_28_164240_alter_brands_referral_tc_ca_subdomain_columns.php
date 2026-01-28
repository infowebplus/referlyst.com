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
        Schema::table('brands_referral', function (Blueprint $table) {
            $table->string('terms_and_conditions', 1000)->nullable()->change();
            $table->string('ca', 1000)->nullable()->change();
            $table->string('subdomain_for_landing_pages')->nullable()->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands_referral', function (Blueprint $table) {
            $table->longText('terms_and_conditions')->nullable()->change();
            $table->longText('ca')->nullable()->change();
            $table->string('subdomain_for_landing_pages')->nullable(false)->unique(false)->change();
        });
    }
};
