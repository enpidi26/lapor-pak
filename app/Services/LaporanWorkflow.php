<?php

namespace App\Services;

use App\Models\Laporan;
use Illuminate\Validation\ValidationException;

/**
 * Mengatur perpindahan status laporan.
 *
 * Alurnya hanya maju satu langkah: menunggu -> diproses -> selesai.
 * Status tidak boleh dilompati atau dikembalikan agar riwayat tetap jujur.
 */
class LaporanWorkflow
{
    /** @var array<string, string|null> */
    private const BERIKUTNYA = [
        'pending' => 'proses',
        'proses' => 'selesai',
        'selesai' => null,
    ];

    public function berikutnya(string $status): ?string
    {
        return self::BERIKUTNYA[$status] ?? null;
    }

    private function bisaBerpindah(string $dari, string $ke): bool
    {
        return $this->berikutnya($dari) === $ke;
    }

    /**
     * @throws ValidationException
     */
    public function pindahkan(Laporan $laporan, string $tujuan): Laporan
    {
        if ($laporan->status === $tujuan) {
            return $laporan;
        }

        if (! $this->bisaBerpindah($laporan->status, $tujuan)) {
            throw ValidationException::withMessages([
                'status' => 'Status laporan tidak boleh dilompati. Ubah ke "'
                    .(Laporan::STATUSES[$this->berikutnya($laporan->status)] ?? '-')
                    .'" terlebih dahulu.',
            ]);
        }

        $laporan->status = $tujuan;
        $laporan->save();

        return $laporan;
    }
}
