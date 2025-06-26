<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('pembayarans', function (Blueprint $table) {
        $table->string('nama_pasien')->after('id');
        $table->string('jenis_kelamin')->after('nama_pasien');
    });
}

public function down()
{
    Schema::table('pembayarans', function (Blueprint $table) {
        $table->dropColumn(['nama_pasien', 'jenis_kelamin']);
    });
}


};
