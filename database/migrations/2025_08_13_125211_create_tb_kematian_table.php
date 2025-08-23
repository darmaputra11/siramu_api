<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hanya create kalau tabel belum ada
        if (!Schema::hasTable('tb_kematian')) {
            Schema::create('tb_kematian', function (Blueprint $table) {
                $table->id();
                $table->string('nik', 16)->index();
                $table->string('nama_lengkap');
                $table->date('tanggal_kematian');
                $table->string('nomor_akta')->unique();
                $table->timestamps();
            });
        }
        // NOTE:
        // Kalau tabel sudah ada tapi strukturnya beda (kolom kurang/lebih),
        // buat migration TERPISAH untuk alter (add/modify/drop) kolomnya.
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_kematian');
    }
};
