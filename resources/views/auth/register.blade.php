@extends('layouts.auth')

@section('title', 'Daftar Akun')
@section('description', 'Buat akun Lapor Pak untuk melaporkan masalah lingkungan.')

@section('content')
    <span class="eyebrow">Buat akun warga</span>
    <h1 style="margin-top:12px;font-size:30px;font-weight:800;letter-spacing:-0.03em">Daftar ke Lapor Pak</h1>
    <p class="lead" style="margin-top:12px;font-size:14.5px">
        Sekali daftar, Anda bisa menyampaikan pengaduan dan memantau penanganannya.
    </p>

    <form method="POST" action="{{ route('register.store') }}" class="form" style="margin-top:32px" data-lock>
        @csrf

        <x-form.input
            name="name"
            label="Nama lengkap"
            required
            autocomplete="name"
            placeholder="Budi Santoso"
            :value="old('name')"
        />

        <x-form.input
            name="email"
            label="Email"
            type="email"
            required
            autocomplete="email"
            placeholder="nama@email.com"
            :value="old('email')"
            hint="Dipakai untuk masuk."
        />

        <div class="form__row">
            <x-form.input
                name="no_whatsapp"
                label="Nomor WhatsApp"
                type="tel"
                inputmode="tel"
                required
                autocomplete="tel"
                placeholder="0812 3456 7890"
                :value="old('no_whatsapp')"
                hint="Untuk verifikasi lokasi."
            />

            <x-form.input
                name="alamat"
                label="Alamat rumah"
                required
                autocomplete="street-address"
                placeholder="Jl. Melati No. 12, RT 04"
                :value="old('alamat')"
                hint="Termasuk nomor rumah dan RT."
            />
        </div>

        <x-form.password
            name="password"
            label="Buat password"
            required
            autocomplete="new-password"
            checklist
        />

        <x-form.password
            name="password_confirmation"
            label="Ulangi password"
            required
            autocomplete="new-password"
        />

        <button type="submit" class="btn btn--primary btn--lg btn--block">
            Buat Akun
            <x-icon name="arrow-right" :size="17" class="icon--arrow" />
        </button>

        <p style="font-size:12.5px;line-height:1.6;color:var(--faint);text-align:center">
            Data pribadi hanya dipakai pengelola {{ config('lapor.rt') }} untuk keperluan penanganan pengaduan.
        </p>
    </form>

    <p style="margin-top:28px;text-align:center;font-size:14px;color:var(--muted)">
        Sudah punya akun?
        <a class="link" href="{{ route('login') }}">Masuk</a>
    </p>
@endsection
