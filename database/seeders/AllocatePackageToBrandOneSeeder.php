<?php

namespace Database\Seeders;

use App\Models\BrandPackage;
use App\Models\Package;
use Illuminate\Database\Seeder;

class AllocatePackageToBrandOneSeeder extends Seeder
{
    /**
     * Allocate an active package to Brand 1 so the landing form is visible.
     */
    public function run(): void
    {
        $package = Package::query()
            ->where('no_of_referrers', '>', 0)
            ->first() ?? Package::query()->first();

        if (! $package) {
            $this->command->warn('No package found. Create a package first.');

            return;
        }

        $exists = BrandPackage::query()
            ->where('brand_id', 1)
            ->where('model_type', Package::class)
            ->where('model_id', $package->id)
            ->where(function ($q) {
                $q->whereNull('expiry_date')->orWhere('expiry_date', '>', now());
            })
            ->exists();

        if ($exists) {
            $this->command->info('Brand 1 already has an active allocation for this package.');

            return;
        }

        BrandPackage::create([
            'brand_id' => 1,
            'model_id' => $package->id,
            'model_type' => Package::class,
            'price' => $package->price,
            'purchase_date' => now(),
            'expiry_date' => now()->addYear(),
        ]);

        $this->command->info("Allocated package \"{$package->name}\" (no_of_referrers: {$package->no_of_referrers}) to Brand 1.");
    }
}
