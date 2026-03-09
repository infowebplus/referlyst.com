<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminAndPackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates an admin user and the Free, Starter, Growth packages.
     */
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@referlyst.com');
        $adminPassword = env('ADMIN_PASSWORD', 'password');

        User::query()->updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Admin',
                'surname' => 'User',
                'email_verified_at' => now(),
                'password' => Hash::make($adminPassword),
                'company_id' => null,
                'type' => 'admin',
            ]
        );

        $this->command->info('Admin user: '.$adminEmail.' / '.$adminPassword);
        $this->command->info('Log in at: /sadm');

        Package::query()->updateOrCreate(
            ['slug' => 'free'],
            [
                'name' => 'Free',
                'default_package' => true,
                'price' => 0,
                'description' => 'Free tier with basic referral features.',
                'short_description' => 'Perfect for testing referral marketing.',
                'domain_structure' => 'referlyst.com/brand',
                'badge' => null,
                'cta_label' => 'Start for free',
                'sort_order' => 1,
                'email_notifications' => true,
                'no_of_referrers' => 10,
                'no_of_referrers_brands' => 1,
                'commission_tracking' => true,
                'export_csv' => true,
                'custom_branding' => false,
                'custom_landing_page' => false,
                'limitations' => ['Referlyst branding on referral pages'],
            ]
        );

        Package::query()->updateOrCreate(
            ['slug' => 'starter'],
            [
                'name' => 'Starter',
                'default_package' => false,
                'price' => 12.00,
                'description' => 'Best for growing businesses launching their first referral programs.',
                'short_description' => 'Best for growing businesses. Clear reason to upgrade.',
                'domain_structure' => 'brand.referlyst.com',
                'badge' => 'Most Popular',
                'cta_label' => 'Choose plan',
                'sort_order' => 2,
                'email_notifications' => true,
                'no_of_referrers' => 250,
                'no_of_referrers_brands' => 3,
                'commission_tracking' => true,
                'export_csv' => true,
                'custom_branding' => true,
                'custom_landing_page' => false,
            ]
        );

        Package::query()->updateOrCreate(
            ['slug' => 'growth'],
            [
                'name' => 'Growth',
                'default_package' => false,
                'price' => 29.00,
                'description' => 'Full referral platform with custom domain and branded experience.',
                'short_description' => 'Unlimited brands & referrers, custom domain, priority support.',
                'domain_structure' => 'Custom domain',
                'badge' => null,
                'cta_label' => 'Choose plan',
                'sort_order' => 3,
                'email_notifications' => true,
                'no_of_referrers' => 0,
                'no_of_referrers_brands' => 0,
                'commission_tracking' => true,
                'export_csv' => true,
                'custom_branding' => true,
                'custom_landing_page' => true,
            ]
        );

        $this->command->info('Packages created/updated: Free ($0), Starter ($12), Growth ($29).');
    }
}
