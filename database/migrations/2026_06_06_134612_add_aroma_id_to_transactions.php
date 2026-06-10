<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hanya menambah, tidak menghapus apapun
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('aroma_id')->nullable()->constrained('aromas')->onDelete('set null')->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['aroma_id']);
            $table->dropColumn('aroma_id');
        });
    }
};
