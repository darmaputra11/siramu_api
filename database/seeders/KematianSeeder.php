<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class KematianSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $data = [];

        for ($i = 1; $i <= 87; $i++) {
            // tanggal_kematian acak 2 tahun terakhir
            $tglKematian = Carbon::instance($faker->dateTimeBetween('-2 years', 'now'))->startOfDay();
            // tanggal_akta 0–30 hari setelahnya
            $tglAkta = $tglKematian->copy()->addDays(random_int(0, 30));

            $data[] = [
                'nik'               => $faker->numerify('3201############'), // 16 digit NIK
                'nama_lengkap'      => $faker->name,
                'tanggal_kematian'  => $tglKematian->toDateString(),
                'nomor_akta'        => 'AKT-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'tanggal_akta'      => $tglAkta->toDateString(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }

        DB::table('tb_kematian')->insert($data);
    }
}
