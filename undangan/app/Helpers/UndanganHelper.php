<?php

namespace App\Helpers;

use App\Models\Pengaturan;
use Carbon\Carbon;

class UndanganHelper
{
    public static function getSetting($key, $default = '')
    {
        return Pengaturan::getValue($key, $default);
    }

    public static function hitungHariTersisa()
    {
        $tanggalAcara = self::getSetting('tanggal_acara', '2032-08-02');
        $target = Carbon::parse($tanggalAcara)->startOfDay();
        $sekarang = Carbon::now()->startOfDay();
        
        $selisih = $target->diffInDays($sekarang, false);
        return $selisih < 0 ? abs($selisih) : 0; // if target is in the future, diff is negative. So abs() it.
    }

    public static function formatTanggalIndonesia($tanggal)
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $ts = strtotime($tanggal);
        return date('j', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
    }

    public static function salamWaktu()
    {
        $jam = (int)date('H');
        if ($jam < 12) return 'Selamat pagi';
        if ($jam < 15) return 'Selamat siang';
        if ($jam < 18) return 'Selamat sore';
        return 'Selamat malam';
    }
}
