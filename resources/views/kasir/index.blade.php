 <!DOCTYPE html>                                                                                                               
    <html lang="id">                                                                                                              
    <head>                                                                                                                        
        <meta charset="UTF-8">                                                                                                    
        <meta name="viewport" content="width=device-width, initial-scale=1.0">                                                    
        <title>Kasir - RestoKita POS</title>                                                                                      
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">                    
    </head>                                                                                                                       
    <body class="bg-light">                                                                                                       
                                                                                                                                  
    <nav class="navbar navbar-dark bg-dark px-4">                                                                                 
        <span class="navbar-brand">🍽️ RestoKita POS — Kasir</span>                                                                
        <div class="d-flex align-items-center gap-3">                                                                             
            <span class="text-white">{{ Auth::user()->name }}</span>                                                              
            <form action="{{ route('logout') }}" method="POST">                                                                   
                @csrf                                                                                                             
                <button class="btn btn-sm btn-outline-light">Logout</button>                                                      
            </form>                                                                                                               
        </div>                                                                                                                    
    </nav>                                                                                                                        
                                                                                                                                  
    <div class="container-fluid py-4">                                                                                            
        @if (session('error'))                                                                                                    
            <div class="alert alert-danger">{{ session('error') }}</div>                                                          
        @endif                                                                                                                    
                                                                                                                                  
        <div class="row g-4">                                                                                                     
                                                                                                                                  
            {{-- KIRI: Daftar Produk --}}                                                                                         
            <div class="col-md-7">                                                                                                
                <h5 class="mb-3">📦 Pilih Produk</h5>                                                                             
                <div class="row g-3">                                                                                             
                    @foreach ($products as $product)                                                                              
                        <div class="col-md-4">                                                                                    
                            <div class="card shadow-sm h-100">                                                                    
                                <div class="card-body">                                                                           
                                    <h6 class="card-title">{{ $product->nama_produk }}</h6>                                       
                                    <p class="text-muted mb-1">                                                                   
                                        Rp {{ number_format($product->harga, 0, ',', '.') }}                                      
                                    </p>                                                                                          
                                    <small class="text-secondary">Stok: {{ $product->stok }}</small>                              
                                </div>                                                                                            
                                <div class="card-footer">                                                                         
                                    <button class="btn btn-primary btn-sm w-100"                                                  
                                        onclick="tambahKeKeranjang(                                                               
                                            {{ $product->id }},                                                                   
                                            '{{ $product->nama_produk }}',                                                        
                                            {{ $product->harga }},                                                                
                                            {{ $product->stok }}                                                                  
                                        )">                                                                                       
                                        + Tambah                                                                                  
                                    </button>                                                                                     
                                </div>                                                                                            
                            </div>                                                                                                
                        </div>                                                                                                    
                    @endforeach                                                                                                   
                </div>                                                                                                            
            </div>                                                                                                                
                                                                                                                                  
            {{-- KANAN: Keranjang & Kalkulasi --}}                                                                                
            <div class="col-md-5">                                                                                                
                <h5 class="mb-3">🛒 Keranjang</h5>                                                                                
                                                                                                                                  
                <form action="{{ route('kasir.store') }}" method="POST" id="formTransaksi">                                       
                    @csrf                                                                                                         
                                                                                                                                  
                    <div class="card shadow-sm mb-3">                                                                             
                        <div class="card-body p-0">                                                                               
                            <table class="table table-bordered mb-0" id="tabelKeranjang">                                         
                                <thead class="table-dark">                                                                        
                                    <tr>                                                                                          
                                        <th>Produk</th>                                                                           
                                        <th>Harga</th>                                                                            
                                        <th>Qty</th>                                                                              
                                        <th>Subtotal</th>                                                                         
                                        <th></th>                                                                                 
                                    </tr>                                                                                         
                                </thead>                                                                                          
                                <tbody id="isiKeranjang">                                                                         
                                    <tr id="keranjangKosong">                                                                     
                                        <td colspan="5" class="text-center text-muted py-3">                                      
                                            Keranjang masih kosong                                                                
                                        </td>                                                                                     
                                    </tr>                                                                                         
                                </tbody>                                                                                          
                            </table>                                                                                              
                        </div>                                                                                                    
                    </div>                                                                                                        
                                                                                                                                  
                    {{-- Ringkasan Kalkulasi --}}                                                                                 
                    <div class="card shadow-sm mb-3">                                                                             
                        <div class="card-body">                                                                                   
                            <div class="d-flex justify-content-between">                                                          
                                <span>Subtotal</span>                                                                             
                                <span id="txtSubtotal">Rp 0</span>                                                                
                            </div>                                                                                                
                            <div class="d-flex justify-content-between text-success">                                             
                                <span>Diskon (<span id="txtPctDiskon">0</span>%)</span>                                           
                                <span id="txtDiskon">- Rp 0</span>                                                                
                            </div>                                                                                                
                            <div class="d-flex justify-content-between text-muted">                                               
                                <span>Pajak (10%)</span>                                                                          
                                <span id="txtPajak">Rp 0</span>                                                                   
                            </div>                                                                                                
                            <hr>                                                                                                  
                            <div class="d-flex justify-content-between fw-bold fs-5">                                             
                                <span>Total</span>                                                                                
                                <span id="txtTotal">Rp 0</span>                                                                   
                            </div>                                                                                                
                        </div>                                                                                                    
                    </div>                                                                                                        
                                                                                                                                  
                    {{-- Input Bayar --}}                                                                                         
                    <div class="mb-3">                                                                                            
                        <label class="form-label fw-bold">Uang Bayar (Rp)</label>                                                 
                        <input type="number" name="bayar" id="inputBayar"                                                         
                               class="form-control" min="0"                                                                       
                               oninput="hitungKembalian()" placeholder="0">                                                       
                        <input type="hidden" name="bayar" id="hiddenBayar">                                                       
                    </div>                                                                                                        
                                                                                                                                  
                    <div class="card bg-info text-white mb-3 p-3">                                                                
                        <div class="d-flex justify-content-between fw-bold fs-5">                                                 
                            <span>Kembalian</span>                                                                                
                            <span id="txtKembalian">Rp 0</span>                                                                   
                        </div>                                                                                                    
                    </div>                                                                                                        
                                                                                                                                  
                    <button type="submit" class="btn btn-success w-100 btn-lg">                                                   
                        ✅ Bayar & Simpan Transaksi                                                                               
                    </button>                                                                                                     
                </form>                                                                                                           
            </div>                                                                                                                
                                                                                                                                  
        </div>                                                                                                                    
    </div>                                                                                                                        
                                                                                                                                  
    <script>                                                                                                                      
        // Menyimpan data keranjang                                                                                               
        let keranjang = {};                                                                                                       
                                                                                                                                  
        function formatRupiah(angka) {                                                                                            
            return 'Rp ' + angka.toLocaleString('id-ID');                                                                         
        }                                                                                                                         
                                                                                                                                  
        function tambahKeKeranjang(id, nama, harga, stok) {                                                                       
            if (keranjang[id]) {                                                                                                  
                if (keranjang[id].qty >= stok) {                                                                                  
                    alert('Stok ' + nama + ' tidak cukup!');                                                                      
                    return;                                                                                                       
                }                                                                                                                 
                keranjang[id].qty++;                                                                                              
            } else {                                                                                                              
                keranjang[id] = { id, nama, harga, stok, qty: 1 };                                                                
            }                                                                                                                     
            renderKeranjang();                                                                                                    
        }                                                                                                                         
                                                                                                                                  
        function ubahQty(id, qty) {                                                                                               
            qty = parseInt(qty);                                                                                                  
            if (qty < 1) qty = 1;                                                                                                 
            if (qty > keranjang[id].stok) {                                                                                       
                alert('Stok tidak cukup!');                                                                                       
                qty = keranjang[id].stok;                                                                                         
            }                                                                                                                     
            keranjang[id].qty = qty;                                                                                              
            renderKeranjang();                                                                                                    
        }                                                                                                                         
                                                                                                                                  
        function hapusDariKeranjang(id) {                                                                                         
            delete keranjang[id];                                                                                                 
            renderKeranjang();                                                                                                    
        }                                                                                                                         
                                                                                                                                  
        function renderKeranjang() {                                                                                              
            const tbody = document.getElementById('isiKeranjang');                                                                
            tbody.innerHTML = '';                                                                                                 
                                                                                                                                  
            // Hapus semua hidden input items lama                                                                                
            document.querySelectorAll('.item-input').forEach(el => el.remove());                                                  
                                                                                                                                  
            let subtotal = 0;                                                                                                     
            let index    = 0;                                                                                                     
                                                                                                                                  
            if (Object.keys(keranjang).length === 0) {                                                                            
                tbody.innerHTML = `<tr id="keranjangKosong">                                                                      
                    <td colspan="5" class="text-center text-muted py-3">Keranjang masih kosong</td>                               
                </tr>`;                                                                                                           
            }                                                                                                                     
                                                                                                                                  
            for (const id in keranjang) {                                                                                         
                const item      = keranjang[id];                                                                                  
                const itemTotal = item.harga * item.qty;                                                                          
                subtotal       += itemTotal;                                                                                      
                                                                                                                                  
                tbody.innerHTML += `                                                                                              
                    <tr>                                                                                                          
                        <td>${item.nama}</td>                                                                                     
                        <td>${formatRupiah(item.harga)}</td>                                                                      
                        <td>                                                                                                      
                            <input type="number" value="${item.qty}" min="1"                                                      
                                   max="${item.stok}"                                                                             
                                   class="form-control form-control-sm"                                                           
                                   style="width:65px"                                                                             
                                   onchange="ubahQty(${id}, this.value)">                                                         
                        </td>                                                                                                     
                        <td>${formatRupiah(itemTotal)}</td>                                                                       
                        <td>                                                                                                      
                            <button type="button" class="btn btn-danger btn-sm"                                                   
                                onclick="hapusDariKeranjang(${id})">✕</button>                                                    
                        </td>                                                                                                     
                    </tr>`;                                                                                                       
                                                                                                                                  
                // Tambahkan hidden input untuk dikirim ke server                                                                 
                const form = document.getElementById('formTransaksi');                                                            
                ['id', 'qty'].forEach(field => {                                                                                  
                    const input     = document.createElement('input');                                                            
                    input.type      = 'hidden';                                                                                   
                    input.name      = `items[${index}][${field}]`;                                                                
                    input.value     = field === 'id' ? item.id : item.qty;                                                        
                    input.className = 'item-input';                                                                               
                    form.appendChild(input);                                                                                      
                });                                                                                                               
                                                                                                                                  
                index++;                                                                                                          
            }                                                                                                                     
                                                                                                                                  
            hitungKalkulasi(subtotal);                                                                                            
        }                                                                                                                         
                                                                                                                                  
        function hitungKalkulasi(subtotal) {                                                                                      
            // Hitung diskon                                                                                                      
            let pctDiskon = 10;                                                                                                    
            let diskon    = 15;                                                                                                    
                                                                                                                                  
            if (subtotal >= 50000) {                                                                                             
                pctDiskon = 15;                                                                                                   
                diskon    = Math.floor(subtotal * 0.15);                                                                          
            } else if (subtotal >= 50000) {                                                                                       
                pctDiskon = 10;                                                                                                   
                diskon    = Math.floor(subtotal * 0.10);                                                                          
            }                                                                                                                     
                                                                                                                                  
            const dpp   = subtotal - diskon;                                                                                      
            const pajak = Math.floor(dpp * 0.10);                                                                                 
            const total = dpp + pajak;                                                                                            
                                                                                                                                  
            document.getElementById('txtSubtotal').textContent  = formatRupiah(subtotal);                                         
            document.getElementById('txtPctDiskon').textContent = pctDiskon;                                                      
            document.getElementById('txtDiskon').textContent    = '- ' + formatRupiah(diskon);                                    
            document.getElementById('txtPajak').textContent     = formatRupiah(pajak);                                            
            document.getElementById('txtTotal').textContent     = formatRupiah(total);                                            
                                                                                                                                  
            // Simpan total ke variabel global                                                                                    
            window.totalAkhir = total;                                                                                            
            hitungKembalian();                                                                                                    
        }                                                                                                                         
                                                                                                                                  
        function hitungKembalian() {                                                                                              
            const bayar      = parseInt(document.getElementById('inputBayar').value) || 0;                                        
            const kembalian  = bayar - (window.totalAkhir || 0);                                                                  
                                                                                                                                  
            document.getElementById('txtKembalian').textContent = formatRupiah(Math.max(kembalian, 0));                           
            document.getElementById('hiddenBayar').value        = bayar;                                                          
        }                                                                                                                         
    </script>                                                                                                                     
                                                                                                                                  
    </body>                                                                                                                       
    </html>   