<?php

namespace Database\Seeders;

use App\Models\Ambulances;
use Illuminate\Database\Seeder;

class AmbulancesSeeder extends Seeder
{
    public function run(): void
    {
        Ambulances::factory()->count(10)->create();
    }
}
