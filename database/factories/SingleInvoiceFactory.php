<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Section;
use App\Models\SingleInvioce;
use App\Models\SingleServices;
use Illuminate\Database\Eloquent\Factories\Factory;

class SingleInvoiceFactory extends Factory
{
    protected $model = SingleInvioce::class;

    public function definition(): array
    {
        $price = $this->faker->randomFloat(2, 10, 500);
        $discount = $this->faker->randomFloat(2, 0, $price * 0.2);
        $taxRates = ['0%', '5%', '10%', '15%'];
        $taxRate = $taxRates[array_rand($taxRates)];
        $taxValue = round(($price - $discount) * ((int) trim($taxRate, '%') / 100), 2);
        $totalWithTax = round($price - $discount + $taxValue, 2);

        return [
            'invoice_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'patient_id' => Patient::inRandomOrder()->first()?->id ?? Patient::factory(),
            'doctor_id' => Doctor::inRandomOrder()->first()?->id ?? Doctor::factory(),
            'section_id' => Section::inRandomOrder()->first()?->id ?? Section::factory(),
            'service_id' => SingleServices::inRandomOrder()->first()?->id ?? SingleServices::factory(),
            'price' => $price,
            'discount_value' => $discount,
            'tax_rate' => $taxRate,
            'tax_value' => $taxValue,
            'total_with_tax' => $totalWithTax,
            'type' => $this->faker->randomElement([1, 2, 3]),
        ];
    }
}
