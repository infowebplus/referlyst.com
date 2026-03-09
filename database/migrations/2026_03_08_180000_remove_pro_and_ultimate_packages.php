<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Remove legacy Pro and Ultimate packages; only Free, Starter, Growth are used.
     */
    public function up(): void
    {
        DB::table('packages')->whereIn('slug', ['pro', 'ultimate'])->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot restore deleted packages; no-op.
    }
};
