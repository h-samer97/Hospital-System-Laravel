<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        \DB::table('appointments')->delete();

        $Appointments = [
            ['name' => 'السبت'],
            ['name' => 'الاحد'],
            ['name' => 'الاثنين'],
            ['name' => 'الثلاثاء'],
            ['name' => 'الاربعاء'],
            ['name' => 'الخميس'],
            ['name' => 'الجمعة'],
        ];

        \DB::table('appointments')->insert($Appointments);
    }
}
