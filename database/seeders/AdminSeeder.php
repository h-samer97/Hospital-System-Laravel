<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('admins')->delete();

        Admin::create([
            'name'     => 'root',
            'email'    => 'root@hospital.com',
            'password' => 'root',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}