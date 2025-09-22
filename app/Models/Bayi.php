<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bayi extends Model
{
    use HasFactory;

    protected $table = 'tb_bayi';

    protected $fillable = [
        'no_entitas',
        'nik',
        'nama',
        'hub_keluarga',
        'tgl_lahir_bayi',
        'nama_ibu_kandung',
        'tgl_lahir_ibu_kandung',
    ];
}
