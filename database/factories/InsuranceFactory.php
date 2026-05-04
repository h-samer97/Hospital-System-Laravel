<?php

namespace Database\Factories;

use App\Models\Insurance;
use Illuminate\Database\Eloquent\Factories\Factory;

class InsuranceFactory extends Factory
{
    protected $model = Insurance::class;

    public function definition(): array
    {
        $companies = [
            'AXA Insurance',
            'National Insurance Company',
            'United Insurance',
            'Prime Insurance',
            'Global Health Insurance',
            'MediCare Insurance',
            'HealthPlus',
            'SafeCare Insurance',
        ];

        return [
            'insurance_code' => $this->faker->unique()->bothify('INS-####'),
            'name' => $this->faker->randomElement($companies),
            'discount_percentage' => $this->faker->randomFloat(2, 5, 30),
            'company_rate' => $this->faker->randomFloat(2, 10, 100),
            'status' => $this->faker->boolean(90),
        ];
    }
}
