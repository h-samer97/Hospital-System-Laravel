<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserTableSeeder::class,
            AdminTableSeeder::class,
            SectionSeeder::class,
            AppointmentSeeder::class,
            SingleServicesSeeder::class,
            DoctorSeedTable::class,
            PatientSeeder::class,
            InsuranceSeeder::class,
            AmbulancesSeeder::class,
            ImageSeedTable::class,
            GroupsSeeder::class,
            SingleInvoiceSeeder::class,
        ]);
    }
}
