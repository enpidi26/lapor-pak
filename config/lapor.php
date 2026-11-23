<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Identitas wilayah
    |--------------------------------------------------------------------------
    |
    | Dipakai pada halaman publik, halaman bantuan, dan pesan ke warga.
    | Ubah lewat .env agar tidak perlu menyentuh kode.
    |
    */

    'rt' => env('RT_NAMA', 'RT 04 / RW 07'),
    'kelurahan' => env('RT_KELURAHAN', 'Kelurahan Sukamaju'),
    'whatsapp' => env('RT_WHATSAPP', '6281234567890'),
    'jam_tinjau' => (int) env('RT_JAM_TINJAU', 24),

    /*
    |--------------------------------------------------------------------------
    | Akun administrator
    |--------------------------------------------------------------------------
    */

    'admin_email' => env('ADMIN_EMAIL', 'admin@laporpak.id'),
    'admin_password' => env('ADMIN_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Bantuan pratinjau (hanya untuk lingkungan lokal)
    |--------------------------------------------------------------------------
    |
    | Menampilkan tombol masuk cepat memakai akun demo. Tidak pernah aktif
    | ketika APP_ENV bukan "local".
    |
    */

    'demo_aktif' => (bool) env('LAPOR_DEMO_AKTIF', false),
    'demo_warga_email' => env('DEMO_WARGA_EMAIL', 'budi@laporpak.id'),
    'demo_warga_password' => env('DEMO_WARGA_PASSWORD', 'wargaLapor1'),

    /*
    |--------------------------------------------------------------------------
    | Batasan unggahan
    |--------------------------------------------------------------------------
    */

    'foto_maks_kb' => (int) env('LAPOR_FOTO_MAKS_KB', 2048),

];
