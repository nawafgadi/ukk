<?php                                                                                                                         
                                                                                                                                  
    namespace App\Http\Middleware;                                                                                                
                                                                                                                                  
    use Closure;                                                                                                                  
    use Illuminate\Http\Request;                                                                                                  
    use Illuminate\Support\Facades\Auth;                                                                                          
                                                                                                                                  
    class AdminMiddleware                                                                                                         
    {                                                                                                                             
        /**                                                                                                                       
         * Cek apakah user yang login adalah admin.                                                                               
         */                                                                                                                       
        public function handle(Request $request, Closure $next)                                                                   
        {                                                                                                                         
            if (Auth::check() && Auth::user()->role === 'admin') {                                                                
                return $next($request);                                                                                           
            }                                                                                                                     
                                                                                                                                  
            return redirect()->route('login');                                                                                    
        }                                                                                                                         
    } 