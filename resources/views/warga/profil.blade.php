@extends('layouts.app')

@section('crumb', 'Akun')
@section('heading', 'Profil saya')
@section('actions')
    <a class="btn btn--ghost" href="{{ route('beranda') }}">Kembali</a>
@endsection

@section('content')
    @php
        $admin = $user->isAdmin();
    @endphp

    <div class="split">
        <div class="stack">
            <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data" class="sheet sheet--xl" data-lock>
                @csrf
                @method('PUT')

                <div class="person">
                    <x-avatar :user="$user" size="xl" />

                    <div class="mw-0">
                        <p class="person__name" style="font-size: 16px">{{ $user->name }}</p>
                        <p class="person__mail">{{ $user->email }}</p>
                        <div class="row row--wrap mt-8" style="gap: 5px">
                            <span class="chip {{ $admin ? 'chip--ink' : 'chip--mint' }}">
                                {{ $admin ? 'Pengurus RT' : 'Warga' }}
                            </span>
                            <span class="chip">Bergabung {{ \App\Support\Waktu::tanggal($user->created_at) }}</span>
                        </div>
                    </div>
                </div>

                <div class="form__group">
                    <p class="form__group-title">Identitas</p>

                    <div class="form">
                        <div class="form__row">
                            <x-form.input name="name" label="Nama lengkap" required autocomplete="name" :value="old('name', $user->name)" />

                            <x-form.input
                                name="email"
                                label="Email"
                                type="email"
                                required
                                autocomplete="email"
                                :value="old('email', $user->email)"
                                hint="Dipakai untuk masuk."
                            />
                        </div>

                        <div class="form__row">
                            <x-form.input
                                name="no_whatsapp"
                                label="Nomor WhatsApp"
                                type="tel"
                                inputmode="tel"
                                required
                                autocomplete="tel"
                                :value="old('no_whatsapp', $user->nomorWhatsappTampil())"
                                hint="Contoh: 0812 3456 7890"
                            />

                            <x-form.input
                                name="alamat"
                                label="Alamat rumah"
                                required
                                autocomplete="street-address"
                                :value="old('alamat', $user->alamat)"
                                hint="Termasuk nomor rumah dan RT."
                            />
                        </div>

                        <x-form.upload
                            name="foto"
                            label="Foto profil"
                            hint="JPG, PNG, atau WebP · maksimal 1 MB."
                            :max-kb="1024"
                            :current-url="$user->foto ? route('profil.avatar') : null"
                        />
                    </div>
                </div>

                <div class="form__actions">
                    <span class="form__note">Data dipakai hanya untuk penanganan laporan.</span>
                    <button type="submit" class="btn btn--ink">Simpan perubahan</button>
                </div>
            </form>

            <form method="POST" action="{{ route('profil.password') }}" class="sheet" data-lock>
                @csrf
                @method('PUT')

                <p class="form__group-title" style="margin-bottom: 16px">Ganti password</p>

                <div class="form">
                    <x-form.password
                        name="password_saat_ini"
                        label="Password saat ini"
                        required
                        autocomplete="current-password"
                    />

                    <div class="form__row">
                        <x-form.password
                            name="password"
                            label="Password baru"
                            required
                            autocomplete="new-password"
                            checklist
                        />

                        <x-form.password
                            name="password_confirmation"
                            label="Ulangi password baru"
                            required
                            autocomplete="new-password"
                        />
                    </div>
                </div>

                <div class="form__actions">
                    <span class="form__note">Gunakan password baru saat masuk berikutnya.</span>
                    <button type="submit" class="btn btn--ghost">Perbarui password</button>
                </div>
            </form>
        </div>

        <aside class="stack">
            <section class="sheet">
                <h2 class="sheet__title">Ringkasan akun</h2>
                <dl class="dl mt-12">
                    <div class="dl__row">
                        <dt>Peran</dt>
                        <dd>{{ $admin ? 'Pengurus RT' : 'Warga' }}</dd>
                    </div>
                    <div class="dl__row">
                        <dt>Status akun</dt>
                        <dd>Aktif</dd>
                    </div>
                    <div class="dl__row">
                        <dt>{{ $admin ? 'Wilayah' : 'Laporan terkirim' }}</dt>
                        <dd>{{ $admin ? config('lapor.rt').' · '.config('lapor.kelurahan') : $jumlahLaporan.' laporan' }}</dd>
                    </div>
                    <div class="dl__row">
                        <dt>Bergabung</dt>
                        <dd>{{ \App\Support\Waktu::tanggalPanjang($user->created_at) }}</dd>
                    </div>
                </dl>
            </section>

            <section class="sheet tile--mint">
                <h2 class="sheet__title">Privasi data</h2>
                <p class="body mt-8">
                    Nama, email, nomor WhatsApp, dan alamat hanya bisa dilihat pengurus
                    {{ config('lapor.rt') }}. Laporan tidak ditampilkan ke warga lain.
                </p>
                <a class="sheet__link mt-12" href="{{ route('bantuan') }}#privasi">
                    Pelajari privasi
                    <x-icon name="arrow-right" :size="14" />
                </a>
            </section>

            <section class="sheet tile--ink">
                <h2 class="sheet__title">Keluar dari akun</h2>
                <p class="small mt-8">
                    Lakukan terutama jika memakai ponsel atau komputer bersama.
                </p>
                <form method="POST" action="{{ route('logout') }}" class="mt-12">
                    @csrf
                    <button type="submit" class="btn btn--block">Keluar</button>
                </form>
            </section>
        </aside>
    </div>
@endsection
