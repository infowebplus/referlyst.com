<?php

namespace Database\Factories;

use App\Models\Addon;
use App\Models\BrandReferral;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BrandPackage>
 */
class BrandPackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $modelType = fake()->randomElement([Package::class, Addon::class]);
        $model = $modelType::factory()->create();

        return [
            'brand_id' => BrandReferral::factory(),
            'model_id' => $model->id,
            'model_type' => $modelType,
            'price' => fake()->randomFloat(2, 9, 999),
            'purchase_date' => fake()->date(),
            'expiry_date' => fake()->optional()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'details' => fake()->optional()->randomElement([
                null,
                ['notes' => fake()->sentence()],
                ['notes' => fake()->sentence(), 'discount' => fake()->randomFloat(2, 0, 50)],
            ]),
        ];
    }
}
