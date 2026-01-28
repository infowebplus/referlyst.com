<?php

namespace Database\Factories;

use App\Models\Addon;
use App\Models\BrandReferral;
use App\Models\Company;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $model = fake()->randomElement([
            Package::class,
            Addon::class,
        ]);

        return [
            'user_id' => User::factory(),
            'company_id' => Company::factory(),
            'brand_id' => BrandReferral::factory(),
            'date' => fake()->date(),
            'model_id' => $model::factory(),
            'model_type' => $model,
            'price' => fake()->randomFloat(2, 10, 1000),
            'paid' => fake()->numberBetween(0, 1),
            'paid_date' => fake()->optional()->date(),
            'paid_response' => fake()->optional()->text(),
        ];
    }
}
