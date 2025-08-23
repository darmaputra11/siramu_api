<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;   // <— penting
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    // isi sesuai kolom yang ada
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ===== JWTSubject methods =====
    public function getJWTIdentifier()
    {
        // biasanya primary key user
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        // bisa tambahkan klaim custom; kosongkan jika tidak perlu
        return [];
    }
}
