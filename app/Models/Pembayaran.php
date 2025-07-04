<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
    'nama_pasien',
    'jenis_kelamin',
    'poli', // ✅ Tambahkan ini ya!
    'jenis_pembayaran',
    'bulan',
    'tahun',
];

}
