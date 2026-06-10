<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah aroma_id jika belum ada
        if (!Schema::hasColumn('transactions', 'aroma_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->foreignId('aroma_id')->nullable()->constrained('aromas')->onDelete('set null')->after('notes');
            });
        }

        // 2. Tambah is_member ke customers jika belum ada
        if (!Schema::hasColumn('customers', 'is_member')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->boolean('is_member')->default(false);
            });
        }
    }

    public function down(): void
    {
        // Bagian untuk membatalkan migrasi
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'aroma_id')) {
                $table->dropForeign(['aroma_id']);
                $table->dropColumn('aroma_id');
            }
        });

        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'is_member')) {
                $table->dropColumn('is_member');
            }
        });
    }
};
