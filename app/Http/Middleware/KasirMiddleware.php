<?php                                                                                                                         
                                                                                                                                  
    namespace App\Http\Middleware;                                                                                                
                                                                                                                                  
    use Closure;                                                                                                                  
    use Illuminate\Http\Request;                                                                                                  
    use Illuminate\Support\Facades\Auth;                                                                                          
                                                                                                                                  
    class KasirMiddleware                                                                                                         
    {                                                                                                                             
        /**                                                                                                                       
         * Cek apakah user adalah kasir atau admin.                                                                               
         */                                                                                                                       
        public function handle(Request $request, Closure $next)                                                                   
        {                                                                                                                         
            if (Auth::check() && in_array(Auth::user()->role, ['admin', 'kasir'])) {                                              
                return $next($request);                                                                                           
            }                                                                                                                     
                                                                                                                                  
            return redirect()->route('login');                                                                                    
        }                                                                                                                         
    }              