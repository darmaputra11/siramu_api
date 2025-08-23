<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class KematianSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        $data = [];

        for ($i = 1; $i <= 87; $i++) {
            $data[] = [
                'nik' => $faker->numerify('3201############'), // 16 digit NIK
                'nama_lengkap' => $faker->name,
                'tanggal_kematian' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                'nomor_akta' => 'AKT-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('tb_kematian')->insert($data);
    }
}
