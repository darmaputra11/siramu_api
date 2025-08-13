<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PindahSeeder extends Seeder
{
    public function run()
    {
        DB::table('tb_pindah')->insert([
            [
                'nik' => '3201010101010001',
                'nama_lengkap' => 'Budi Santoso',
                'nomor_kk' => '3201010101010002',
                'nomor_pindah' => 'PND-001',
                'tanggal_pindah' => '2025-08-01',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nik' => '3201010101010003',
                'nama_lengkap' => 'Siti Aminah',
                'nomor_kk' => '3201010101010004',
                'nomor_pindah' => 'PND-002',
                'tanggal_pindah' => '2025-08-02',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
