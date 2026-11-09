<?php                                                                                                                         
                                                                                                                                  
    namespace App\Http\Controllers;                                                                                               
                                                                                                                                  
    use Illuminate\Http\Request;                                                                                                  
    use Illuminate\Support\Facades\Auth;                                                                                          
    use Illuminate\Support\Facades\DB;                                                                                            
    use App\Models\Product;                                                                                                       
    use App\Models\Transaction;                                                                                                   
    use App\Models\TransactionDetail;                                                                                             
    use App\Services\PosService;                                                                                                  
                                                                                                                                  
    class TransactionController extends Controller                                                                                
    {                                                                                                                             
        protected PosService $posService;                                                                                         
                                                                                                                                  
        public function __construct(PosService $posService)                                                                       
        {                                                                                                                         
            $this->posService = $posService;                                                                                      
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * Menampilkan halaman kasir beserta daftar produk.                                                                       
         */                                                                                                                       
        public function index()                                                                                                   
        {                                                                                                                         
            $products = Product::where('stok', '>', 0)->get();                                                                    
            return view('kasir.index', compact('products'));                                                                      
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * Menyimpan transaksi ke database secara atomik.                                                                         
         */                                                                                                                       
        public function store(Request $request)                                                                                   
        {                                                                                                                         
            $request->validate([                                                                                                  
                'items'        => 'required|array|min:1',                                                                         
                'items.*.id'   => 'required|exists:products,id',                                                                  
                'items.*.qty'  => 'required|integer|min:1',                                                                       
                'bayar'        => 'required|integer|min:0',                                                                       
            ]);                                                                                                                   
                                                                                                                                  
            // Hitung subtotal dari semua item                                                                                    
            $subtotal = 0;                                                                                                        
            $items    = [];                                                                                                       
                                                                                                                                  
            foreach ($request->items as $item) {                                                                                  
                $product = Product::findOrFail($item['id']);                                                                      
                                                                                                                                  
                // Validasi stok                                                                                                  
                if ($item['qty'] > $product->stok) {                                                                              
                    return back()->with('error',                                                                                  
                        "Stok {$product->nama_produk} tidak cukup! Sisa: {$product->stok}"                                        
                    );                                                                                                            
                }                                                                                                                 
                                                                                                                                  
                $subtotal      += $product->harga * $item['qty'];                                                                 
                $items[]        = [                                                                                               
                    'product' => $product,                                                                                        
                    'qty'     => $item['qty'],                                                                                    
                ];                                                                                                                
            }                                                                                                                     
                                                                                                                                  
            // Hitung diskon, pajak, total                                                                                        
            $kalkulasi = $this->posService->hitungTotal($subtotal);                                                               
                                                                                                                                  
            // Validasi uang bayar                                                                                                
            if ($request->bayar < $kalkulasi['total']) {                                                                          
                return back()->with('error', 'Uang bayar kurang dari total tagihan!');                                            
            }                                                                                                                     
                                                                                                                                  
            $kembalian = $request->bayar - $kalkulasi['total'];                                                                   
                                                                                                                                  
            // Simpan transaksi secara atomik                                                                                     
            DB::transaction(function () use ($kalkulasi, $items, $request, $kembalian) {                                          
                                                                                                                                  
                $transaksi = Transaction::create([                                                                                
                    'user_id'   => Auth::id(),                                                                                    
                    'tanggal'   => now(),                                                                                         
                    'total'     => $kalkulasi['total'],                                                                           
                    'diskon'    => $kalkulasi['diskon'],                                                                          
                    'pajak'     => $kalkulasi['pajak'],                                                                           
                    'bayar'     => $request->bayar,                                                                               
                    'kembalian' => $kembalian,                                                                                    
                ]);                                                                                                               
                                                                                                                                  
                foreach ($items as $item) {                                                                                       
                    // Simpan detail transaksi                                                                                    
                    TransactionDetail::create([                                                                                   
                        'transaction_id' => $transaksi->id,                                                                       
                        'product_id'     => $item['product']->id,                                                                 
                        'jumlah'         => $item['qty'],                                                                         
                        'harga'          => $item['product']->harga,                                                              
                        'subtotal'       => $item['product']->harga * $item['qty'],                                               
                    ]);                                                                                                           
                                                                                                                                  
                    // Kurangi stok produk                                                                                        
                    $item['product']->decrement('stok', $item['qty']);                                                            
                }                                                                                                                 
            });                                                                                                                   
                                                                                                                                  
            $transaksiTerakhir = Transaction::with('details.product')                                                             
                                    ->where('user_id', Auth::id())                                                                
                                    ->latest()                                                                                    
                                    ->first();                                                                                    
                                                                                                                                  
            return redirect()->route('kasir.struk', $transaksiTerakhir->id);                                                      
        }      
        
         public function struk(string $id)                                                                                         
        {                                                                                                                         
            $transaksi = Transaction::with('details.product', 'user')                                                             
                            ->findOrFail($id);                                                                                    
                                                                                                                                  
            return view('kasir.struk', compact('transaksi'));                                                                     
        }                             
    }             