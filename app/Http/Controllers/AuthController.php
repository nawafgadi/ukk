<?php                                                                                                                         
                                                                                                                                  
    namespace App\Http\Controllers;                                                                                               
                                                                                                                                  
    use Illuminate\Http\Request;                                                                                                  
    use Illuminate\Support\Facades\Auth;                                                                                          
                                                                                                                                  
    class AuthController extends Controller                                                                                       
    {                                                                                                                             
        /**                                                                                                                       
         * Menampilkan halaman form login.                                                                                        
         */                                                                                                                       
        public function login()                                                                                                   
        {                                                                                                                         
            return view('auth.login');                                                                                            
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * Memproses data login dari form.                                                                                        
         */                                                                                                                       
        public function doLogin(Request $request)                                                                                 
        {                                                                                                                         
            $request->validate([                                                                                                  
                'email'    => 'required|email',                                                                                   
                'password' => 'required',                                                                                         
            ]);                                                                                                                   
                                                                                                                                  
            $credentials = $request->only('email', 'password');                                                                   
                                                                                                                                  
            if (Auth::attempt($credentials)) {                                                                                    
                $request->session()->regenerate();                                                                                
                                                                                                                                  
                // Cek role, arahkan ke halaman yang sesuai                                                                       
                if (Auth::user()->role === 'admin') {                                                                             
                    return redirect()->route('dashboard');                                                                        
                } else {                                                                                                          
                    return redirect()->route('kasir.index');                                                                      
                }                                                                                                                 
            }                                                                                                                     
                                                                                                                                  
            return back()->with('error', 'Email atau password salah!');                                                           
        }                                                                                                                         
                                                                                                                                  
        /**                                                                                                                       
         * Menghapus sesi login (logout).                                                                                         
         */                                                                                                                       
        public function logout(Request $request)                                                                                  
        {                                                                                                                         
            Auth::logout();                                                                                                       
            $request->session()->invalidate();                                                                                    
            $request->session()->regenerateToken();                                                                               
                                                                                                                                  
            return redirect()->route('login');                                                                                    
        }                                                                                                                         
    }                                                                                                                             
  