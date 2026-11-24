<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Mengambil nilai setting berdasarkan key dengan fallback nilai default.
     */
    public static function get(string $key, $default = null)
    {
        try {
            if (!Schema::hasTable('settings')) {
                return $default;
            }
            $setting = static::where('key', $key)->first();
            return $setting !== null ? $setting->value : $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    /**
     * Menyimpan atau memperbarui nilai setting.
     */
    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => (string) $value]);
    }

    /**
     * Mengambil seluruh data konfigurasi diskon dan pajak dalam bentuk array terstruktur.
     */
    public static function getAllSettings(): array
    {
        return [
            'diskon_tingkat_1_min'    => (int) static::get('diskon_tingkat_1_min', 50000),
            'diskon_tingkat_1_persen' => (int) static::get('diskon_tingkat_1_persen', 10),
            'diskon_tingkat_2_min'    => (int) static::get('diskon_tingkat_2_min', 100000),
            'diskon_tingkat_2_persen' => (int) static::get('diskon_tingkat_2_persen', 15),
            'pajak_tingkat_1_persen'  => (int) static::get('pajak_tingkat_1_persen', 10),
            'pajak_tingkat_2_min'     => (int) static::get('pajak_tingkat_2_min', 100000),
            'pajak_tingkat_2_persen'  => (int) static::get('pajak_tingkat_2_persen', 20),
        ];
    }
}
