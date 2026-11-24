<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Nilai Default Awal
        $defaultSettings = [
            ['key' => 'diskon_tingkat_1_min', 'value' => '50000', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'diskon_tingkat_1_persen', 'value' => '10', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'diskon_tingkat_2_min', 'value' => '100000', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'diskon_tingkat_2_persen', 'value' => '15', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'pajak_tingkat_1_persen', 'value' => '10', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'pajak_tingkat_2_min', 'value' => '100000', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'pajak_tingkat_2_persen', 'value' => '20', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('settings')->insert($defaultSettings);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
