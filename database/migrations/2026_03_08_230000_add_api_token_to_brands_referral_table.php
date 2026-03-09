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
            $table->string('api_token', 255)->nullable()->unique()->after('colors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands_referral', function (Blueprint $table) {
            $table->dropColumn('api_token');
        });
    }
};
