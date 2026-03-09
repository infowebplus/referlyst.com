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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('legal_name')->nullable()->after('name');
            $table->string('registration_number')->nullable()->after('legal_name');
            $table->string('vat_number')->nullable()->after('registration_number');
            $table->string('billing_email')->nullable()->after('vat_number');
            $table->string('billing_address')->nullable()->after('billing_email');
            $table->string('billing_city')->nullable()->after('billing_address');
            $table->string('billing_postal_code')->nullable()->after('billing_city');
            $table->string('billing_country')->nullable()->after('billing_postal_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'legal_name',
                'registration_number',
                'vat_number',
                'billing_email',
                'billing_address',
                'billing_city',
                'billing_postal_code',
                'billing_country',
            ]);
        });
    }
};
