<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDokter extends Model
{
    use HasFactory;

    protected $table = 'jadwal_dokter';

    protected $fillable = [
        'nama_dokter',
        'spesialis',
        'tanggal',
        'status',
    ];

    protected $dates = ['tanggal'];
}
