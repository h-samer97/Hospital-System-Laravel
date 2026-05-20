<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Section;

class SectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(Section::count() > 0) return;
        Section::factory()->create([
            'name' => 'طوارئ',
            'is_active' => true,
        ]);
        Section::factory()->create([
            'name' => 'باطنة',
            'is_active' => true,
        ]);
        Section::factory()->create([
            'name' => 'جراحة',
            'is_active' => true,
        ]);
        Section::factory()->create([
            'name' => 'أطفال',
            'is_active' => true,
        ]);
        Section::factory()->create([
            'name' => 'عيون',
            'is_active' => true,
        ]);
        Section::factory()->create([
            'name' => 'نساء وتوليد',
            'is_active' => true,
        ]);
        Section::factory()->create([
            'name' => 'عظام',
            'is_active' => true,
        ]);
        Section::factory()->create([
            'name' => 'أسنان',
            'is_active' => true,
        ]);
    }
}
