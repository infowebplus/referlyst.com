<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brands_referral', function (Blueprint $table): void {
            $table->string('landing_theme')->default('light')->after('colors');
        });
    }

    public function down(): void
    {
        Schema::table('brands_referral', function (Blueprint $table): void {
            $table->dropColumn('landing_theme');
        });
    }
};
