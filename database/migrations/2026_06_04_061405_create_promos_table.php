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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Contoh: DISKON10, HEMATONGKIR
            $table->enum('type', ['percentage', 'nominal']); // Tipe potongan: persen atau nominal Rupiah
            $table->integer('value'); // Nilai potongan (misal: 10 untuk 10%, atau 5000 untuk Rp5.000)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
