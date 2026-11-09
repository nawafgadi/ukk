<!DOCTYPE html>                                                                                                               
    <html lang="id">                                                                                                              
    <head>                                                                                                                        
        <meta charset="UTF-8">                                                                                                    
        <meta name="viewport" content="width=device-width, initial-scale=1.0">                                                    
        <title>Login - RestoKita POS</title>                                                                                      
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">                    
    </head>                                                                                                                       
    <body class="bg-light">                                                                                                       
                                                                                                                                  
    <div class="container py-5">                                                                                                  
        <div class="row justify-content-center">                                                                                  
            <div class="col-md-4">                                                                                                
                                                                                                                                  
                <div class="card shadow">                                                                                         
                    <div class="card-body p-4">                                                                                   
                        <h4 class="text-center mb-4">🍽️ RestoKita POS</h4>                                                        
                                                                                                                                  
                        @if (session('error'))                                                                                    
                            <div class="alert alert-danger">                                                                      
                                {{ session('error') }}                                                                            
                            </div>                                                                                                
                        @endif                                                                                                    
                                                                                                                                  
                        <form action="{{ route('login.post') }}" method="POST">                                                   
                            @csrf                                                                                                 
                                                                                                                                  
                            <div class="mb-3">                                                                                    
                                <label class="form-label">Email</label>                                                           
                                <input type="email" name="email"                                                                  
                                       class="form-control @error('email') is-invalid @enderror"                                  
                                       value="{{ old('email') }}" required>                                                       
                                @error('email')                                                                                   
                                    <div class="invalid-feedback">{{ $message }}</div>                                            
                                @enderror                                                                                         
                            </div>                                                                                                
                                                                                                                                  
                            <div class="mb-3">                                                                                    
                                <label class="form-label">Password</label>                                                        
                                <input type="password" name="password"                                                            
                                       class="form-control" required>                                                             
                            </div>                                                                                                
                                                                                                                                  
                            <button type="submit" class="btn btn-primary w-100">                                                  
                                Masuk                                                                                             
                            </button>                                                                                             
                        </form>                                                                                                   
                                                                                                                                  
                    </div>                                                                                                        
                </div>                                                                                                            
                                                                                                                                  
            </div>                                                                                                                
        </div>                                                                                                                    
    </div>                                                                                                                        
                                                                                                                                  
    </body>                                                                                                                       
    </html>       u