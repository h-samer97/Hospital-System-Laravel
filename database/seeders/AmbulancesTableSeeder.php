<?php

namespace Database\Seeders;

use App\Models\Ambulances;
use Illuminate\Database\Seeder;

class AmbulancesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Ambulances::factory()->count(12)->create();
  }
}
