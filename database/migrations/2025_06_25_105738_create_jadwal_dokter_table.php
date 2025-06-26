<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalDokterTable extends Migration
{
    public function up()
    {
        Schema::create('jadwal_dokter', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dokter');
            $table->string('spesialis');
            $table->string('hari_praktek'); // Senin, Selasa, dll
            $table->time('jam_mulai');      // 08:00
            $table->time('jam_selesai');    // 12:00
            $table->string('poli')->nullable(); // Poli Anak, Umum, dll (opsional)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal_dokter');
    }
}
