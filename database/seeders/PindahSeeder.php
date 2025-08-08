<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PindahSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_pindah')->insert([
            'nik' => '3276010101010002',
            'nama_lengkap' => 'Siti Nurhaliza',
            'no_kk' => '3276010101010000',
            'nomor_pindah' => 'PINDAH-2024-001',
            'tanggal_pindah' => '2024-08-01',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
