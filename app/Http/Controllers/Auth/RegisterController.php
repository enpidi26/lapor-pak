<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'no_whatsapp' => preg_replace('/[\s\-\.]/', '', (string) $request->input('no_whatsapp')),
            'email' => mb_strtolower(trim((string) $request->input('email'))),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'string', 'email', 'max:120', 'unique:users,email'],
            'no_whatsapp' => ['required', 'string', 'max:20', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,11}$/'],
            'alamat' => ['required', 'string', 'max:255'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->numbers(),
            ],
        ], [
            'name.required' => 'Isi nama lengkapmu.',
            'email.required' => 'Isi email aktif yang bisa kamu akses.',
            'email.email' => 'Format email belum benar.',
            'email.unique' => 'Email ini sudah terdaftar. Coba masuk saja.',
            'no_whatsapp.required' => 'Nomor WhatsApp dibutuhkan agar pengurus RT bisa menghubungimu.',
            'no_whatsapp.regex' => 'Nomor WhatsApp belum benar. Contoh: 0812 3456 7890.',
            'alamat.required' => 'Isi alamat rumah supaya laporan mudah dicek lokasinya.',
            'password.required' => 'Buat password untuk akunmu.',
            'password.confirmed' => 'Ulangi password belum sama.',
        ], [
            'name' => 'nama lengkap',
            'email' => 'email',
            'no_whatsapp' => 'nomor WhatsApp',
            'alamat' => 'alamat',
            'password' => 'password',
        ]);

        $user = User::create($data);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('beranda')
            ->with('sukses', 'Akun dibuat. Kamu bisa langsung mengirim laporan.');
    }
}
