<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('tb_user')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Dinas Kesehatan',
                'email' => 'dinkes@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'viewer',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
