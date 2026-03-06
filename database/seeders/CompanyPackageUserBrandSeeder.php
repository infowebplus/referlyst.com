<?php

namespace Database\Seeders;

use App\CommissionType;
use App\Models\BrandReferral;
use App\Models\Company;
use App\Models\Package;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CompanyPackageUserBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create company
        $company = Company::firstOrCreate(
            ['name' => 'Referlyst Demo Company'],
            ['name' => 'Referlyst Demo Company']
        );

        // 2. Create 3 packages: free, pro, ultimate
        $free = Package::firstOrCreate(
            ['name' => 'Free'],
            [
                'default_package' => true,
                'price' => 0,
                'description' => 'Free tier with basic referral features.',
                'email_notifications' => true,
                'no_of_referrers' => 5,
                'no_of_referrers_brands' => 2,
                'commission_tracking' => true,
                'export_csv' => false,
                'custom_branding' => false,
            ]
        );

        Package::firstOrCreate(
            ['name' => 'Pro'],
            [
                'default_package' => false,
                'price' => 49.00,
                'description' => 'Pro tier with more referrers and export.',
                'email_notifications' => true,
                'no_of_referrers' => 25,
                'no_of_referrers_brands' => 10,
                'commission_tracking' => true,
                'export_csv' => true,
                'custom_branding' => false,
            ]
        );

        Package::firstOrCreate(
            ['name' => 'Ultimate'],
            [
                'default_package' => false,
                'price' => 149.00,
                'description' => 'Ultimate tier with unlimited referrers and custom branding.',
                'email_notifications' => true,
                'no_of_referrers' => 999,
                'no_of_referrers_brands' => 999,
                'commission_tracking' => true,
                'export_csv' => true,
                'custom_branding' => true,
            ]
        );

        // 3. Create 3 users with random passwords (updateOrCreate so re-run shows new passwords)
        $password1 = Str::random(16);
        $password2 = Str::random(16);
        $password3 = Str::random(16);

        User::query()->updateOrCreate(
            ['email' => 'gabriel.fieraru@gmail.com'],
            [
                'name' => 'Gabriel',
                'surname' => 'Fieraru',
                'email_verified_at' => now(),
                'password' => Hash::make($password1),
                'company_id' => null,
                'type' => 'admin',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'contact@gabrielfieraru.com'],
            [
                'name' => 'Contact',
                'surname' => 'Fieraru',
                'email_verified_at' => now(),
                'password' => Hash::make($password2),
                'company_id' => $company->id,
                'type' => 'referral',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'georgebarbudev@gmail.com'],
            [
                'name' => 'George',
                'surname' => 'Barbudev',
                'email_verified_at' => now(),
                'password' => Hash::make($password3),
                'company_id' => null,
                'type' => 'admin',
            ]
        );

        $this->command->info('User passwords (save these):');
        $this->command->info('  gabriel.fieraru@gmail.com: '.$password1);
        $this->command->info('  contact@gabrielfieraru.com: '.$password2);
        $this->command->info('  georgebarbudev@gmail.com: '.$password3);

        // 4. Create brand for company with subdomain "kkt"
        $brand = BrandReferral::firstOrCreate(
            ['subdomain_for_landing_pages' => 'testref'],
            [
                'company_id' => $company->id,
                'name' => 'KKT Brand',
                'logo' => null,
                'website' => 'https://www.google.com',
                'terms_and_conditions' => null,
                'ca' => null,
                'smtp_host' => null,
                'smtp_port' => null,
                'smtp_username' => null,
                'smtp_password' => null,
                'smtp_security' => null,
                'colors' => null,
            ]
        );

        // 5. Create 3–4 services for that brand
        $serviceNames = ['Consulting', 'Training', 'Support', 'Implementation'];
        foreach ($serviceNames as $name) {
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
