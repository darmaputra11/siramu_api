<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <-- Tambahkan baris ini
use Carbon\Carbon;

class KematianSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_kematian')->insert([
            'nik' => '3276010101010001',
            'nama_lengkap' => 'Ahmad Sulaiman',
            'tanggal_kematian' => '2024-07-15',
            'nomor_akta' => 'AKTA-2024-001',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
