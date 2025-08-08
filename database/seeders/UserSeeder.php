<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    User::firstOrCreate([
        'email' => 'admin@mail.com'
    ], [
        'username' => 'admin',
        'password' => Hash::make('password'),
        'role' => 'admin'
    ]);

    User::firstOrCreate([
        'email' => 'dinkes@mail.com'
    ], [
        'username' => 'dinkes',
        'password' => Hash::make('password'),
        'role' => 'dinkes'
    ]);

    User::firstOrCreate([
        'email' => 'dinsos@mail.com'
    ], [
        'username' => 'dinsos',
        'password' => Hash::make('password'),
        'role' => 'dinsos'
    ]);

    User::firstOrCreate([
        'email' => 'bpjs@mail.com'
    ], [
        'username' => 'bpjs',
        'password' => Hash::make('password'),
        'role' => 'bpjs'
    ]);
}
}
