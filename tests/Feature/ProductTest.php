<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper untuk membuat akun admin simulasi.
     */
    private function buatAdmin(): User
    {
        return User::create([
            'name'     => 'Admin Toko',
            'email'    => 'admin@tokomaksum.com',
            'password' => bcrypt('password123'),
            'role'     => 'admin',
        ]);
    }

    /**
     * Uji 1: Admin dapat membuka halaman daftar produk.
     */
    public function test_admin_bisa_melihat_daftar_produk(): void
    {
        $admin = $this->buatAdmin();

        Product::create([
            'nama_produk' => 'Kopi Gula Aren',
            'harga'       => 15000,
            'stok'        => 20,
        ]);

        $response = $this->actingAs($admin)->get('/products');

        $response->assertStatus(200);
        $response->assertSee('Kopi Gula Aren');
    }

    /**
     * Uji 2: Admin dapat menyimpan produk baru ke database.
     */
    public function test_admin_bisa_menambah_produk_baru(): void
    {
        $admin = $this->buatAdmin();

        $dataProduk = [
            'nama_produk' => 'Beras Pandan Wangi 5kg',
            'kategori'    => 'Makanan',
            'harga'       => 75000,
            'stok'        => 10,
        ];

        $response = $this->actingAs($admin)->post('/products', $dataProduk);

        // Memastikan dialihkan kembali ke daftar produk dengan pesan sukses
        $response->assertRedirect('/products');
        $response->assertSessionHas('success');

        // Memastikan data benar-benar tersimpan di tabel products
        $this->assertDatabaseHas('products', [
            'nama_produk' => 'Beras Pandan Wangi 5kg',
            'harga'       => 75000,
            'stok'        => 10,
        ]);
    }

     /**                                                                                                                       
         * Uji 3: Validasi gagal jika kolom wajib dikosongkan.                                                                    
         */                                                                                                                       
        public function test_validasi_tambah_produk_gagal_jika_data_kosong(): void                                                
        {                                                                                                                         
            $admin = $this->buatAdmin();                                                                                          
                                                                                                                                  
            // Mengirim form kosong                                                                                               
            $response = $this->actingAs($admin)->post('/products', []);                                                           
                                                                                                                                  
            // Memastikan ada error validasi untuk nama_produk, harga, dan stok                                                   
            $response->assertSessionHasErrors(['nama_produk', 'kategori', 'harga', 'stok']);                                                  
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * Uji 4: Admin dapat memperbarui data produk (Update).                                                                   
         */                                                                                                                       
        public function test_admin_bisa_mengupdate_produk(): void                                                                 
        {                                                                                                                         
            $admin = $this->buatAdmin();                                                                                          
                                                                                                                                  
            $produk = Product::create([                                                                                           
                'nama_produk' => 'Minyak Goreng 1L',                                                                              
                'harga'       => 18000,                                                                                           
                'stok'        => 15,                                                                                              
            ]);                                                                                                                   
                                                                                                                                  
            $response = $this->actingAs($admin)->put("/products/{$produk->id}", [
                'nama_produk' => 'Minyak Goreng 1L (Promo)',
                'kategori'    => 'Kebutuhan Rumah Tangga',
                'harga'       => 16500,
                'stok'        => 20,
            ]);                                                                                                                   
                                                                                                                                  
            $response->assertRedirect('/products');                                                                               
            $response->assertSessionHas('success');                                                                               
                                                                                                                                  
            // Memastikan data di database sudah berubah                                                                          
            $this->assertDatabaseHas('products', [                                                                                
                'id'          => $produk->id,                                                                                     
                'nama_produk' => 'Minyak Goreng 1L (Promo)',                                                                      
                'harga'       => 16500,                                                                                           
                'stok'        => 20,                                                                                              
            ]);                                                                                                                   
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * Uji 5: Admin dapat menghapus produk (Delete).                                                                          
         */                                                                                                                       
        public function test_admin_bisa_menghapus_produk(): void                                                                  
        {                                                                                                                         
            $admin = $this->buatAdmin();                                                                                          
                                                                                                                                  
            $produk = Product::create([                                                                                           
                'nama_produk' => 'Gula Pasir 1kg',                                                                                
                'harga'       => 14000,                                                                                           
                'stok'        => 10,                                                                                              
            ]);                                                                                                                   
                                                                                                                                  
            $response = $this->actingAs($admin)->delete("/products/{$produk->id}");                                               
                                                                                                                                  
            $response->assertRedirect('/products');                                                                               
            $response->assertSessionHas('success');                                                                               
                                                                                                                                  
            // Memastikan data sudah TIDAK ADA di database                                                                        
            $this->assertDatabaseMissing('products', [                                                                            
                'id' => $produk->id,                                                                                              
            ]);  
                                                                                                                             
        }        
}
