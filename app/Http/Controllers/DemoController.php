<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Masuk cepat untuk pratinjau lokal.
 *
 * Hanya aktif saat APP_ENV=local dan LAPOR_DEMO_AKTIF=true, sehingga tidak
 * pernah tersedia di server produksi.
 */
class DemoController extends Controller
{
    public function masuk(Request $request, string $peran): RedirectResponse
    {
        abort_unless(app()->environment('local') && config('lapor.demo_aktif'), 404);
        abort_unless(in_array($peran, ['warga', 'admin'], true), 404);

        $email = $peran === 'admin'
            ? config('lapor.admin_email')
            : config('lapor.demo_warga_email');

        $user = User::where('email', $email)->firstOrFail();

        Auth::login($user);
        $request->session()->regenerate();

        $tujuan = (string) $request->query('ke', '');
        $tujuanAman = $tujuan !== ''
            && str_starts_with($tujuan, '/')
            && ! str_starts_with($tujuan, '//');

        $redirect = $tujuanAman
            ? redirect()->away($tujuan)
            : redirect()->route($user->dashboardRoute());

        return $redirect->with(
            'sukses',
            'Masuk sebagai akun demo '.($user->isAdmin() ? 'admin' : 'warga').'.'
        );
    }
}
