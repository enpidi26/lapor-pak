<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\User;
use App\Support\Statistik;
use App\Support\Waktu;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $hari = (int) $request->integer('hari', 7);
        $hari = in_array($hari, Statistik::RENTANG, true) ? $hari : 7;

        $query = Laporan::query();
        $status = Statistik::status($query);
        $matriks = Statistik::jumlahHarian($query, 30);
        $tertua = Laporan::where('status', 'pending')->oldest('created_at')->first();

        return view('admin.dashboard', [
            'hari' => $hari,
            'status' => $status,
            'selesai' => Statistik::persentase($status),
            'delta' => Statistik::delta($query),
            'seri' => Statistik::seri($query, $hari),
            'matriks' => $matriks,
            'matriksTotal' => array_sum(array_column($matriks, 'jumlah')),
            'sparkline' => Statistik::selesaiHarian($query, 30),
            'terbaru' => Laporan::with('pelapor')->latest('created_at')->latest('id')->limit(6)->get(),
            'tertua' => $tertua,
            'usiaTertua' => $tertua ? Waktu::lamaHari($tertua->created_at) : 0,
            'warga' => User::where('role', 'warga')->count(),
        ]);
    }
}
