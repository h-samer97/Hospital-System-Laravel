<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Receipt;

class ReceiptSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Receipt::factory()->count(200)->create();
  }
}
