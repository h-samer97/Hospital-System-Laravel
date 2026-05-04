<?php

namespace Database\Factories;

use App\Models\SingleServices;
use Illuminate\Database\Eloquent\Factories\Factory;

class SingleServicesFactory extends Factory
{
    protected $model = SingleServices::class;

    public function definition(): array
    {
        $services = [
            'General Checkup',
            'Blood Test',
            'X-Ray',
            'Ultrasound',
            'ECG',
            'CT Scan',
            'MRI Scan',
            'Laboratory Test',
            'Vaccination',
            'Dental Cleaning',
            'Eye Examination',
            'Physical Therapy',
        ];

        return [
            'name' => $this->faker->randomElement($services),
            'price' => $this->faker->randomFloat(2, 10, 500),
        ];
    }
}
