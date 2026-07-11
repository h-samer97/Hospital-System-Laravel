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
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Section::class;
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
            'name' => $sections[array_rand($sections)],
            'is_active' => true,
        ];
    }
}
