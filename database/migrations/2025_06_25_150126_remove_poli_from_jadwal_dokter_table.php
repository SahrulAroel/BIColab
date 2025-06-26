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
            if (Schema::hasColumn('jadwal_dokter', 'poli')) {
                $table->dropColumn('poli');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_dokter', function (Blueprint $table) {
            $table->string('poli')->nullable()->after('tahun');
        });
    }
};
