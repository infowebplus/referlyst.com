<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referrers_brands', function (Blueprint $table) {
            $table->string('referrer_name')->nullable()->after('date');
            $table->string('referrer_email')->nullable()->after('referrer_name');
            $table->string('referrer_phone')->nullable()->after('referrer_email');
        });
    }

    public function down(): void
    {
        Schema::table('referrers_brands', function (Blueprint $table) {
            $table->dropColumn(['referrer_name', 'referrer_email', 'referrer_phone']);
        });
    }
};
