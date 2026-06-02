<?php

namespace Database\Factories;

use App\Models\Ambulances;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ambulances>
 */
class AmbulancesFactory extends Factory
{
  protected $model = Ambulances::class;

  public function definition(): array
  {
    return [
      'car_number' => $this->faker->unique()->numerify('###-####'),
      'car_model' => $this->faker->randomElement([
        'Mercedes Sprinter',
        'Ford Transit',
        'Toyota HiAce',
        'Nissan NV350',
        'Hyundai H1',
        'Chevrolet Express',
        'GMC Savana',
        'Dodge Ram 1500'
      ]),
      'car_year_made' => $this->faker->numberBetween(2005, date('Y')),
      'driver_name' => $this->faker->name(),
      'driver_license_number' => strtoupper($this->faker->bothify('??######')),
      'is_available' => $this->faker->boolean(80),
      'status' => $this->faker->boolean(90),
      'notes' => $this->faker->optional(0.6)->sentence(),
    ];
  }
}
