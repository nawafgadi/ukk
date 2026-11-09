<!DOCTYPE html>                                                                                                               
    <html lang="id">                                                                                                              
    <head>                                                                                                                        
        <meta charset="UTF-8">                                                                                                    
        <meta name="viewport" content="width=device-width, initial-scale=1.0">                                                    
        <title>Riwayat Transaksi - RestoKita POS</title>                                                                          
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">                    
    </head>                                                                                                                       
    <body class="bg-light">                                                                                                       
                                                                                                                                  
    <nav class="navbar navbar-dark bg-dark px-4">                                                                                 
        <span class="navbar-brand">🍽️ RestoKita POS</span>                                                                        
        <div class="d-flex align-items-center gap-3">                                                                             
            <span class="text-white">{{ Auth::user()->name }}</span>                                                              
            <form action="{{ route('logout') }}" method="POST">                                                                   
                @csrf                                                                                                             
                <button class="btn btn-sm btn-outline-light">Logout</button>                                                      
            </form>                                                                                                               
        </div>                                                                                                                    
    </nav>                                                                                                                        
                                                                                                                                  
    <div class="container py-5">                                                                                                  
                                                                                                                                  
        <div class="d-flex justify-content-between align-items-center mb-4">                                                      
            <div>                                                                                                                 
                <h4>🧾 Riwayat Transaksi</h4>                                                                                     
                <p class="text-muted">Semua transaksi yang pernah terjadi</p>                                                     
            </div>                                                                                                                
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">                                                         
                ← Kembali                                                                                                         
            </a>                                                                                                                  
        </div>                                                                                                                    
                                                                                                                                  
        <div class="card shadow-sm">                                                                                              
            <div class="card-body">                                                                                               
                <table class="table table-bordered table-striped">                                                                
                    <thead class="table-dark">                                                                                    
                        <tr>                                                                                                      
                            <th>No</th>                                                                                           
                            <th>Tanggal</th>                                                                                      
                            <th>Kasir</th>                                                                                        
                            <th>Diskon</th>                                                                                       
                            <th>Pajak</th>                                                                                        
                            <th>Total</th>                                                                                        
                            <th>Bayar</th>                                                                                        
                            <th>Kembalian</th>                                                                                    
                        </tr>                                                                                                     
                    </thead>                                                                                                      
                    <tbody>                                                                                                       
                        @forelse ($transaksi as $trx)                                                                             
                            <tr>                                                                                                  
                                <td>{{ $loop->iteration }}</td>                                                                   
                                <td>{{ $trx->tanggal }}</td>                                                                      
                                <td>{{ $trx->user->name }}</td>                                                                   
                                <td class="text-success">                                                                         
                                    Rp {{ number_format($trx->diskon, 0, ',', '.') }}                                             
                                </td>                                                                                             
                                <td>Rp {{ number_format($trx->pajak, 0, ',', '.') }}</td>                                         
                                <td class="fw-bold">                                                                              
                                    Rp {{ number_format($trx->total, 0, ',', '.') }}                                              
                                </td>                                                                                             
                                <td>Rp {{ number_format($trx->bayar, 0, ',', '.') }}</td>                                         
                                <td class="text-primary">                                                                         
                                    Rp {{ number_format($trx->kembalian, 0, ',', '.') }}                                          
                                </td>                                                                                             
                            </tr>                                                                                                 
                        @empty                                                                                                    
                            <tr>                                                                                                  
                                <td colspan="8" class="text-center text-muted">                                                   
                                    Belum ada transaksi.                                                                          
                                </td>                                                                                             
                            </tr>                                                                                                 
                        @endforelse                                                                                               
                    </tbody>                                                                                                      
                </table>                                                                                                          
            </div>                                                                                                                
        </div>                                                                                                                    
                                                                                                                                  
    </div>                                                                                                                        
                                                                                                                                  
    </body>                                                                                                                       
    </html>        