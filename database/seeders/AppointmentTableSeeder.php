<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if(DB::table('appointments')->count() > 0) DB::table('appointments')->delete();

        $appointments = [
            ['name' => 'Saturday'],
            ['name' => 'Sunday'],
            ['name' => 'Monday'],
            ['name' => 'Tuesday'],
            ['name' => 'Wednesday'],
            ['name' => 'Thursday'],
            ['name' => 'Friday'],
        ];

        foreach ($appointments as $a) {
            DB::table('appointments')->insert([
                'name' => $a['name'],
            ]);
        }
    }
}
