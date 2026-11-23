@extends('layouts.app')

@section('crumb', 'Ruang warga')
@section('heading', 'Buat laporan')
@section('actions')
    <a class="btn btn--ghost" href="{{ route('laporan.index') }}">Kembali</a>
@endsection

@section('content')
    <div class="split">
        <form method="POST" action="{{ route('laporan.store') }}" enctype="multipart/form-data" class="sheet sheet--xl" data-lock>
            @csrf

            <div class="form__group">
                <p class="form__group-title">
                    Jenis masalah
                    <span>wajib</span>
                </p>

                <div class="pick-grid" role="radiogroup" aria-label="Jenis masalah">
                    @foreach ($kategori as $k => $m)
                        <label class="pick">
                            <input type="radio" name="kategori" value="{{ $k }}" @checked(old('kategori') === $k) required>
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
                <p class="form__group-title">
                    Cerita
                    <span>judul dan kronologi</span>
                </p>

                <div class="form">
                    <x-form.input
                        name="judul"
                        label="Judul laporan"
                        required
                        maxlength="120"
                        placeholder="Lampu jalan di Gang Melati mati sejak Sabtu"
                        :value="old('judul')"
                        hint="Satu kalimat yang langsung menjelaskan masalahnya."
                    />

                    <x-form.textarea
                        name="detail"
                        label="Ceritakan masalahnya"
                        required
                        :maxlength="2000"
                        :rows="6"
                        placeholder="Lampu penerangan di depan rumah Bu Sri (Gang Melati) mati sejak Sabtu malam. Gang jadi gelap dan warga yang pulang malam kesulitan melihat jalan."
                        :value="old('detail')"
                        hint="Sebutkan lokasi atau patokan, sejak kapan terjadi, dan siapa yang terdampak."
                    />
                </div>
            </div>

            <div class="form__group">
                <p class="form__group-title">
                    Waktu dan bukti
                    <span>foto opsional</span>
                </p>

                <div class="form">
                    <x-form.input
                        name="tanggal"
                        label="Tanggal kejadian"
                        type="date"
                        required
                        max="{{ today()->format('Y-m-d') }}"
                        :value="old('tanggal', today()->format('Y-m-d'))"
                        hint="Tidak boleh melewati hari ini."
                    />

                    <x-form.upload
                        name="foto"
                        label="Foto"
                        hint="JPG, PNG, atau WebP · maksimal {{ round(config('lapor.foto_maks_kb') / 1024) }} MB. Foto mempercepat verifikasi."
                    />
                </div>
            </div>

            <div class="form__actions">
                <span class="form__note">Data hanya dipakai pengurus {{ config('lapor.rt') }}.</span>
                <a class="btn btn--quiet" href="{{ route('laporan.index') }}">Batal</a>
                <button type="submit" class="btn btn--ink btn--lg">Kirim laporan</button>
            </div>
        </form>

        <aside class="stack">
            <section class="sheet tile--mint">
                <h2 class="sheet__title">Supaya cepat ditangani</h2>
                <div class="bullets">
                    <div class="bullet">
                        <span class="bullet__icon" style="background: var(--paper)"><x-icon name="map-pin" :size="13" /></span>
                        <div>
                            <h4>Sebutkan patokan</h4>
                            <p>Nama gang, nomor rumah tetangga, atau bangunan terdekat.</p>
                        </div>
                    </div>
                    <div class="bullet">
                        <span class="bullet__icon" style="background: var(--paper)"><x-icon name="clock" :size="13" /></span>
                        <div>
                            <h4>Jelaskan dampaknya</h4>
                            <p>Siapa yang terganggu dan sejak kapan.</p>
                        </div>
                    </div>
                    <div class="bullet">
                        <span class="bullet__icon" style="background: var(--paper)"><x-icon name="image" :size="13" /></span>
                        <div>
                            <h4>Foto situasi keseluruhan</h4>
                            <p>Bukan hanya bagian yang rusak.</p>
                        </div>
                    </div>
                    <div class="bullet">
                        <span class="bullet__icon" style="background: var(--paper)"><x-icon name="list" :size="13" /></span>
                        <div>
                            <h4>Satu laporan, satu masalah</h4>
                            <p>Supaya mudah dipantau dan ditindaklanjuti.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="sheet">
                <div class="row">
                    <span class="list__tile"><x-icon name="lock" :size="16" /></span>
                    <h2 class="sheet__title">Siapa yang melihat</h2>
                </div>
                <p class="body mt-8">
                    Hanya kamu dan pengurus {{ config('lapor.rt') }}. Laporan tidak ditampilkan
                    ke warga lain dan tidak muncul di mesin pencari.
                </p>
            </section>
        </aside>
    </div>
@endsection
