<?php

namespace Database\Factories;

use App\Models\Insurance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Insurance>
 */
class InsuranceFactory extends Factory
{
  protected $model = Insurance::class;

  public function definition(): array
  {
    return [
      'name' => $this->faker->unique()->company(),
      'insurance_code' => strtoupper('INS-' . $this->faker->unique()->bothify('????-####')),
      'discount_percentage' => $this->faker->randomFloat(2, 0, 50),
      'company_rate' => $this->faker->randomFloat(2, 100, 500),
      'is_active' => $this->faker->boolean(90),
      'note' => $this->faker->optional(0.5)->sentence(6),
    ];
  }
}
