<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * Statistik ringkas untuk papan ringkasan.
 *
 * Semua metode menerima query builder sehingga bisa dipakai untuk laporan
 * satu warga maupun seluruh laporan tanpa menduplikasi logika.
 */
class Statistik
{
    public const RENTANG = [7, 14, 30];

    /**
     * @return array{pending:int, proses:int, selesai:int, total:int}
     */
    public static function status(Builder $query): array
    {
        $jumlah = (clone $query)
            ->selectRaw('status, count(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        return [
            'pending' => (int) ($jumlah['pending'] ?? 0),
            'proses' => (int) ($jumlah['proses'] ?? 0),
            'selesai' => (int) ($jumlah['selesai'] ?? 0),
            'total' => (int) $jumlah->sum(),
        ];
    }

    /**
     * Deret harian berisi jumlah laporan per status.
     *
     * @return array<int, array{tanggal: Carbon, label: string, labelPanjang: string, pending: int, proses: int, selesai: int, total: int}>
     */
    public static function seri(Builder $query, int $hari): array
    {
        $hari = in_array($hari, self::RENTANG, true) ? $hari : 7;
        $mulai = now()->subDays($hari - 1)->startOfDay();

        $data = (clone $query)
            ->where('created_at', '>=', $mulai)
            ->get(['created_at', 'status']);

        $seri = [];

        for ($i = 0; $i < $hari; $i++) {
            $tanggal = $mulai->copy()->addDays($i);
            $harian = $data->filter(fn ($laporan) => $laporan->created_at->isSameDay($tanggal));

            $seri[] = [
                'tanggal' => $tanggal,
                'label' => $tanggal->copy()->locale('id')->translatedFormat('D'),
                'labelPanjang' => $tanggal->copy()->locale('id')->translatedFormat('j M'),
                'pending' => $harian->where('status', 'pending')->count(),
                'proses' => $harian->where('status', 'proses')->count(),
                'selesai' => $harian->where('status', 'selesai')->count(),
                'total' => $harian->count(),
            ];
        }

        return $seri;
    }

    /**
     * Jumlah laporan per hari untuk matriks titik.
     *
     * @return array<int, array{tanggal: Carbon, label: string, jumlah: int}>
     */
    public static function jumlahHarian(Builder $query, int $hari = 30): array
    {
        return array_map(
            fn (array $baris) => [
                'tanggal' => $baris['tanggal'],
                'label' => $baris['label'],
                'jumlah' => $baris['total'],
            ],
            self::seri($query, $hari)
        );
    }

    /**
     * Laporan yang selesai per hari (memakai waktu pembaruan terakhir).
     *
     * @return array<int, int>
     */
    public static function selesaiHarian(Builder $query, int $hari = 30): array
    {
        $mulai = now()->subDays($hari - 1)->startOfDay();

        $data = (clone $query)
            ->where('status', 'selesai')
            ->where('updated_at', '>=', $mulai)
            ->get(['updated_at']);

        $hasil = [];

        for ($i = 0; $i < $hari; $i++) {
            $tanggal = $mulai->copy()->addDays($i);
            $hasil[] = $data->filter(fn ($laporan) => $laporan->updated_at->isSameDay($tanggal))->count();
        }

        return $hasil;
    }

    /**
     * @param  array{pending:int, proses:int, selesai:int, total:int}  $status
     */
    public static function persentase(array $status): int
    {
        return $status['total'] > 0
            ? (int) round($status['selesai'] / $status['total'] * 100)
            : 0;
    }

    /**
     * Selisih tingkat penyelesaian bulan ini dibanding bulan lalu.
     * Null jika bulan lalu belum punya data pembanding.
     */
    public static function delta(Builder $query): ?int
    {
        $sekarang = now();
        $awalBulan = $sekarang->copy()->startOfMonth();
        $bulanLalu = $sekarang->copy()->subMonthNoOverflow();

        $ini = self::persentase(self::status(
            (clone $query)->where('created_at', '>=', $awalBulan)
        ));

        $laluQuery = (clone $query)->whereBetween('created_at', [
            $bulanLalu->copy()->startOfMonth(),
            $bulanLalu->copy()->endOfMonth(),
        ]);

        $laluStatus = self::status($laluQuery);

        if ($laluStatus['total'] === 0) {
            return null;
        }

        return $ini - self::persentase($laluStatus);
    }
}
