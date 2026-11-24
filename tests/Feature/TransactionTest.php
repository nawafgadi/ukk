<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper untuk membuat akun kasir simulasi.
     */
    private function buatKasir(): User
    {
        return User::create([
            'name'     => 'Kasir Toko',
            'email'    => 'kasir@tokomaksum.com',
            'password' => bcrypt('password123'),
            'role'     => 'kasir',
        ]);
    }

    /**
     * Uji 1: Kasir dapat membuka halaman terminal POS.
     */
    public function test_kasir_dapat_mengakses_halaman_kasir(): void
    {
        $kasir = $this->buatKasir();

        $response = $this->actingAs($kasir)->get('/kasir');

        $response->assertStatus(200);
        $response->assertSee('Keranjang Kasir');
    }

    /**
     * Uji 2: Transaksi berhasil disimpan, stok berkurang, dan diarahkan ke struk.
     */
    public function test_transaksi_berhasil_dan_stok_berkurang(): void
    {
        $kasir = $this->buatKasir();

        // Siapkan produk dengan stok awal = 10
        $produk = Product::create([
            'nama_produk' => 'Beras Ramos 5kg',
            'harga'       => 60000,
            'stok'        => 10,
        ]);

        // Subtotal = 120.000, Diskon 15% = 18.000, DPP = 102.000, Pajak 20% = 20.400, Total = 122.400, Bayar = 130.000
        $payload = [
            'items' => [
                [
                    'id'  => $produk->id,
                    'qty' => 2,
                ],
            ],
            'bayar' => 130000,
        ];

        $response = $this->actingAs($kasir)->post('/kasir', $payload);

        // 1. Memastikan diarahkan ke halaman struk
        $transaksi = Transaction::first();
        $this->assertNotNull($transaksi);
        $response->assertRedirect("/kasir/struk/{$transaksi->id}");

        // 2. Memastikan stok berkurang: 10 - 2 = 8
        $this->assertEquals(8, $produk->fresh()->stok);

        // 3. Memastikan data transaksi tersimpan di database
        $this->assertDatabaseHas('transactions', [
            'id'        => $transaksi->id,
            'user_id'   => $kasir->id,
            'total'     => 122400,
            'diskon'    => 18000,
            'pajak'     => 20400,
            'bayar'     => 130000,
            'kembalian' => 7600,
        ]);
    }

    /**
     * Uji 3: Transaksi ditolak jika uang pembayaran kurang dari total tagihan.
     */
    public function test_transaksi_ditolak_jika_uang_bayar_kurang(): void
    {
        $kasir = $this->buatKasir();

        $produk = Product::create([
            'nama_produk' => 'Minyak Goreng',
            'harga'       => 20000,
            'stok'        => 5,
        ]);

        // Total tagihan = 20.000 + pajak 10% (2.000) = 22.000, Uang bayar hanya 10.000 (kurang)
        $payload = [
            'items' => [
                [
                    'id'  => $produk->id,
                    'qty' => 1,
                ],
            ],
            'bayar' => 10000,
        ];

        $response = $this->actingAs($kasir)->post('/kasir', $payload);

        // Memastikan ada error dan transaksi tidak tersimpan
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('transactions', 0);

        // Stok tidak boleh berkurang
        $this->assertEquals(5, $produk->fresh()->stok);
    }
}
