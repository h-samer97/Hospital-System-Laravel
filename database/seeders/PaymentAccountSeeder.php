<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentAccount;

class PaymentAccountSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    PaymentAccount::factory()->count(200)->create();
  }
}
