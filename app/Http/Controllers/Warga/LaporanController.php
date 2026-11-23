<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Http\Requests\LaporanRequest;
use App\Models\Laporan;
use App\Support\FilterLaporan;
use App\Support\Foto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $filters = FilterLaporan::dariRequest($request);
        $query = $request->user()->laporans();

        $laporans = FilterLaporan::terapkan($query, $filters)
            ->paginate(8)
            ->withQueryString();

        $jumlahStatus = FilterLaporan::jumlahStatus($query);

        return view('warga.laporan.index', compact('laporans', 'filters', 'jumlahStatus'));
    }

    public function create(): View
    {
        return view('warga.laporan.create', [
            'kategori' => Laporan::KATEGORI,
        ]);
    }

    public function store(LaporanRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('foto', 'hapus_foto');
        $path = $request->file('foto') ? Foto::simpan($request->file('foto'), 'laporan') : null;

        try {
            $laporan = new Laporan($data);
            $laporan->pelapor()->associate($request->user());
            $laporan->status = 'pending';
            $laporan->foto = $path;
            $laporan->save();
        } catch (Throwable $exception) {
            Foto::hapus($path);

            throw $exception;
        }

        return redirect()
            ->route('laporan.show', $laporan)
            ->with('sukses', 'Laporan berhasil dikirim. Pengurus RT akan meninjau dalam '
                .config('lapor.jam_tinjau').' jam.');
    }

    public function show(Laporan $laporan): View
    {
        $this->authorize('view', $laporan);

        $laporan->load('pelapor');

        return view('warga.laporan.show', compact('laporan'));
    }

    public function edit(Laporan $laporan): View|RedirectResponse
    {
        $this->authorize('view', $laporan);

        if (! $laporan->isEditable()) {
            return redirect()
                ->route('laporan.show', $laporan)
                ->with('galat', 'Laporan yang sudah ditinjau tidak bisa diubah lagi. Hubungi pengurus RT jika ada yang perlu dikoreksi.');
        }

        $this->authorize('update', $laporan);

        return view('warga.laporan.edit', [
            'laporan' => $laporan,
            'kategori' => Laporan::KATEGORI,
        ]);
    }

    public function update(LaporanRequest $request, Laporan $laporan): RedirectResponse
    {
        $this->authorize('update', $laporan);

        $data = $request->safe()->except('foto', 'hapus_foto');
        $fotoLama = $laporan->foto;
        $fotoBaru = $request->file('foto') ? Foto::simpan($request->file('foto'), 'laporan') : null;
        $hapusFoto = $request->boolean('hapus_foto') && ! $fotoBaru;

        try {
            if ($fotoBaru) {
                $laporan->foto = $fotoBaru;
            } elseif ($hapusFoto) {
                $laporan->foto = null;
            }

            $laporan->fill($data);
            $laporan->save();
        } catch (Throwable $exception) {
            Foto::hapus($fotoBaru);

            throw $exception;
        }

        if (($fotoBaru || $hapusFoto) && $fotoLama) {
            Foto::hapus($fotoLama);
        }

        return redirect()
            ->route('laporan.show', $laporan)
            ->with('sukses', 'Perubahan laporan sudah disimpan.');
    }

    public function destroy(Laporan $laporan): RedirectResponse
    {
        $this->authorize('delete', $laporan);

        Foto::hapus($laporan->foto);
        $laporan->delete();

        return redirect()
            ->route('laporan.index')
            ->with('sukses', 'Laporan sudah dihapus.');
    }

    public function foto(Laporan $laporan): StreamedResponse
    {
        $this->authorize('view', $laporan);

        abort_unless(Foto::ada($laporan->foto), 404);

        return Storage::disk('public')->response($laporan->foto, null, [
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
