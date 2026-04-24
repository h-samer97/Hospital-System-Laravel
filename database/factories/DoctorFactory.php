<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'phone' => $this->faker->phoneNumber(),
            // 'specialization' => $this->faker->randomElement(['Cardiology', 'Dermatology', 'Neurology', 'Pediatrics', 'Psychiatry']),
            'price' => $this->faker->randomFloat(2, 0, 50),
            'section_id' => Section::inRandomOrder()->first()->id,
            'appointments' => implode(',', $this->faker->randomElements(['sat', 'sun', 'mon'], 2)),
            'status' => 1,
        ];
    }
}
