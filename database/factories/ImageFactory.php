<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Doctor;

/**
 * @extends Factory<Image>
 */
class ImageFactory extends Factory
{
   

    public function definition(): array
    {
        $doctor = Doctor::inRandomOrder()->first();

        return [
             'filename'      => $this->faker->randomElement(['1.jpg','2.jpg','3.jpg','4.jpg']),
            'imageable_id'   => $doctor->id,
            'imageable_type' => Doctor::class,
        ];
    }
}
