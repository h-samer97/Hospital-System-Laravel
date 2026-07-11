<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PaymentAccount;
use App\Models\Patients;

class PaymentAccountFactory extends Factory
{
  protected $model = PaymentAccount::class;

  public function definition()
  {
    $patient = Patients::inRandomOrder()->first() ?? Patients::factory()->create();

    return [
      'date' => $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
      'patient_id' => $patient->id,
      'description' => $this->faker->sentence(6),
      'amount' => $this->faker->randomFloat(2, 50, 10000),
    ];
  }
}
