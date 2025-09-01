<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin Yogie',
                'email' => 'adminyogie@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Yogie',
                'email' => 'yogie@example.com',
                'password' => Hash::make('password'),
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
