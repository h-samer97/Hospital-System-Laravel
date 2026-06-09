<?php

namespace Database\Factories;

use App\Models\SingleInvoices;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patients;
use App\Models\Doctor;
use App\Models\Section;
use App\Models\Service;

/**
 * @extends Factory<SingleInvoices>
 */
class SingleInvoicesFactory extends Factory
{
  protected $model = SingleInvoices::class;

  public function definition(): array
  {
    $patient = Patients::inRandomOrder()->first() ?? Patients::factory()->create();
    $doctor = Doctor::inRandomOrder()->first() ?? Doctor::factory()->create();
    $section = Section::inRandomOrder()->first() ?? Section::factory()->create();
    $service = Service::inRandomOrder()->first() ?? Service::factory()->create();

    $price = $this->faker->randomFloat(2, 20, 1500);
    $discount = $this->faker->randomFloat(2, 0, 0.3) * $price;
    $taxRate = $this->faker->randomElement([0, 0.05, 0.1, 0.14]);
    $taxValue = ($price - $discount) * $taxRate;
    $total = ($price - $discount) + $taxValue;

    return [
      'invoice_date'   => $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
      'patient_id'     => $patient->id,
      'doctor_id'      => $doctor->id,
      'section_id'     => $section->id,
      'service_id'     => $service->id,
      'price'          => $price,
      'discount_value' => round($discount, 2),
      'tax_rate'       => $taxRate,
      'tax_value'      => round($taxValue, 2),
      'total_with_tax' => round($total, 2),
      'type'           => $this->faker->randomElement(['cash', 'deferred']),
    ];
  }
}
