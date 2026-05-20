<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(3, true),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomElement([50, 100, 150, 200, 300, 500]),
            'is_active' => true
        ];
    }
}
