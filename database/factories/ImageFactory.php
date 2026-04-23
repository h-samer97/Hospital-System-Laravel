<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Image>
 */
class ImageFactory extends Factory
{
    protected $model = Image::class;
    public function definition(): array
    {
        return [
            'imageable_type' => $this->faker->randomElement(['App\Models\Doctor', 'App\Models\Section', 'App\Models\Patient', 'App\Models\Nurse', 'App\Models\Admin']),
            'imageable_id' => $this->faker->randomNumber(5),
            'filename' => $this->faker->imageUrl(),
        ];
    }
}
