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
    Schema::create('hasil_prediksi', function (Blueprint $table) {
        $table->increments('id_pred');      // PK
        $table->unsignedInteger('id_ukur'); // FK ke pengukuran

        $table->boolean('label_pred');      // 1 = risiko stunting, 0 = tidak
        $table->decimal('prob_pred', 5, 4); // misal 0.9123

        $table->timestamps();

        $table->foreign('id_ukur')->references('id_ukur')->on('pengukuran')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_prediksis');
    }
};
