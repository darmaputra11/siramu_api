<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // aman-aman saja ditambah pengecekan biar tidak error kalau sudah ada
        if (!Schema::hasColumn('tb_kematian', 'tanggal_akta')) {
            Schema::table('tb_kematian', function (Blueprint $table) {
                $table->date('tanggal_akta')->nullable()->after('nomor_akta');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tb_kematian', 'tanggal_akta')) {
            Schema::table('tb_kematian', function (Blueprint $table) {
                $table->dropColumn('tanggal_akta');
            });
        }
    }
};
