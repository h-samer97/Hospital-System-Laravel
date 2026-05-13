<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Section;
use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{
   
    
    public function definition(): array
    {
    // $daysAR = ['السبت','الأحد','الاثنين','الثلاثاء','الأربعاء','الخميس'];
    $days = ['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday'];
    $idx = array_rand($days);
    
            return [
                'name' => $this->faker->name(),
                'appointments' => $this->faker->sentence(),
                'email' => $this->faker->unique()->safeEmail(),
                'password' => bcrypt('password'),
                'phone' => $this->faker->phoneNumber(),
                'price' => $this->faker->randomFloat(2, 50, 500),
                'is_active' => true,
                'section_id' => Section::factory(),
                'email_verified_at' => now(),
            ];

    }
}
