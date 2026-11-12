<?php                                                                                                                         
                                                                                                                                  
    namespace App\Http\Controllers;                                                                                               
                                                                                                                                  
    use Illuminate\Http\Request;                                                                                                  
    use Illuminate\Support\Facades\Storage;                                                                                       
    use App\Models\Product;                                                                                                       
                                                                                                                                  
    class ProductController extends Controller                                                                                    
    {                                                                                                                             
        /**                                                                                                                       
         * Menampilkan daftar semua produk.                                                                                       
         */                                                                                                                       
        public function index()                                                                                                   
        {                                                                                                                         
            $products = Product::all();                                                                                           
            return view('products.index', compact('products'));                                                                   
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * Menampilkan form tambah produk baru.                                                                                   
         */                                                                                                                       
        public function create()                                                                                                  
        {                                                                                                                         
            return view('products.create');                                                                                       
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * Menyimpan produk baru beserta fotonya ke database.                                                                     
         */                                                                                                                       
        public function store(Request $request)                                                                                   
        {                                                                                                                         
            $request->validate([                                                                                                  
                'nama_produk' => 'required|string|max:100',
                'kategori'    => 'required|string|max:50',                                                                       
                'harga'       => 'required|integer|min:0',                                                                        
                'stok'        => 'required|integer|min:0',                                                                        
                'foto'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Maks 2MB                                   
            ]);                                                                                                                   
                                                                                                                                  
            $data = $request->only(['nama_produk', 'kategori', 'harga', 'stok']);                                                             
                                                                                                                                  
            // Jika user mengunggah foto                                                                                          
            if ($request->hasFile('foto')) {                                                                                      
                $data['foto'] = $request->file('foto')->store('products', 'public');                                              
            }                                                                                                                     
                                                                                                                                  
            Product::create($data);                                                                                               
                                                                                                                                  
            return redirect()->route('products.index')                                                                            
                             ->with('success', 'Produk berhasil ditambahkan!');                                                   
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * Menampilkan form edit produk.                                                                                          
         */                                                                                                                       
        public function edit(string $id)                                                                                          
        {                                                                                                                         
            $product = Product::findOrFail($id);                                                                                  
            return view('products.edit', compact('product'));                                                                     
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * Memperbarui data produk dan mengganti foto jika ada foto baru.                                                         
         */                                                                                                                       
        public function update(Request $request, string $id)                                                                      
        {                                                                                                                         
            $request->validate([                                                                                                  
                'nama_produk' => 'required|string|max:100',
                'kategori'    => 'required|string|max:50',                                                                       
                'harga'       => 'required|integer|min:0',                                                                        
                'stok'        => 'required|integer|min:0',                                                                        
                'foto'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',                                               
            ]);                                                                                                                   
                                                                                                                                  
            $product = Product::findOrFail($id);                                                                                  
            $data = $request->only(['nama_produk', 'kategori', 'harga', 'stok']);                                                             
                                                                                                                                  
            // Jika ada foto baru yang diupload                                                                                   
            if ($request->hasFile('foto')) {                                                                                      
                // Hapus foto lama dari storage jika sebelumnya sudah ada                                                         
                if ($product->foto && Storage::disk('public')->exists($product->foto)) {                                          
                    Storage::disk('public')->delete($product->foto);                                                              
                }                                                                                                                 
                // Simpan foto baru                                                                                               
                $data['foto'] = $request->file('foto')->store('products', 'public');                                              
            }                                                                                                                     
                                                                                                                                  
            $product->update($data);                                                                                              
                                                                                                                                  
            return redirect()->route('products.index')                                                                            
                             ->with('success', 'Produk berhasil diperbarui!');                                                    
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * Menghapus produk beserta file fotonya dari storage.                                                                    
         */                                                                                                                       
        public function destroy(string $id)                                                                                       
        {                                                                                                                         
            $product = Product::findOrFail($id);                                                                                  
                                                                                                                                  
            // Hapus file fisik foto jika ada                                                                                     
            if ($product->foto && Storage::disk('public')->exists($product->foto)) {                                              
                Storage::disk('public')->delete($product->foto);                                                                  
            }                                                                                                                     
                                                                                                                                  
            $product->delete();                                                                                                   
                                                                                                                                  
            return redirect()->route('products.index')                                                                            
                             ->with('success', 'Produk berhasil dihapus!');                                                       
        }                                                                                                                         
    }          