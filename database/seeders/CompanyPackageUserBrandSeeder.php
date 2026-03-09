<?php

namespace Database\Seeders;

use App\CommissionType;
use App\Models\BrandReferral;
use App\Models\Company;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanyPackageUserBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create company (packages Free, Starter, Growth come from AdminAndPackagesSeeder)
        $company = Company::firstOrCreate(
            ['name' => 'Referlyst Demo Company'],
            ['name' => 'Referlyst Demo Company']
        );

        // 2. Create 3 users with random passwords (updateOrCreate so re-run shows new passwords)
        $password1 = Str::random(16);
        $password2 = Str::random(16);
        $password3 = Str::random(16);

        User::query()->updateOrCreate(
            ['email' => 'gabriel.fieraru@gmail.com'],
            [
                'name' => 'Gabriel',
                'surname' => 'Fieraru',
                'email_verified_at' => now(),
                'password' => $password1,
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
                'password' => $password2,
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
                'password' => $password3,
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
