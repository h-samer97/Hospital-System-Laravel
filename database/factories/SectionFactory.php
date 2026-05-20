<?php

namespace Database\Factories;

use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Section>
 */
class SectionFactory extends Factory
{
    public function definition(): array
    {
         $sections = [
            'طوارئ',
            'باطنة',
            'جراحة',
            'أطفال',
            'عيون',
            'نساء وتوليد',
            'عظام',
            'أسنان',
        ];

        return [
            'name' => $this->faker->randomElement($sections),
            'is_active' => true,
        ];
    }
}
