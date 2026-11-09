<?php                                                                                                                         
                                                                                                                                  
    namespace App\Models;                                                                                                         
                                                                                                                                  
    use Illuminate\Database\Eloquent\Model;                                                                                       
                                                                                                                                  
    class Transaction extends Model                                                                                               
    {                                                                                                                             
        protected $fillable = [                                                                                                   
            'user_id', 'tanggal', 'total',                                                                                        
            'diskon', 'pajak', 'bayar', 'kembalian',                                                                              
        ];                                                                                                                        
                                                                                                                                  
        /** Relasi ke user (kasir). */                                                                                            
        public function user()                                                                                                    
        {                                                                                                                         
            return $this->belongsTo(User::class);                                                                                 
        }                                                                                                                         
                                                                                                                                  
        /** Relasi ke detail transaksi. */                                                                                        
        public function details()                                                                                                 
        {                                                                                                                         
            return $this->hasMany(TransactionDetail::class);                                                                      
        }                                                                                                                         
    }                                                                                                                             
                                                                                                                                  
                                                                                
    