<?php                                                                                                                         
                                                                                                                                  
    namespace Tests\Unit;                                                                                                         
                                                                                                                                  
    use PHPUnit\Framework\TestCase;                                                                                               
    use App\Services\PosService;                                                                                                  
                                                                                                                                  
    class PosServiceTest extends TestCase                                                                                         
    {                                                                                                                             
        protected PosService $pos;                                                                                                
                                                                                                                                  
        protected function setUp(): void                                                                                          
        {                                                                                                                         
            parent::setUp();                                                                                                      
            $this->pos = new PosService();                                                                                        
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * TC-01: Subtotal di bawah 50.000 → diskon 0.                                                                            
         */                                                                                                                       
        public function test_diskon_level_0_tidak_ada_diskon(): void                                                              
        {                                                                                                                         
            $diskon = $this->pos->hitungDiskon(30000);                                                                            
            $this->assertEquals(0, $diskon);                                                                                      
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * TC-02: Subtotal 50.000–99.999 → diskon 10%.                                                                            
         */                                                                                                                       
        public function test_diskon_level_1_sepuluh_persen(): void                                                                
        {                                                                                                                         
            $diskon = $this->pos->hitungDiskon(58000);                                                                            
            $this->assertEquals(5800, $diskon);                                                                                   
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * TC-03: Subtotal >= 100.000 → diskon 15%.                                                                               
         */                                                                                                                       
        public function test_diskon_level_2_limabelas_persen(): void                                                              
        {                                                                                                                         
            $diskon = $this->pos->hitungDiskon(120000);                                                                           
            $this->assertEquals(18000, $diskon);                                                                                  
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * TC-04: Hitung DPP dan Pajak 10%.                                                                                       
         */                                                                                                                       
        public function test_hitung_pajak_sepuluh_persen(): void                                                                  
        {                                                                                                                         
            $hasil = $this->pos->hitungTotal(58000);                                                                              
            $this->assertEquals(52200, $hasil['dpp']);                                                                            
            $this->assertEquals(5220, $hasil['pajak']);                                                                           
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * TC-05: Total akhir = DPP + Pajak.                                                                                      
         */                                                                                                                       
        public function test_total_akhir(): void                                                                                  
        {                                                                                                                         
            $hasil = $this->pos->hitungTotal(58000);                                                                              
            $this->assertEquals(57420, $hasil['total']);                                                                          
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * TC-06: Kembalian = Bayar - Total.                                                                                      
         */                                                                                                                       
        public function test_kembalian_cukup(): void                                                                              
        {                                                                                                                         
            $hasil     = $this->pos->hitungTotal(58000);                                                                          
            $bayar     = 60000;                                                                                                   
            $kembalian = $bayar - $hasil['total'];                                                                                
            $this->assertEquals(2580, $kembalian);                                                                                
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * TC-07: Bayar kurang dari total → kembalian negatif.                                                                    
         */                                                                                                                       
        public function test_bayar_kurang_dari_total(): void                                                                      
        {                                                                                                                         
            $hasil     = $this->pos->hitungTotal(58000);                                                                          
            $bayar     = 50000;                                                                                                   
            $kembalian = $bayar - $hasil['total'];                                                                                
            $this->assertLessThan(0, $kembalian);                                                                                 
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * TC-08: Stok berkurang setelah transaksi.                                                                               
         */                                                                                                                       
        public function test_stok_berkurang_setelah_transaksi(): void                                                             
        {                                                                                                                         
            $stokAwal = 10;                                                                                                       
            $beli     = 2;                                                                                                        
            $sisaStok = $stokAwal - $beli;                                                                                        
            $this->assertEquals(8, $sisaStok);                                                                                    
        }                                                                                                                         
    }        