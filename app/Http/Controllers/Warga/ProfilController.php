<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfilRequest;
use App\Models\User;
use App\Support\Foto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class ProfilController extends Controller
{
    public function edit(Request $request): View
    {
        return view('warga.profil', [
            'user' => $request->user(),
            'jumlahLaporan' => $request->user()->laporans()->count(),
        ]);
    }

    public function update(ProfilRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->safe()->except('foto', 'hapus_foto');
        $fotoLama = $user->foto;
        $fotoBaru = $request->file('foto') ? Foto::simpan($request->file('foto'), 'avatar') : null;
        $hapusFoto = $request->boolean('hapus_foto') && ! $fotoBaru;

        try {
            if ($fotoBaru) {
                $user->foto = $fotoBaru;
            } elseif ($hapusFoto) {
                $user->foto = null;
            }

            $user->fill($data);
            $user->save();
        } catch (Throwable $exception) {
            Foto::hapus($fotoBaru);

            throw $exception;
        }

        if (($fotoBaru || $hapusFoto) && $fotoLama) {
            Foto::hapus($fotoLama);
        }

        return redirect()
            ->route('profil.edit')
            ->with('sukses', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'password_saat_ini' => ['required', 'string', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'password_saat_ini.required' => 'Isi password yang sekarang dipakai.',
            'password_saat_ini.current_password' => 'Password saat ini tidak sesuai.',
            'password.required' => 'Isi password baru.',
            'password.confirmed' => 'Ulangi password baru belum sama.',
        ], [
            'password_saat_ini' => 'password saat ini',
            'password' => 'password baru',
        ]);

        $request->user()->forceFill([
            'password' => Hash::make($data['password']),
        ])->save();

        return redirect()
            ->route('profil.edit')
            ->with('sukses', 'Password berhasil diperbarui. Gunakan password baru saat masuk berikutnya.');
    }

    public function avatar(Request $request): StreamedResponse
    {
        $user = $request->user();

        abort_unless(Foto::ada($user->foto), 404);

        return Storage::disk('public')->response($user->foto, null, [
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
