<?php

namespace Database\Factories;

use App\Models\Patients;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Patients>
 */
class PatientsFactory extends Factory
{
  protected $model = Patients::class;

  public function definition(): array
  {
    return [
      'name' => $this->faker->name(),
      'email' => $this->faker->unique()->safeEmail(),
      'password' => bcrypt('password'),
      'birth_date' => $this->faker->date('Y-m-d'),
      'phone' => $this->faker->phoneNumber(),
      'gender' => $this->faker->randomElement(['male', 'female']),
      'blood_group' => $this->faker->randomElement(['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+']),
      'address' => $this->faker->address(),
      'is_active' => true,
    ];
  }

  public function inactive(): self
  {
    return $this->state(fn() => [
      'is_active' => false,
    ]);
  }
}
