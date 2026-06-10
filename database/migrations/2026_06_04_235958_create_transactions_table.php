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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // Nomor nota otomatis (cth: TRS-20260604-0001)
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Kasir yang melayani
            $table->foreignId('promo_id')->nullable()->constrained()->onDelete('set null');

            $table->dateTime('start_date'); // Jam masuk laundry
            $table->dateTime('end_date');   // Estimasi jam selesai otomatis

            $table->decimal('subtotal', 12, 2);  // Total sebelum diskon
            $table->decimal('discount', 12, 2)->default(0); // Nilai potongan rupiah
            $table->decimal('total_pay', 12, 2); // Total setelah diskon yang harus dibayar

            // Poin 6: Status Laundry
            $table->enum('status', ['diterima', 'diproses', 'selesai', 'diambil'])->default('diterima');
            $table->enum('payment_status', ['belum_bayar', 'lunas'])->default('belum_bayar');

            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
