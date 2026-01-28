<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'price' => fake()->randomFloat(2, 9, 999),
            'description' => fake()->paragraphs(3, true),
            'email_notifications' => fake()->numberBetween(0, 1),
            'no_of_referrers' => fake()->numberBetween(0, 100),
            'no_of_referrers_brands' => fake()->numberBetween(0, 50),
            'commission_tracking' => fake()->numberBetween(0, 1),
            'export_csv' => fake()->numberBetween(0, 1),
            'custom_branding' => fake()->numberBetween(0, 1),
        ];
    }
}
