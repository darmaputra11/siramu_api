<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('tb_pindah')) {
            Schema::create('tb_pindah', function (Blueprint $table) {
                $table->id();
                $table->string('nik', 16);
                $table->string('nama_lengkap');
                $table->string('nomor_kk', 16);
                $table->string('nomor_pindah');
                $table->date('tanggal_pindah');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_pindah');
    }
};
