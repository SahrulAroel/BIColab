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
            $table->unsignedTinyInteger('bulan')->after('status')->nullable(); // nilai 1-12
            $table->year('tahun')->after('bulan')->nullable(); // misal: 2025
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_dokter', function (Blueprint $table) {
            $table->dropColumn('bulan');
            $table->dropColumn('tahun');
        });
    }
};
