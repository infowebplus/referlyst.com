<?php

namespace Database\Seeders;

use App\CommissionType;
use App\Models\BrandPackage;
use App\Models\BrandReferral;
use App\Models\Company;
use App\Models\Package;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserWithFreePackageAndDefaultBrandColorsSeeder extends Seeder
{
    /**
     * Creates (or updates) a referral user with company, one brand that has the Free package
     * purchased and colors taken from the default brand (first brand with colors, or first brand).
     * Ensures the brand has demo services for testing.
     */
    public function run(): void
    {
        $freePackage = Package::query()->where('slug', 'free')->first()
            ?? Package::query()->where('default_package', true)->first();

        if (! $freePackage) {
            $this->command->warn('Free package not found. Run AdminAndPackagesSeeder first.');

            return;
        }

        $defaultBrand = BrandReferral::query()
            ->get()
            ->first(fn (BrandReferral $b): bool => is_array($b->colors) && ! empty($b->colors));

        if (! $defaultBrand) {
            $defaultBrand = BrandReferral::query()->first();
        }

        $defaultColors = null;
        if ($defaultBrand && $defaultBrand->colors !== null && is_array($defaultBrand->colors) && ! empty($defaultBrand->colors)) {
            $defaultColors = $defaultBrand->colors;
        } else {
            $defaultColors = ['primary' => \App\Services\ReferralBrandColors::REFERLYST_PRIMARY];
            if ($defaultBrand) {
                $defaultBrand->update(['colors' => $defaultColors]);
                $this->command->info('Set default brand (id '.$defaultBrand->id.') colors to Referlyst primary.');
            }
        }

        $user = User::query()->where('email', 'freeuser@referlyst.demo')->first();

        if ($user) {
            $company = $user->company;
            if (! $company) {
                $this->command->warn('User exists but has no company. Create company and brand manually or use another email.');

                return;
            }
            $brand = $company->brandReferrals()->where('subdomain_for_landing_pages', 'free-demo')->first();
            if (! $brand) {
                $brand = $company->brandReferrals()->first();
            }
            if ($brand) {
                $brand->update(['colors' => $defaultColors]);
                $hasFree = BrandPackage::query()
                    ->where('brand_id', $brand->id)
                    ->where('model_type', Package::class)
                    ->where('model_id', $freePackage->id)
                    ->where(function ($q) {
                        $q->whereNull('expiry_date')->orWhere('expiry_date', '>', now());
                    })
                    ->exists();
                if (! $hasFree) {
                    BrandPackage::create([
                        'brand_id' => $brand->id,
                        'model_id' => $freePackage->id,
                        'model_type' => Package::class,
                        'price' => $freePackage->price,
                        'purchase_date' => now(),
                        'expiry_date' => now()->addYear(),
                    ]);
                }
                $this->seedServicesForBrand($brand, ['Consulting', 'Training', 'Support']);
                $this->command->info('Updated existing user/brand. Colors from default brand. Free package allocated. Services ensured.');
            }

            return;
        }

        $company = Company::create([
            'name' => 'Free Package Demo Company',
        ]);

        $user = User::create([
            'name' => 'Free',
            'surname' => 'User',
            'email' => 'freeuser@referlyst.demo',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'company_id' => $company->id,
            'type' => 'referral',
        ]);

        $brand = BrandReferral::create([
            'company_id' => $company->id,
            'name' => 'Free Demo Brand',
            'logo' => null,
            'website' => 'https://example.com',
            'terms_and_conditions' => null,
            'ca' => null,
            'subdomain_for_landing_pages' => 'free-demo',
            'smtp_host' => null,
            'smtp_port' => null,
            'smtp_username' => null,
            'smtp_password' => null,
            'smtp_security' => null,
            'colors' => $defaultColors,
        ]);

        BrandPackage::create([
            'brand_id' => $brand->id,
            'model_id' => $freePackage->id,
            'model_type' => Package::class,
            'price' => $freePackage->price,
            'purchase_date' => now(),
            'expiry_date' => now()->addYear(),
        ]);

        $this->seedServicesForBrand($brand, ['Consulting', 'Training', 'Support']);

        $this->command->info('Created:');
        $this->command->info('  Company: '.$company->name.' (id '.$company->id.')');
        $this->command->info('  User: '.$user->email.' / password (type: referral)');
        $this->command->info('  Brand: '.$brand->name.' (id '.$brand->id.', subdomain: '.$brand->subdomain_for_landing_pages.')');
        $this->command->info('  Free package allocated. Colors from default brand: '.json_encode($defaultColors));
        $this->command->info('  Services created for brand.');
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
}
