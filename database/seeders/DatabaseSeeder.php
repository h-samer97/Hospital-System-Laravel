<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\ServiceTableSeeder;
use Database\Seeders\GroupsTableSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            AppointmentTableSeeder::class,
            SectionTableSeeder::class,
            DoctorTableSeeder::class,
            ImageTableSeeder::class,
            ServiceTableSeeder::class,
            GroupsTableSeeder::class
        ]);
        

        User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => 'user'
        ]);
    }
}
