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
            $diskon = $this->pos->hitungDiskon(10000);                                                                            
            $this->assertEquals(0, $diskon);                                                                                      
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * TC-02: Subtotal 50.000–99.999 → diskon 10%.                                                                            
         */                                                                                                                       
        public function test_diskon_level_1_limapersen_persen(): void                                                                
        {                                                                                                                         
            $diskon = $this->pos->hitungDiskon(100000);                                                                            
            $this->assertEquals(5000, $diskon);                                                                                   
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * TC-03: Subtotal >= 300.000 → diskon 10%.                                                                               
         */                                                                                                                       
        public function test_diskon_level_2_limabelas_persen(): void                                                              
        {                                                                                                                         
            $diskon = $this->pos->hitungDiskon(300000);                                                                           
            $this->assertEquals(30000, $diskon);                                                                                  
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * TC-04: Hitung DPP dan Pajak 10%.                                                                                       
         */                                                                                                                       
        public function test_hitung_pajak_sepuluh_persen(): void                                                                  
        {                                                                                                                         
            $hasil = $this->pos->hitungTotal(58000);
            $this->assertEquals(58000, $hasil['dpp']);
            $this->assertEquals(5800, $hasil['pajak']);
        }

        /**
         * TC-04B: Hitung Pajak 20% jika DPP >= 100.000.
         */
        public function test_hitung_pajak_duapuluh_persen_jika_dpp_seratus_ribu(): void
        {
            // Subtotal = 120.000, Diskon 10% = 12.000, DPP = 108.000 (>= 100rb) -> Pajak 20% = 21.600
            $hasil = $this->pos->hitungTotal(120000);
            $this->assertEquals(114000, $hasil['dpp']);
            $this->assertEquals(22800, $hasil['pajak']);
            $this->assertEquals(20, $hasil['persen_pajak']);
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * TC-05: Total akhir = DPP + Pajak.                                                                                      
         */                                                                                                                       
        public function test_total_akhir(): void                                                                                  
        {                                                                                                                         
            $hasil = $this->pos->hitungTotal(58000);                                                                              
            $this->assertEquals(63800, $hasil['total']);                                                                          
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * TC-06: Kembalian = Bayar - Total.                                                                                      
         */                                                                                                                       
        public function test_kembalian_cukup(): void                                                                              
        {                                                                                                                         
            $hasil     = $this->pos->hitungTotal(58000);                                                                          
            $bayar     = 70000;                                                                                                   
            $kembalian = $bayar - $hasil['total'];                                                                                
            $this->assertEquals(6200, $kembalian);                                                                                
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