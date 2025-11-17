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
    Schema::create('balita', function (Blueprint $table) {
        $table->increments('id_balita');         // PK
        $table->string('nama');
        $table->date('tanggal_lahir');
        $table->enum('jenis_kelamin', ['L', 'P']);
        $table->string('nama_ibu')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balitas');
    }
};
