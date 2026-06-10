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
        // Tambah aroma_id ke transaksi
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('aroma_id')->nullable()->constrained('aromas')->onDelete('set null');
        });

        // Tambah is_member ke pelanggan
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('is_member')->default(false);
        });
    }
};
