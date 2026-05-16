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
            ['ar' => 'طوارئ',        'en' => 'Emergency'],
            ['ar' => 'باطنة',         'en' => 'Internal Medicine'],
            ['ar' => 'جراحة',         'en' => 'Surgery'],
            ['ar' => 'أطفال',         'en' => 'Pediatrics'],
            ['ar' => 'عيون',          'en' => 'Ophthalmology'],
            ['ar' => 'نساء وتوليد',   'en' => 'Gynecology'],
            ['ar' => 'عظام',          'en' => 'Orthopedics'],
            ['ar' => 'أسنان',         'en' => 'Dentistry'],
        ];

        $pick = $this->faker->unique()->randomElement($sections);
        return [
            'name' => $pick,
            'is_active' => true,
        ];
    }
}
