<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PindahSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        $data = [];

        for ($i = 1; $i <= 50; $i++) {
            $data[] = [
                'nik' => $faker->numerify('3201############'), // 16 digit NIK
                'nama_lengkap' => $faker->name,
                'nomor_kk' => $faker->numerify('3172###########'), // 16 digit No KK
                'nomor_pindah' => 'P-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'tanggal_pindah' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('tb_pindah')->insert($data);
    }
}
