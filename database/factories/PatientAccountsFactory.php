<?php

namespace Database\Factories;

use App\Models\PatientAccounts;
use App\Models\Patients;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientAccountsFactory extends Factory
{
  protected $model = PatientAccounts::class;

  public function definition()
  {
    $patient = Patients::inRandomOrder()->first() ?? Patients::factory()->create();

    return [
      'date' => $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
      'patient_id' => $patient->id,
      'single_invoice_id' => null,
      'debit' => $this->faker->randomFloat(2, 0, 1000),
      'credit' => $this->faker->randomFloat(2, 0, 1000),
      'notes' => $this->faker->sentence(),
    ];
  }
}
