<?php

namespace Database\Seeders;

use App\Models\SingleInvioce;
use Illuminate\Database\Seeder;

class SingleInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        SingleInvioce::factory()->count(20)->create();
    }
}
