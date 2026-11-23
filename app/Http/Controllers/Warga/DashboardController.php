<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Support\Statistik;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $query = Laporan::where('pelapor_id', $user->id);

        $hari = (int) $request->integer('hari', 7);
        $hari = in_array($hari, Statistik::RENTANG, true) ? $hari : 7;

        $status = Statistik::status($query);
        $matriks = Statistik::jumlahHarian($query, 30);

        return view('warga.dashboard', [
            'user' => $user,
            'hari' => $hari,
            'status' => $status,
            'selesai' => Statistik::persentase($status),
            'delta' => Statistik::delta($query),
            'seri' => Statistik::seri($query, $hari),
            'matriks' => $matriks,
            'matriksTotal' => array_sum(array_column($matriks, 'jumlah')),
            'sparkline' => Statistik::selesaiHarian($query, 30),
            'terbaru' => (clone $query)->latest('created_at')->latest('id')->limit(5)->get(),
            'kabar' => (clone $query)->whereNotNull('tanggapan')->latest('updated_at')->first(),
        ]);
    }
}
