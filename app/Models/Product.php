<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * Kolom yang boleh diisi massal (mass assignment).
     */
    protected $fillable = [
        'nama_produk',
        'harga',
        'stok',
    ];

    /**
     * Relasi produk memiliki banyak detail transaksi.
     */
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
