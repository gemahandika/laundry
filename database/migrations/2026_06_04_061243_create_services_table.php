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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Cuci Kering Kiloan" atau "Kemeja Satuan"
            $table->enum('type', ['kiloan', 'satuan']);
            $table->integer('price'); // Harga per kg atau per pcs
            $table->integer('estimated_hours')->default(24); // Estimasi selesai dalam jam (default 24 jam/1 hari)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
