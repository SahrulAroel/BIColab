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
            $table->enum('status', ['Hadir', 'Cuti', 'Tidak Hadir'])->default('Hadir')->after('jam_selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_dokter', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
