<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jadwal_dokter', function (Blueprint $table) {
            // Drop kolom jam_mulai dan jam_selesai hanya jika ada
            if (Schema::hasColumn('jadwal_dokter', 'jam_mulai')) {
                $table->dropColumn('jam_mulai');
            }

            if (Schema::hasColumn('jadwal_dokter', 'jam_selesai')) {
                $table->dropColumn('jam_selesai');
            }
        });

        Schema::table('jadwal_dokter', function (Blueprint $table) {
            // Tambahkan kolom tanggal jika belum ada
            if (!Schema::hasColumn('jadwal_dokter', 'tanggal')) {
                $table->date('tanggal')->nullable()->after('hari_praktek');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_dokter', function (Blueprint $table) {
            // Tambahkan kembali jam_mulai dan jam_selesai
            if (!Schema::hasColumn('jadwal_dokter', 'jam_mulai')) {
                $table->time('jam_mulai')->nullable()->after('spesialis');
            }

            if (!Schema::hasColumn('jadwal_dokter', 'jam_selesai')) {
                $table->time('jam_selesai')->nullable()->after('jam_mulai');
            }

            // Hapus kolom tanggal jika ada
            if (Schema::hasColumn('jadwal_dokter', 'tanggal')) {
                $table->dropColumn('tanggal');
            }
        });
    }
};
