<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan ringkasan metrik dan riwayat transaksi.
     */
    public function index()
    {
        $totalProduk     = Product::count();
        $totalTransaksi  = Transaction::count();
        $totalPendapatan = Transaction::sum('total');
        $semuaTransaksi  = Transaction::with('user')->latest()->get();

        return view('dashboard', compact(
            'totalProduk',
            'totalTransaksi',
            'totalPendapatan',
            'semuaTransaksi'
        ));
    }
}
