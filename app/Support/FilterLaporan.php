<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

/**
 * Menyatukan cara daftar laporan dibaca, difilter, dan diringkas.
 *
 * Dipakai bersama oleh halaman warga dan admin agar perilaku pencarian,
 * filter, urutan, dan jumlah status tetap konsisten.
 */
final class FilterLaporan
{
    /**
     * @return array{q: string, status: string, kategori: string, urut: string}
     */
    public static function dariRequest(Request $request): array
    {
        return [
            'q' => trim((string) $request->query('q', '')),
            'status' => (string) $request->query('status', ''),
            'kategori' => (string) $request->query('kategori', ''),
            'urut' => (string) $request->query('urut', 'baru'),
        ];
    }

    /**
     * @param  array{q: string, status: string, kategori: string, urut: string}  $filters
     */
    public static function terapkan(Builder|Relation $query, array $filters): Builder
    {
        $builder = $query instanceof Relation ? $query->getQuery() : $query;

        return $builder
            ->dicari($filters['q'])
            ->berstatus($filters['status'] ?: null)
            ->berkategori($filters['kategori'] ?: null)
            ->urutkan($filters['urut']);
    }

    /**
     * @return array{semua: int, pending: int, proses: int, selesai: int}
     */
    public static function jumlahStatus(Builder|Relation $query): array
    {
        $builder = $query instanceof Relation ? $query->getQuery() : $query;
        $status = Statistik::status($builder);

        return [
            'semua' => $status['total'],
            'pending' => $status['pending'],
            'proses' => $status['proses'],
            'selesai' => $status['selesai'],
        ];
    }
}
