<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('refresh_tokens', function (Blueprint $table) {
            if (!Schema::hasColumn('refresh_tokens', 'revoked')) {
                $table->boolean('revoked')->default(false)->after('expires_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('refresh_tokens', function (Blueprint $table) {
            if (Schema::hasColumn('refresh_tokens', 'revoked')) {
                $table->dropColumn('revoked');
            }
        });
    }
};

