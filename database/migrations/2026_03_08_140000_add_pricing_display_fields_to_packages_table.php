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
        Schema::table('packages', function (Blueprint $table) {
            $table->string('short_description')->nullable()->after('description');
            $table->string('domain_structure')->nullable()->after('short_description');
            $table->string('badge')->nullable()->after('domain_structure');
            $table->string('cta_label')->default('Choose plan')->after('badge');
            $table->unsignedTinyInteger('sort_order')->default(0)->after('cta_label');
            $table->boolean('custom_landing_page')->default(false)->after('custom_branding');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'short_description',
                'domain_structure',
                'badge',
                'cta_label',
                'sort_order',
                'custom_landing_page',
            ]);
        });
    }
};
