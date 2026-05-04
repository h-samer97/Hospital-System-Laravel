<?php

namespace Database\Seeders;

use App\Models\SingleServices;
use Illuminate\Database\Seeder;

class SingleServicesSeeder extends Seeder
{
    public function run(): void
    {
        SingleServices::factory()->count(12)->create();
    }
}
