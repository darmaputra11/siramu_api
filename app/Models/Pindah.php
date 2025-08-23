<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pindah extends Model
{
    use HasFactory;

    protected $table = 'tb_pindah';
protected $fillable = [
  'nik','nama_lengkap','nomor_kk','nomor_pindah','tanggal_pindah'
];
}
