<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KematianSeeder extends Seeder
{
    public function run()
    {
        DB::table('tb_kematian')->insert([
            [
                'nik' => '3201010101010005',
                'nama_lengkap' => 'Agus Salim',
                'tanggal_kematian' => '2025-07-20',
                'nomor_akta' => 'AKT-001',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nik' => '3201010101010006',
                'nama_lengkap' => 'Nurhayati',
                'tanggal_kematian' => '2025-07-25',
                'nomor_akta' => 'AKT-002',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
