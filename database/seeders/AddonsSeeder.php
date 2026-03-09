<?php

namespace Database\Seeders;

use App\Models\Addon;
use Illuminate\Database\Seeder;

class AddonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates the 3 addons: Custom domain, API access to form, Extra Referrers Number.
     * All have annual validity.
     */
    public function run(): void
    {
        $addonNames = ['API Access', 'Extra Referrers', 'Extra custom domain'];

        $addons = [
            [
                'name' => 'API Access',
                'price' => 19,
                'description' => 'API access for embedding the referral form into your application.',
                'validity_period' => 'annual',
            ],
            [
                'name' => 'Extra Referrers',
                'price' => 9,
                'description' => '+500 referrers.',
                'validity_period' => 'annual',
            ],
            [
                'name' => 'Extra custom domain',
                'price' => 9,
                'description' => 'Extra custom domain.',
                'validity_period' => 'annual',
            ],
        ];

        Addon::query()->whereNotIn('name', $addonNames)->delete();

        foreach ($addons as $addon) {
            Addon::query()->updateOrCreate(
                ['name' => $addon['name']],
                $addon
            );
        }

        $this->command->info('Addons created/updated: API Access (€19/year), Extra Referrers (€9/year), Extra custom domain (€9/year).');
    }
}
