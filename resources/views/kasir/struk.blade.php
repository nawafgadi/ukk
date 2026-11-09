<!DOCTYPE html>                                                                                                               
    <html lang="id">                                                                                                              
    <head>                                                                                                                        
        <meta charset="UTF-8">                                                                                                    
        <meta name="viewport" content="width=device-width, initial-scale=1.0">                                                    
        <title>Struk Transaksi - RestoKita POS</title>                                                                            
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">                    
        <style>                                                                                                                   
            @media print {                                                                                                        
                .no-print { display: none !important; }                                                                           
                body { background: white !important; }                                                                            
            }                                                                                                                     
        </style>                                                                                                                  
    </head>                                                                                                                       
    <body class="bg-light">                                                                                                       
                                                                                                                                  
    <div class="container py-5">                                                                                                  
        <div class="row justify-content-center">                                                                                  
            <div class="col-md-5">                                                                                                
                                                                                                                                  
                <div class="card shadow">                                                                                         
                    <div class="card-body p-4">                                                                                   
                                                                                                                                  
                        {{-- Header Struk --}}                                                                                    
                        <div class="text-center mb-3">                                                                            
                            <h4 class="fw-bold">🍽️ RestoKita POS</h4>                                                             
                            <small class="text-muted">{{ $transaksi->tanggal }}</small><br>                                       
                            <small class="text-muted">Kasir: {{ $transaksi->user->name }}</small>                                 
                        </div>                                                                                                    
                                                                                                                                  
                        <hr>                                                                                                      
                                                                                                                                  
                        {{-- Rincian Item --}}                                                                                    
                        <table class="table table-sm">                                                                            
                            <thead>                                                                                               
                                <tr>                                                                                              
                                    <th>Produk</th>                                                                               
                                    <th class="text-center">Qty</th>                                                              
                                    <th class="text-end">Subtotal</th>                                                            
                                </tr>                                                                                             
                            </thead>                                                                                              
                            <tbody>                                                                                               
                                @foreach ($transaksi->details as $detail)                                                         
                                    <tr>                                                                                          
                                        <td>                                                                                      
                                            {{ $detail->product->nama_produk }}<br>                                               
                                            <small class="text-muted">                                                            
                                                Rp {{ number_format($detail->harga, 0, ',', '.') }}                               
                                            </small>                                                                              
                                        </td>                                                                                     
                                        <td class="text-center">{{ $detail->jumlah }}</td>                                        
                                        <td class="text-end">                                                                     
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}                                
                                        </td>                                                                                     
                                    </tr>                                                                                         
                                @endforeach                                                                                       
                            </tbody>                                                                                              
                        </table>                                                                                                  
                                                                                                                                  
                        <hr>                                                                                                      
                                                                                                                                  
                        {{-- Kalkulasi --}}                                                                                       
                        <table class="table table-sm table-borderless">                                                           
                            <tr>                                                                                                  
                                <td>Subtotal</td>                                                                                 
                                <td class="text-end">                                                                             
                                    Rp {{ number_format($transaksi->details->sum('subtotal'), 0, ',', '.') }}                     
                                </td>                                                                                             
                            </tr>                                                                                                 
                            <tr class="text-success">                                                                             
                                <td>Diskon</td>                                                                                   
                                <td class="text-end">                                                                             
                                    - Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}                                     
                                </td>                                                                                             
                            </tr>                                                                                                 
                            <tr class="text-muted">                                                                               
                                <td>Pajak (10%)</td>                                                                              
                                <td class="text-end">                                                                             
                                    Rp {{ number_format($transaksi->pajak, 0, ',', '.') }}                                        
                                </td>                                                                                             
                            </tr>                                                                                                 
                            <tr class="fw-bold fs-5">                                                                             
                                <td>Total</td>                                                                                    
                                <td class="text-end">                                                                             
                                    Rp {{ number_format($transaksi->total, 0, ',', '.') }}                                        
                                </td>                                                                                             
                            </tr>                                                                                                 
                            <tr>                                                                                                  
                                <td>Bayar</td>                                                                                    
                                <td class="text-end">                                                                             
                                    Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}                                        
                                </td>                                                                                             
                            </tr>                                                                                                 
                            <tr class="text-primary fw-bold">                                                                     
                                <td>Kembalian</td>                                                                                
                                <td class="text-end">                                                                             
                                    Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}                                    
                                </td>                                                                                             
                            </tr>                                                                                                 
                        </table>                                                                                                  
                                                                                                                                  
                        <hr>                                                                                                      
                        <p class="text-center text-muted mb-0">                                                                   
                            Terima kasih telah berkunjung! 🙏                                                                     
                        </p>                                                                                                      
                                                                                                                                  
                    </div>                                                                                                        
                </div>                                                                                                            
                                                                                                                                  
                {{-- Tombol --}}                                                                                                  
                <div class="d-flex gap-2 mt-3 no-print">                                                                          
                    <button onclick="window.print()"                                                                              
                            class="btn btn-secondary flex-fill">                                                                  
                        🖨️ Cetak Struk                                                                                            
                    </button>                                                                                                     
                    <a href="{{ route('kasir.index') }}"                                                                          
                       class="btn btn-primary flex-fill">                                                                         
                        ➕ Transaksi Baru                                                                                         
                    </a>                                                                                                          
                </div>                                                                                                            
                                                                                                                                  
            </div>                                                                                                                
        </div>                                                                                                                    
    </div>                                                                                                                        
                                                                                                                                  
    </body>                                                                                                                       
    </html>               