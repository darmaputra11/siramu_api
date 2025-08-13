<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_kematian', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->index();
            $table->string('nama_lengkap');
            $table->date('tanggal_kematian');
            $table->string('nomor_akta')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_kematian');
    }
};
