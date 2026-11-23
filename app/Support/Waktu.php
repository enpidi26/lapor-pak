<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class Waktu
{
    public static function tanggal(?Carbon $tanggal): string
    {
        return $tanggal ? $tanggal->copy()->locale('id')->translatedFormat('j M Y') : '-';
    }

    public static function tanggalPanjang(?Carbon $tanggal): string
    {
        return $tanggal ? $tanggal->copy()->locale('id')->translatedFormat('l, j F Y') : '-';
    }

    public static function tanggalJam(?Carbon $tanggal): string
    {
        return $tanggal ? $tanggal->copy()->locale('id')->translatedFormat('j M Y, H.i').' WIB' : '-';
    }

    public static function relatif(?Carbon $tanggal): string
    {
        if (! $tanggal) {
            return '-';
        }

        return $tanggal->copy()->locale('id')->diffForHumans();
    }

    public static function lamaHari(?Carbon $sejak): int
    {
        if (! $sejak) {
            return 0;
        }

        return (int) $sejak->copy()->startOfDay()->diffInDays(now()->startOfDay());
    }
}
