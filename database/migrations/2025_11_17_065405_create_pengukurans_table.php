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
    Schema::create('pengukuran', function (Blueprint $table) {
        $table->increments('id_ukur');        // PK

        $table->unsignedInteger('id_balita');
        $table->unsignedBigInteger('id_user'); // relasi ke users.id

        $table->date('tanggal_ukur');
        $table->integer('umur_bulan');
        $table->decimal('bb_kg', 5, 2);       // berat badan
        $table->decimal('tb_cm', 5, 2);       // tinggi badan
        $table->decimal('lila_cm', 5, 2)->nullable();
        $table->boolean('status_stunting')->nullable(); // true=stunting, false=tidak, null=belum tahu

        $table->timestamps();

        $table->foreign('id_balita')->references('id_balita')->on('balita')->onDelete('cascade');
        $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengukurans');
    }
};
