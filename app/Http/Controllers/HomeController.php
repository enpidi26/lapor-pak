<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\User;
use App\Support\Statistik;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $query = Laporan::query();
        $status = Statistik::status($query);

        $kategori = Laporan::query()
            ->selectRaw('kategori, count(*) as jumlah')
            ->groupBy('kategori')
            ->orderByDesc('jumlah')
            ->pluck('jumlah', 'kategori')
            ->all();

        return view('home', [
            'status' => $status,
            'selesai' => Statistik::persentase($status),
            'seri' => Statistik::seri($query, 7),
            'kategori' => $kategori,
            'warga' => User::where('role', 'warga')->count(),
        ]);
    }
}
