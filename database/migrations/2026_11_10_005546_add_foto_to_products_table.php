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
            Schema::table('products', function (Blueprint $table) {                                                               
                // Kolom foto boleh kosong (nullable) jika ada produk tanpa gambar                                                
                $table->string('foto')->nullable()->after('stok');                                                                
            });                                                                                                                   
        }                                                                                                                         
                                                                                                                                  
        public function down(): void                                                                                              
        {                                                                                                                         
            Schema::table('products', function (Blueprint $table) {                                                               
                $table->dropColumn('foto');                                                                                       
            });                                                                                                                   
        }                  
};
