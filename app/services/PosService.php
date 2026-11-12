<?php                                                                                                                         
                                                                                                                                  
    namespace App\Services;                                                                                                       
                                                                                                                                  
    class PosService                                                                                                              
    {                                                                                                                             
        /**                                                                                                                       
         * Menghitung diskon bertingkat berdasarkan subtotal.                                                                     
         */                                                                                                                       
        public function hitungDiskon(int $subtotal): int                                                                          
        {                                                                                                                         
        if ($subtotal >= 300000) {
            return (int) ($subtotal * 0.10); // Diskon 10%                                                                    
            } elseif ($subtotal >= 100000) {                                                                                       
                return (int) ($subtotal * 0.05); // Diskon 5%                                                                    
            } else {                                                                                                              
                return 0; // Tidak ada diskon                                                                                     
            }                                                                                                                     
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * Menghitung total akhir transaksi termasuk pajak 10%.                                                                   
         */                                                                                                                       
      /**                                                                                                                       
         * Menghitung total akhir transaksi termasuk pajak bertingkat (10% atau 20%).                                             
         */                                                                                                                       
        public function hitungTotal(int $subtotal): array                                                                         
        {                                                                                                                         
            $diskon = $this->hitungDiskon($subtotal);                                                                             
            $dpp    = $subtotal - $diskon;                                                                                        
                                                                                                                                  
            // Logika Pajak Bertingkat:                                                                                           
            // Jika belanja (DPP) >= 100.000, pajak 20%. Jika di bawah itu, pajak 10%.                                            
            $persenPajak = ($dpp >= 100000) ? 20 : 10;                                                                            
            $pajak       = (int) ($dpp * ($persenPajak / 100));                                                                   
            $total       = $dpp + $pajak;                                                                                         
                                                                                                                                  
            return [                                                                                                              
                'subtotal'     => $subtotal,                                                                                      
                'diskon'       => $diskon,                                                                                        
                'dpp'          => $dpp,                                                                                           
                'persen_pajak' => $persenPajak,                                                                                   
                'pajak'        => $pajak,                                                                                         
                'total'        => $total,                                                                                         
            ];                                                                                                                    
        } 
    }
