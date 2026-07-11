<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Section;
use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{


    public function definition(): array
    {
        // $daysAR = ['السبت','الأحد','الاثنين','الثلاثاء','الأربعاء','الخميس'];
        $faker = \Faker\Factory::create();

        return [
            'name' => $faker->name(),
            'email' => $faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'phone' => $faker->phoneNumber(),
            'price' => $faker->randomFloat(2, 50, 500),
            'is_active' => true,
            'section_id' => Section::factory(),
            'email_verified_at' => now(),
        ];
    }
}
