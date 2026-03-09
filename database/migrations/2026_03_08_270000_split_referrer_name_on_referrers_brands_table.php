<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referrers_brands', function (Blueprint $table) {
            $table->renameColumn('referrer_name', 'referrer_first_name');
            $table->string('referrer_surname')->nullable()->after('referrer_first_name');
        });
    }

    public function down(): void
    {
        Schema::table('referrers_brands', function (Blueprint $table) {
            $table->dropColumn('referrer_surname');
            $table->renameColumn('referrer_first_name', 'referrer_name');
        });
    }
};
