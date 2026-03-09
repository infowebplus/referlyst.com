<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brand_api_tokens', function (Blueprint $table): void {
            // Default to both scopes so existing tokens keep full access.
            $table->json('scopes')->default('["form","referrals"]')->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('brand_api_tokens', function (Blueprint $table): void {
            $table->dropColumn('scopes');
        });
    }
};
