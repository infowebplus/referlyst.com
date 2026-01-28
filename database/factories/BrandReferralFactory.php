<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BrandReferral>
 */
class BrandReferralFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'logo' => null,
            'website' => fake()->url(),
            'terms_and_conditions' => fake()->optional()->url(),
            'ca' => fake()->optional()->url(),
            'subdomain_for_landing_pages' => fake()->optional()->unique()->slug(2),
            'smtp_host' => null,
            'smtp_port' => null,
            'smtp_username' => null,
            'smtp_password' => null,
            'smtp_security' => null,
            'colors' => null,
        ];
    }
}
