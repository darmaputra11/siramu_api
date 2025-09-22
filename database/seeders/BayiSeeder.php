<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class BayiSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $data = [];

        for ($i = 1; $i <= 50; $i++) {
            // Tanggal lahir bayi: 0–2 tahun terakhir
            $tglLahirBayi = Carbon::instance($faker->dateTimeBetween('-2 years', 'now'))->startOfDay();

            // Tanggal lahir ibu kandung: 20–40 tahun yang lalu
            $tglLahirIbu = Carbon::instance($faker->dateTimeBetween('-40 years', '-20 years'))->startOfDay();

            $data[] = [
                'no_entitas'           => 'ENT-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nik'                  => $faker->numerify('3201############'), // 16 digit NIK
                'nama'                 => $faker->firstName,                   // nama bayi
                'hub_keluarga'         => $faker->randomElement(['Anak', 'Cucu', 'Keponakan']),
                'tgl_lahir_bayi'       => $tglLahirBayi->toDateString(),
                'nama_ibu_kandung'     => $faker->name('female'),
                'tgl_lahir_ibu_kandung'=> $tglLahirIbu->toDateString(),
                'created_at'           => now(),
                'updated_at'           => now(),
            ];
        }

        DB::table('tb_bayi')->insert($data);
    }
}
