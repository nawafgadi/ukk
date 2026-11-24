<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Setting;
use App\Services\PosService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Uji 1: Admin bisa memperbarui pengaturan pajak dan diskon.
     */
    public function test_admin_bisa_memperbarui_pengaturan_diskon_dan_pajak(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $payload = [
            'diskon_tingkat_1_min'    => 60000,
            'diskon_tingkat_1_persen' => 8,
            'diskon_tingkat_2_min'    => 120000,
            'diskon_tingkat_2_persen' => 18,
            'pajak_tingkat_1_persen'  => 11,
            'pajak_tingkat_2_min'     => 150000,
            'pajak_tingkat_2_persen'  => 22,
        ];

        $response = $this->actingAs($admin)->post('/settings', $payload);

        $response->assertSessionHas('success');
        $this->assertEquals(60000, Setting::get('diskon_tingkat_1_min'));
        $this->assertEquals(8, Setting::get('diskon_tingkat_1_persen'));
        $this->assertEquals(120000, Setting::get('diskon_tingkat_2_min'));
        $this->assertEquals(18, Setting::get('diskon_tingkat_2_persen'));
        $this->assertEquals(11, Setting::get('pajak_tingkat_1_persen'));
        $this->assertEquals(150000, Setting::get('pajak_tingkat_2_min'));
        $this->assertEquals(22, Setting::get('pajak_tingkat_2_persen'));

        // Cek apakah PosService langsung mengikuti aturan baru
        $pos = new PosService();
        $this->assertEquals((int)(120000 * 0.18), $pos->hitungDiskon(120000));
    }

    /**
     * Uji 2: Kasir tidak memiliki akses untuk mengubah pengaturan (dibatasi middleware admin).
     */
    public function test_kasir_tidak_bisa_mengubah_pengaturan(): void
    {
        $kasir = User::factory()->create([
            'role' => 'kasir',
        ]);

        $response = $this->actingAs($kasir)->post('/settings', [
            'diskon_tingkat_1_min'    => 50000,
            'diskon_tingkat_1_persen' => 10,
            'diskon_tingkat_2_min'    => 100000,
            'diskon_tingkat_2_persen' => 15,
            'pajak_tingkat_1_persen'  => 10,
            'pajak_tingkat_2_min'     => 100000,
            'pajak_tingkat_2_persen'  => 20,
        ]);

        $response->assertRedirect('/login');
    }
}
