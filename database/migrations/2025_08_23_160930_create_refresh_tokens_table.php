<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refresh_tokens', function (Blueprint $table) {
    $table->id();

    // Cocokkan ke users.id (unsigned BIGINT)
    $table->foreignId('user_id')
          ->constrained('users')
          ->cascadeOnDelete();

    $table->string('token_hash', 128)->unique();
    $table->string('ip', 64)->nullable();
    $table->string('ua', 255)->nullable();
    $table->timestamp('expires_at')->index();
    $table->boolean('revoked')->default(false)->index();
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('refresh_tokens');
    }
};
