<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Penyimpanan foto (bukti laporan & foto profil).
 *
 * Berkas disimpan di disk "public" tetapi tidak dibuka lewat symlink.
 * Akses selalu melalui route yang memeriksa izin pengguna.
 */
class Foto
{
    public static function simpan(UploadedFile $file, string $folder): string
    {
        return $file->store($folder, 'public');
    }

    public static function hapus(?string $path): void
    {
        if (! $path) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public static function ada(?string $path): bool
    {
        return (bool) $path && Storage::disk('public')->exists($path);
    }
}
