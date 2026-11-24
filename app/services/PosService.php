<?php

namespace App\Services;

use App\Models\Setting;

class PosService
{
    /**
     * Menghitung diskon bertingkat berdasarkan subtotal (membaca konfigurasi dinamis).
     */
    public function hitungDiskon(int $subtotal): int
    {
        $settings = Setting::getAllSettings();
        $minTingkat2    = $settings['diskon_tingkat_2_min'];
        $persenTingkat2 = $settings['diskon_tingkat_2_persen'] / 100;
        $minTingkat1    = $settings['diskon_tingkat_1_min'];
        $persenTingkat1 = $settings['diskon_tingkat_1_persen'] / 100;

        if ($subtotal >= $minTingkat2) {
            return (int) ($subtotal * $persenTingkat2);
        } elseif ($subtotal >= $minTingkat1) {
            return (int) ($subtotal * $persenTingkat1);
        } else {
            return 0; // Tidak ada diskon
        }
    }

    /**
     * Menghitung total akhir transaksi termasuk pajak bertingkat (membaca konfigurasi dinamis).
     */
    public function hitungTotal(int $subtotal): array
    {
        $settings = Setting::getAllSettings();
        $diskon = $this->hitungDiskon($subtotal);
        $dpp    = $subtotal - $diskon;

        $pajakTingkat2Min    = $settings['pajak_tingkat_2_min'];
        $pajakTingkat2Persen = $settings['pajak_tingkat_2_persen'];
        $pajakTingkat1Persen = $settings['pajak_tingkat_1_persen'];

        // Logika Pajak Bertingkat Dinamis:
        $persenPajak = ($dpp >= $pajakTingkat2Min) ? $pajakTingkat2Persen : $pajakTingkat1Persen;
        $pajak       = (int) ($dpp * ($persenPajak / 100));
        $total       = $dpp + $pajak;

        return [
            'subtotal'     => $subtotal,
            'diskon'       => $diskon,
            'dpp'          => $dpp,
            'persen_pajak' => $persenPajak,
            'pajak'        => $pajak,
            'total'        => $total,
        ];
    }
}
