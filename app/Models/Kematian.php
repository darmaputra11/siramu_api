<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kematian extends Model
{
    use HasFactory;

    protected $table = 'tb_kematian';

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'tanggal_kematian',
        'nomor_akta',
        'tanggal_akta',
    ];
}
