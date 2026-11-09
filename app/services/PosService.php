<?php                                                                                                                         
                                                                                                                                  
    namespace App\Services;                                                                                                       
                                                                                                                                  
    class PosService                                                                                                              
    {                                                                                                                             
        /**                                                                                                                       
         * Menghitung diskon bertingkat berdasarkan subtotal.                                                                     
         */                                                                                                                       
        public function hitungDiskon(int $subtotal): int                                                                          
        {                                                                                                                         
            if ($subtotal >= 100000) {                                                                                            
                return (int) ($subtotal * 0.15); // Diskon 15%                                                                    
            } elseif ($subtotal >= 50000) {                                                                                       
                return (int) ($subtotal * 0.10); // Diskon 10%                                                                    
            } else {                                                                                                              
                return 0; // Tidak ada diskon                                                                                     
            }                                                                                                                     
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * Menghitung total akhir transaksi termasuk pajak 10%.                                                                   
         */                                                                                                                       
        public function hitungTotal(int $subtotal): array                                                                         
        {                                                                                                                         
            $diskon = $this->hitungDiskon($subtotal);                                                                             
            $dpp    = $subtotal - $diskon;                                                                                        
            $pajak  = (int) ($dpp * 0.10);                                                                                        
            $total  = $dpp + $pajak;                                                                                              
                                                                                                                                  
            return [                                                                                                              
                'subtotal' => $subtotal,                                                                                          
                'diskon'   => $diskon,                                                                                            
                'dpp'      => $dpp,                                                                                               
                'pajak'    => $pajak,                                                                                             
                'total'    => $total,                                                                                             
            ];                                                                                                                    
        }                                                                                                                         
    }  
