<?php                                                                                                                         
                                                                                                                                  
    namespace Database\Seeders;                                                                                                   
                                                                                                                                  
    use Illuminate\Database\Seeder;                                                                                               
    use Illuminate\Support\Facades\Hash;                                                                                          
    use App\Models\User;                                                                                                          
                                                                                                                                  
    class DatabaseSeeder extends Seeder                                                                                           
    {                                                                                                                             
        /**                                                                                                                       
         * Mengisi database dengan data awal (user admin dan kasir).                                                              
         */                                                                                                                       
        public function run(): void                                                                                               
        {                                                                                                                         
            // Buat akun Admin                                                                                                    
            User::create([                                                                                                        
                'name'     => 'Admin RestoKita',                                                                                  
                'email'    => 'admin@restokita.com',                                                                              
                'password' => Hash::make('password123'),                                                                          
                'role'     => 'admin',                                                                                            
            ]);                                                                                                                   
                                                                                                                                  
            // Buat akun Kasir                                                                                                    
            User::create([                                                                                                        
                'name'     => 'Kasir RestoKita',                                                                                  
                'email'    => 'kasir@restokita.com',                                                                              
                'password' => Hash::make('password123'),                                                                          
                'role'     => 'kasir',                                                                                            
            ]);                                                                                                                   
        }                                                                                                                         
    }  