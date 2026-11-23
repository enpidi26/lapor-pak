<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pesan validasi
    |--------------------------------------------------------------------------
    |
    | Ditulis dengan bahasa sehari-hari agar warga tidak bingung ketika ada
    | isian yang perlu diperbaiki.
    |
    */

    'required' => ':attribute wajib diisi.',
    'string' => ':attribute harus berupa teks.',
    'integer' => ':attribute harus berupa angka.',
    'numeric' => ':attribute harus berupa angka.',
    'boolean' => ':attribute tidak valid.',
    'email' => 'Format :attribute belum benar. Contoh: nama@email.com.',
    'unique' => ':attribute sudah dipakai.',
    'confirmed' => 'Konfirmasi :attribute belum sama.',
    'current_password' => 'Password saat ini tidak sesuai.',
    'different' => ':attribute harus berbeda dengan :other.',
    'in' => ':attribute yang dipilih tidak tersedia.',
    'exists' => ':attribute yang dipilih tidak ditemukan.',
    'image' => ':attribute harus berupa gambar.',
    'mimes' => ':attribute harus berformat: :values.',
    'file' => ':attribute harus berupa berkas.',
    'uploaded' => ':attribute gagal diunggah. Coba berkas dengan ukuran lebih kecil.',
    'date' => ':attribute bukan tanggal yang valid.',
    'date_format' => ':attribute tidak sesuai format :format.',
    'before_or_equal' => ':attribute tidak boleh melewati :date.',
    'after_or_equal' => ':attribute tidak boleh sebelum :date.',
    'before' => ':attribute harus sebelum :date.',
    'after' => ':attribute harus setelah :date.',
    'regex' => 'Format :attribute belum benar.',
    'accepted' => ':attribute harus disetujui.',
    'array' => ':attribute tidak valid.',
    'size' => [
        'string' => ':attribute harus :size karakter.',
        'numeric' => ':attribute harus bernilai :size.',
        'file' => ':attribute harus berukuran :size kilobyte.',
        'array' => ':attribute harus berisi :size item.',
    ],
    'min' => [
        'string' => ':attribute minimal :min karakter.',
        'numeric' => ':attribute minimal :min.',
        'file' => ':attribute minimal :min kilobyte.',
        'array' => ':attribute minimal berisi :min item.',
    ],
    'max' => [
        'string' => ':attribute maksimal :max karakter.',
        'numeric' => ':attribute maksimal :max.',
        'file' => 'Ukuran :attribute maksimal :max kilobyte.',
        'array' => ':attribute maksimal berisi :max item.',
    ],
    'between' => [
        'string' => ':attribute harus :min sampai :max karakter.',
        'numeric' => ':attribute harus antara :min dan :max.',
        'file' => ':attribute harus :min sampai :max kilobyte.',
    ],
    'password' => [
        'letters' => 'Password harus memuat minimal satu huruf.',
        'mixed' => 'Password harus memuat huruf besar dan huruf kecil.',
        'numbers' => 'Password harus memuat minimal satu angka.',
        'symbols' => 'Password harus memuat minimal satu simbol.',
        'uncompromised' => 'Password ini pernah bocor di internet. Gunakan password lain.',
    ],

    'custom' => [],

    /*
    |--------------------------------------------------------------------------
    | Nama atribut
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'name' => 'nama lengkap',
        'email' => 'email',
        'password' => 'password',
        'password_saat_ini' => 'password saat ini',
        'no_whatsapp' => 'nomor WhatsApp',
        'alamat' => 'alamat',
        'foto' => 'foto',
        'kategori' => 'jenis masalah',
        'judul' => 'judul laporan',
        'detail' => 'deskripsi',
        'tanggal' => 'tanggal kejadian',
        'status' => 'status',
        'tanggapan' => 'catatan',
    ],

];
