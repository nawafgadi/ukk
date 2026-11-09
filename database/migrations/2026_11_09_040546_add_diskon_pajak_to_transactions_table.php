<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom diskon dan pajak ke tabel transactions.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('diskon')->default(0)->after('total');
            $table->integer('pajak')->default(0)->after('diskon');
        });
    }

    /**
     * Menghapus kolom diskon dan pajak dari tabel transactions.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['diskon', 'pajak']);
        });
    }
};
