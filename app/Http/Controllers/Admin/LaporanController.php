<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Services\LaporanWorkflow;
use App\Support\FilterLaporan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $filters = FilterLaporan::dariRequest($request);
        $query = Laporan::query()->with('pelapor');

        $laporans = FilterLaporan::terapkan($query, $filters)
            ->paginate(10)
            ->withQueryString();

        $jumlahStatus = FilterLaporan::jumlahStatus($query);

        return view('admin.laporan.index', compact('laporans', 'filters', 'jumlahStatus'));
    }

    public function show(Laporan $laporan, LaporanWorkflow $workflow): View
    {
        $this->authorize('changeStatus', $laporan);

        $laporan->load('pelapor');

        return view('admin.laporan.show', [
            'laporan' => $laporan,
            'berikutnya' => $workflow->berikutnya($laporan->status),
        ]);
    }

    public function updateStatus(Request $request, Laporan $laporan, LaporanWorkflow $workflow): RedirectResponse
    {
        $this->authorize('changeStatus', $laporan);

        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Laporan::STATUSES))],
            'tanggapan' => ['nullable', 'string', 'max:2000'],
        ], [
            'status.required' => 'Pilih status laporan.',
            'status.in' => 'Status laporan tidak dikenali.',
            'tanggapan.max' => 'Catatan terlalu panjang. Maksimal 2.000 karakter.',
        ]);

        $statusBaru = $data['status'];
        $berpindah = $statusBaru !== $laporan->status;

        if ($berpindah) {
            $workflow->pindahkan($laporan, $statusBaru);
        }

        if ($request->has('tanggapan')) {
            $laporan->tanggapan = filled($data['tanggapan'] ?? null)
                ? trim((string) $data['tanggapan'])
                : null;
            $laporan->save();
        }

        $pesan = match (true) {
            $statusBaru === 'proses' && $berpindah => 'Laporan ditandai diproses. Warga melihat perubahan ini.',
            $statusBaru === 'selesai' && $berpindah => 'Laporan ditandai selesai.',
            default => 'Catatan untuk laporan ini sudah disimpan.',
        };

        return redirect()
            ->route('admin.laporan.show', $laporan)
            ->with('sukses', $pesan);
    }
}
