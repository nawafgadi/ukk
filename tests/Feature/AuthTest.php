<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Uji 1: Memastikan halaman login bisa dibuka dengan sukses (Status 200).
     */
    public function test_halaman_login_bisa_dibuka(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Toko Maksum');
    }

    /**
     * Uji 2: Admin berhasil login dan diarahkan ke Dashboard.
     */
    public function test_admin_berhasil_login_dan_diarahkan_ke_dashboard(): void
    {
        $admin = User::create([
            'name'     => 'Admin Tes',
            'email'    => 'admintes@gmail.com',
            'password' => bcrypt('password123'),
            'role'     => 'admin',
        ]);

        $response = $this->post('/login', [
            'email'    => 'admintes@gmail.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect('/dashboard');
    }

    /**
     * Uji 3: Kasir berhasil login dan diarahkan ke Modul Kasir POS.
     */
    public function test_kasir_berhasil_login_dan_diarahkan_ke_kasir(): void
    {
        $kasir = User::create([
            'name'     => 'Kasir Tes',
            'email'    => 'kasirtes@gmail.com',
            'password' => bcrypt('password123'),
            'role'     => 'kasir',
        ]);

        $response = $this->post('/login', [
            'email'    => 'kasirtes@gmail.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($kasir);
        $response->assertRedirect('/kasir');
    }

    /**
     * Uji 4: Memastikan akses ditolak jika kata sandi keliru.
     */
    public function test_login_tidak_berhasil_bila_kata_sandi_keliru(): void
    {
        User::create([
            'name'     => 'User Uji',
            'email'    => 'useruji@gmail.com',
            'password' => bcrypt('password123'),
            'role'     => 'admin',
        ]);

        $response = $this->post('/login', [
            'email'    => 'useruji@gmail.com',
            'password' => 'kata-sandi-salah',
        ]);

        $this->assertGuest();
        $response->assertSessionHas('error');
    }

    /**
     * Uji 5: Memastikan pengguna dapat keluar (logout).
     */
    public function test_pengguna_dapat_logout(): void
    {
        $user = User::create([
            'name'     => 'User Aktif',
            'email'    => 'useraktif@gmail.com',
            'password' => bcrypt('password123'),
            'role'     => 'admin',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}
