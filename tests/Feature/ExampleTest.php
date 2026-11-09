<?php                                                                                                                         
                                                                                                                                  
    namespace Tests\Feature;                                                                                                      
                                                                                                                                  
    use Tests\TestCase;                                                                                                           
                                                                                                                                  
    class ExampleTest extends TestCase                                                                                            
    {                                                                                                                             
        /**                                                                                                                       
         * Halaman login bisa diakses.                                                                                            
         */                                                                                                                       
        public function test_halaman_login_bisa_diakses(): void                                                                   
        {                                                                                                                         
            $response = $this->get('/login');                                                                                     
            $response->assertStatus(200);                                                                                         
        }                                                                                                                         
    }          