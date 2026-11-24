<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Setting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan ringkasan metrik, riwayat transaksi, dan pengaturan.
     */
    public function index()
    {
        $totalProduk     = Product::count();
        $totalTransaksi  = Transaction::count();
        $totalPendapatan = Transaction::sum('total');
        $semuaTransaksi  = Transaction::with('user')->latest()->get();
        $settings        = Setting::getAllSettings();

        return view('dashboard', compact(
            'totalProduk',
            'totalTransaksi',
            'totalPendapatan',
            'semuaTransaksi',
            'settings'
        ));
    }

    /**
     * Memperbarui pengaturan diskon dan pajak secara dinamis dari dashboard admin.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'diskon_tingkat_1_min'    => 'required|integer|min:0',
            'diskon_tingkat_1_persen' => 'required|integer|min:0|max:100',
            'diskon_tingkat_2_min'    => 'required|integer|min:0',
            'diskon_tingkat_2_persen' => 'required|integer|min:0|max:100',
            'pajak_tingkat_1_persen'  => 'required|integer|min:0|max:100',
            'pajak_tingkat_2_min'     => 'required|integer|min:0',
            'pajak_tingkat_2_persen'  => 'required|integer|min:0|max:100',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Pengaturan Diskon & Pajak berhasil diperbarui dan langsung aktif!');
    }
}
