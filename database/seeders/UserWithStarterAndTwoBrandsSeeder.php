<?php

namespace Database\Seeders;

use App\CommissionType;
use App\Models\Addon;
use App\Models\BrandPackage;
use App\Models\BrandReferral;
use App\Models\Company;
use App\Models\Package;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserWithStarterAndTwoBrandsSeeder extends Seeder
{
    /**
     * Creates a referral user with one company, 2 brands:
     * - Brand 1 (Acme Coffee): Starter package + API Access addon + custom colors
     * - Brand 2 (Nordic Design): Free package
     * Each brand has demo services.
     *
     * Run after AdminAndPackagesSeeder and AddonsSeeder. Usage: php artisan db:seed --class=UserWithStarterAndTwoBrandsSeeder
     */
    public function run(): void
    {
        $starterPackage = Package::query()->where('slug', 'starter')->first();
        $freePackage = Package::query()->where('slug', 'free')->first()
            ?? Package::query()->where('default_package', true)->first();

        if (! $starterPackage) {
            $this->command->warn('Starter package not found. Run AdminAndPackagesSeeder first.');

            return;
        }

        if (! $freePackage) {
            $this->command->warn('Free package not found. Run AdminAndPackagesSeeder first.');

            return;
        }

        $user = User::query()->where('email', 'starter-demo@referlyst.demo')->first();

        if ($user) {
            $user->update(['password' => 'password']);
            $this->ensureStarterAndFreeBrands($user->company_id, $starterPackage, $freePackage);

            return;
        }

        $company = Company::create([
            'name' => 'Starter Two Brands Demo',
        ]);

        $user = User::create([
            'name' => 'Starter',
            'surname' => 'Demo',
            'email' => 'starter-demo@referlyst.demo',
            'email_verified_at' => now(),
            'password' => 'password',
            'company_id' => $company->id,
            'type' => 'referral',
        ]);

        $brand1 = BrandReferral::create([
            'company_id' => $company->id,
            'name' => 'Acme Coffee',
            'logo' => null,
            'website' => 'https://acmecoffee.example.com',
            'terms_and_conditions' => null,
            'ca' => null,
            'subdomain_for_landing_pages' => 'acme-coffee',
            'smtp_host' => null,
            'smtp_port' => null,
            'smtp_username' => null,
            'smtp_password' => null,
            'smtp_security' => null,
            'colors' => [
                'primary' => '#2d5016',
                'secondary' => '#5a8c2e',
            ],
        ]);

        $brand2 = BrandReferral::create([
            'company_id' => $company->id,
            'name' => 'Nordic Design',
            'logo' => null,
            'website' => 'https://nordicdesign.example.com',
            'terms_and_conditions' => null,
            'ca' => null,
            'subdomain_for_landing_pages' => 'nordic-design',
            'smtp_host' => null,
            'smtp_port' => null,
            'smtp_username' => null,
            'smtp_password' => null,
            'smtp_security' => null,
            'colors' => [
                'primary' => '#0d3b66',
                'secondary' => '#1a5f8a',
            ],
        ]);

        BrandPackage::create([
            'brand_id' => $brand1->id,
            'model_id' => $starterPackage->id,
            'model_type' => Package::class,
            'price' => $starterPackage->price,
            'purchase_date' => now(),
            'expiry_date' => now()->addYear(),
        ]);

        $apiAccessAddon = Addon::query()->where('name', 'API Access')->first();
        if ($apiAccessAddon) {
            BrandPackage::create([
                'brand_id' => $brand1->id,
                'model_id' => $apiAccessAddon->id,
                'model_type' => Addon::class,
                'price' => $apiAccessAddon->price,
                'purchase_date' => now(),
                'expiry_date' => now()->addYear(),
            ]);
        }

        BrandPackage::create([
            'brand_id' => $brand2->id,
            'model_id' => $freePackage->id,
            'model_type' => Package::class,
            'price' => $freePackage->price,
            'purchase_date' => now(),
            'expiry_date' => now()->addYear(),
        ]);

        $this->seedServicesForBrand($brand1, ['Coffee subscription', 'Barista training', 'Equipment rental', 'Catering']);
        $this->seedServicesForBrand($brand2, ['Logo design', 'Brand identity', 'Web design', 'Print design']);

        $this->command->info('Created:');
        $this->command->info('  Company: '.$company->name.' (id '.$company->id.')');
        $this->command->info('  User: '.$user->email.' / password (type: referral)');
        $this->command->info('  Brand 1: '.$brand1->name.' (id '.$brand1->id.') — Starter, colors: '.json_encode($brand1->colors).($apiAccessAddon ? ', API Access addon' : ''));
        $this->command->info('  Brand 2: '.$brand2->name.' (id '.$brand2->id.') — Free');
        $this->command->info('  Services created for both brands.');
    }

    /**
     * @param  array<int, string>  $names
     */
    private function seedServicesForBrand(BrandReferral $brand, array $names): void
    {
        foreach ($names as $name) {
            Service::firstOrCreate(
                [
                    'brand_id' => $brand->id,
                    'name' => $name,
                ],
                [
                    'commission_type' => fake()->randomElement(CommissionType::cases()),
                    'commission' => fake()->randomFloat(2, 5, 50),
                    'texts' => fake()->optional(0.7)->paragraph(),
                    'pictures' => null,
                ]
            );
        }
    }

    /**
     * Ensure the company has one brand with Starter and one with Free (by id order).
     */
    private function ensureStarterAndFreeBrands(int $companyId, Package $starterPackage, Package $freePackage): void
    {
        $brands = BrandReferral::query()->where('company_id', $companyId)->orderBy('id')->get();
        if ($brands->count() < 2) {
            $this->command->warn('Company has fewer than 2 brands. Create brands first or re-run full seeder.');

            return;
        }

        $brand1 = $brands[0];
        $brand2 = $brands[1];

        $this->setBrandActivePackage($brand1, $starterPackage);
        $this->setBrandActivePackage($brand2, $freePackage);
        $this->ensureApiAccessAddonOnBrand($brand1);

        $this->command->info('Updated packages: '.$brand1->name.' → Starter, '.$brand2->name.' → Free.');
    }

    private function ensureApiAccessAddonOnBrand(BrandReferral $brand): void
    {
        $addon = Addon::query()->where('name', 'API Access')->first();
        if (! $addon) {
            return;
        }
        $alreadyHas = BrandPackage::query()
            ->where('brand_id', $brand->id)
            ->where('model_type', Addon::class)
            ->where('model_id', $addon->id)
            ->where(function ($q) {
                $q->whereNull('expiry_date')->orWhere('expiry_date', '>', now());
            })
            ->exists();
        if ($alreadyHas) {
            return;
        }
        BrandPackage::create([
            'brand_id' => $brand->id,
            'model_id' => $addon->id,
            'model_type' => Addon::class,
            'price' => $addon->price,
            'purchase_date' => now(),
            'expiry_date' => now()->addYear(),
        ]);
        $this->command->info('Attached API Access addon to '.$brand->name.'.');
    }

    private function setBrandActivePackage(BrandReferral $brand, Package $package): void
    {
        BrandPackage::query()
            ->where('brand_id', $brand->id)
            ->where('model_type', Package::class)
            ->where(function ($q) {
                $q->whereNull('expiry_date')->orWhere('expiry_date', '>', now());
            })
            ->update(['expiry_date' => now()]);

        BrandPackage::create([
            'brand_id' => $brand->id,
            'model_id' => $package->id,
            'model_type' => Package::class,
            'price' => $package->price,
            'purchase_date' => now(),
            'expiry_date' => now()->addYear(),
        ]);
    }
}
