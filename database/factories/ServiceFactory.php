<?php

namespace Database\Factories;

use App\CommissionType;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'commission_type' => fake()->randomElement(CommissionType::cases()),
            'commission' => fake()->randomFloat(2, 0, 100),
            'texts' => fake()->optional(0.7)->paragraphs(3, true),
            'pictures' => fake()->optional(0.5)->randomElements(
                ['services/a.jpg', 'services/b.jpg', 'services/c.jpg'],
                fake()->numberBetween(0, 3)
            ),
        ];
    }
}
