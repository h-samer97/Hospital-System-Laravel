<?php

namespace Database\Factories;

use App\Models\Ambulances;
use Illuminate\Database\Eloquent\Factories\Factory;

class AmbulancesFactory extends Factory
{
    protected $model = Ambulances::class;

    public function definition(): array
    {
        $carModels = ['Mercedes Sprinter', 'Ford Transit', 'Iveco', 'GMC Savana', 'Chevrolet Express'];
        $carTypes = [1, 2, 3]; // Basic, Advanced, ICU

        return [
            'car_number' => $this->faker->unique()->bothify('AMB-####'),
            'car_model' => $this->faker->randomElement($carModels),
            'car_year_made' => $this->faker->year(),
            'driver_license_number' => $this->faker->unique()->bothify('DL-#########'),
            'driver_phone' => $this->faker->phoneNumber(),
            'driver_name' => $this->faker->name(),
            'is_available' => $this->faker->boolean(85),
            'car_type' => $this->faker->randomElement($carTypes),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
