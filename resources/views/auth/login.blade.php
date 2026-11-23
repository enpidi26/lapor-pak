@extends('layouts.auth')

@section('title', 'Masuk')
@section('description', 'Masuk ke akun Lapor Pak.')

@section('content')
    <span class="eyebrow">Selamat datang kembali</span>
    <h1 style="margin-top:12px;font-size:30px;font-weight:800;letter-spacing:-0.03em">Masuk ke Lapor Pak</h1>
    <p class="lead" style="margin-top:12px;font-size:14.5px">Lanjutkan langkah kecil untuk perubahan besar.</p>

    <form method="POST" action="{{ route('login.store') }}" class="form" style="margin-top:32px" data-lock>
        @csrf

        <x-form.input
            name="email"
            label="Email"
            type="email"
            required
            autocomplete="email"
            placeholder="nama@email.com"
            :value="old('email')"
        />

        <x-form.password
            name="password"
            label="Password"
            required
            autocomplete="current-password"
        />

        <div class="row between row--wrap">
            <label class="check" for="ingat">
                <input type="checkbox" name="ingat" id="ingat" value="1" @checked(old('ingat'))>
                Ingat saya
            </label>
            <a class="link" style="font-size:14px" href="{{ route('bantuan') }}#akun">Lupa password?</a>
        </div>

        <button type="submit" class="btn btn--primary btn--lg btn--block">
            Masuk ke Akun
            <x-icon name="arrow-right" :size="17" class="icon--arrow" />
        </button>
    </form>

    @if (config('lapor.demo_aktif') && app()->environment('local'))
        <div class="card" style="margin-top:24px;padding:18px;background:var(--slate-50);border-style:dashed">
            <p class="eyebrow" style="font-size:11px">Pratinjau lokal</p>
            <p style="margin-top:8px;font-size:13.5px;color:var(--muted)">Masuk memakai akun contoh yang sudah berisi data.</p>
            <div class="row row--wrap" style="margin-top:14px">
                <a class="btn btn--primary btn--sm" href="{{ route('demo.masuk', 'warga') }}">Sebagai warga</a>
                <a class="btn btn--ghost btn--sm" href="{{ route('demo.masuk', 'admin') }}">Sebagai pengurus</a>
            </div>
        </div>
    @endif

    <p style="margin-top:28px;text-align:center;font-size:14px;color:var(--muted)">
        Belum punya akun?
        <a class="link" href="{{ route('register') }}">Daftar sekarang</a>
    </p>
@endsection
