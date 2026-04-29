<?php

namespace Database\Factories;

use App\Models\Appointment;
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
            'price' => $this->faker->randomFloat(2, 0, 50),
            'section_id' => Section::inRandomOrder()->first()->id,
            'doctorappointments' => Appointment::all()->random()->id,
            'status' => 1,
            'image' => $this->faker->imageUrl(640, 480, 'doctors', true),
        ];
    }
}
