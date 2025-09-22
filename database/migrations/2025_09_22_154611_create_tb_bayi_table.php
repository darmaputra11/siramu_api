<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hanya create kalau tabel belum ada
        if (!Schema::hasTable('tb_bayi')) {
            Schema::create('tb_bayi', function (Blueprint $table) {
                $table->id();
                $table->string('no_entitas')->nullable();
                $table->string('nik', 16)->nullable()->index();
                $table->string('nama')->nullable();
                $table->string('hub_keluarga')->nullable();
                $table->date('tgl_lahir_bayi')->nullable();
                $table->string('nama_ibu_kandung')->nullable();
                $table->date('tgl_lahir_ibu_kandung')->nullable();
                $table->timestamps();
            });
        }

        // NOTE:
        // Kalau tabel sudah ada tapi strukturnya beda (kolom kurang/lebih),
        // buat migration TERPISAH untuk alter (add/modify/drop) kolomnya.
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_bayi');
    }
};
