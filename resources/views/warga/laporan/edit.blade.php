@extends('layouts.app')

@section('crumb', 'Ruang warga')
@section('heading', 'Ubah laporan')
@section('actions')
    <a class="btn btn--ghost" href="{{ route('laporan.show', $laporan) }}">Kembali</a>
@endsection

@section('content')
    <div class="split">
        <form method="POST" action="{{ route('laporan.update', $laporan) }}" enctype="multipart/form-data" class="sheet sheet--xl" data-lock>
            @csrf
            @method('PUT')

            <div class="form__group">
                <p class="form__group-title">Jenis masalah</p>

                <div class="pick-grid" role="radiogroup" aria-label="Jenis masalah">
                    @foreach ($kategori as $k => $m)
                        <label class="pick">
                            <input type="radio" name="kategori" value="{{ $k }}" @checked(old('kategori', $laporan->kategori) === $k) required>
                            <span class="pick__body">
                                <x-icon :name="$m['icon']" :size="16" />
                                {{ $m['label'] }}
                            </span>
                        </label>
                    @endforeach
                </div>

                @error('kategori')
                    <p class="field__error mt-8" role="alert">
                        <x-icon name="alert-circle" :size="13" />
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="form__group">
                <p class="form__group-title">Cerita</p>

                <div class="form">
                    <x-form.input
                        name="judul"
                        label="Judul laporan"
                        required
                        maxlength="120"
                        :value="old('judul', $laporan->judul)"
                        hint="Satu kalimat yang langsung menjelaskan masalahnya."
                    />

                    <x-form.textarea
                        name="detail"
                        label="Ceritakan masalahnya"
                        required
                        :maxlength="2000"
                        :rows="6"
                        :value="old('detail', $laporan->detail)"
                        hint="Sebutkan lokasi atau patokan, sejak kapan terjadi, dan siapa yang terdampak."
                    />
                </div>
            </div>

            <div class="form__group">
                <p class="form__group-title">Waktu dan bukti</p>

                <div class="form">
                    <x-form.input
                        name="tanggal"
                        label="Tanggal kejadian"
                        type="date"
                        required
                        max="{{ today()->format('Y-m-d') }}"
                        :value="old('tanggal', $laporan->tanggal->format('Y-m-d'))"
                        hint="Tidak boleh melewati hari ini."
                    />

                    <x-form.upload
                        name="foto"
                        label="Foto"
                        :current-url="$laporan->foto ? route('laporan.foto', $laporan) : null"
                    />
                </div>
            </div>

            <div class="form__actions">
                <span class="form__note">Status tetap Menunggu setelah disimpan.</span>
                <a class="btn btn--quiet" href="{{ route('laporan.show', $laporan) }}">Batal</a>
                <button type="submit" class="btn btn--ink btn--lg">Simpan perubahan</button>
            </div>
        </form>

        <aside class="stack">
            <section class="sheet tile--mint">
                <h2 class="sheet__title">Setelah ditinjau, laporan terkunci</h2>
                <p class="body mt-8">
                    Selama status masih Menunggu, isi laporan bebas diperbaiki. Begitu pengurus
                    mulai menindaklanjuti, isinya dikunci agar riwayat penanganan tetap jujur.
                </p>
                <a class="sheet__link mt-12" href="{{ route('bantuan') }}#ubah">
                    Baca aturannya
                    <x-icon name="arrow-right" :size="14" />
                </a>
            </section>

            <section class="sheet">
                <h2 class="sheet__title">Perlu mengoreksi laporan yang terkunci?</h2>
                <p class="body mt-8">
                    Hubungi pengurus RT. Mereka bisa mencatat koreksi pada laporan yang sedang
                    diproses.
                </p>
                <a class="btn btn--ghost btn--sm mt-12" href="https://wa.me/{{ preg_replace('/\D+/', '', config('lapor.whatsapp')) }}" target="_blank" rel="noopener">
                    <x-icon name="whatsapp" :size="14" />
                    WhatsApp pengurus
                </a>
            </section>
        </aside>
    </div>
@endsection
