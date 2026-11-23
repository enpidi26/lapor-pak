@extends('layouts.app')

@section('crumb', $laporan->kode())
@section('heading', 'Tinjau laporan')
@section('actions')
    <a class="btn btn--ghost" href="{{ route('admin.laporan.index') }}">Kembali</a>
    @if ($berikutnya)
        <form method="POST" action="{{ route('admin.laporan.status', $laporan) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="{{ $berikutnya }}">
            <button type="submit" class="btn btn--ink">
                <x-icon name="check" :size="15" />
                @if ($berikutnya === 'proses')
                    Tandai diproses
                @else
                    Tandai selesai
                @endif
            </button>
        </form>
    @endif
@endsection

@section('content')
    @php
        $langkah = [
            ['kunci' => 'pending', 'judul' => 'Laporan dikirim', 'desc' => 'Warga mengirim laporan.', 'waktu' => $laporan->created_at],
            ['kunci' => 'proses', 'judul' => 'Sedang ditinjau', 'desc' => 'Laporan ditindaklanjuti pengurus.', 'waktu' => null],
            ['kunci' => 'selesai', 'judul' => 'Selesai', 'desc' => 'Penanganan selesai dan dicatat.', 'waktu' => null],
        ];
        $posisi = array_search($laporan->status, array_column($langkah, 'kunci'), true);
        $wa = preg_replace('/\D+/', '', (string) $laporan->pelapor->no_whatsapp);
        $wa = str_starts_with($wa, '0') ? '62'.substr($wa, 1) : $wa;
    @endphp

    <header class="page-head">
        <div class="page-head__main">
            <div class="row" style="gap: 8px">
                <x-status-badge :status="$laporan->status" />
                <span class="mono faint" style="font-size: 11.5px">{{ $laporan->kode() }}</span>
            </div>
            <h1 class="h1 mt-12">{{ $laporan->judul }}</h1>
            <p class="page-head__meta">
                {{ $laporan->pelapor->name }}
                <span class="list__sep"></span>
                {{ \App\Support\Waktu::tanggalJam($laporan->created_at) }}
                <span class="list__sep"></span>
                {{ $laporan->labelKategori() }}
            </p>
        </div>
    </header>

    <div class="split">
        <div class="stack">
            <section class="sheet">
                <div class="sheet__head" style="margin-bottom: 12px">
                    <div>
                        <h2 class="sheet__title">Cerita laporan</h2>
                        <p class="sheet__sub">Sampaikan penanganan dengan bahasa yang mudah dipahami warga</p>
                    </div>
                </div>

                <p class="detail-text">{{ $laporan->detail }}</p>
            </section>

            @if ($laporan->foto)
                <section class="sheet">
                    <div class="sheet__head" style="margin-bottom: 12px">
                        <div>
                            <h2 class="sheet__title">Foto</h2>
                            <p class="sheet__sub">Diunggah oleh pelapor</p>
                        </div>
                        <a class="sheet__link" href="{{ route('laporan.foto', $laporan) }}" target="_blank" rel="noopener">
                            Ukuran penuh
                            <x-icon name="arrow-up-right" :size="14" />
                        </a>
                    </div>

                    <a class="photo" href="{{ route('laporan.foto', $laporan) }}" target="_blank" rel="noopener">
                        <img src="{{ route('laporan.foto', $laporan) }}" alt="Foto bukti: {{ $laporan->judul }}" loading="lazy">
                    </a>
                </section>
            @endif

            <section class="sheet">
                <div class="sheet__head">
                    <div>
                        <h2 class="sheet__title">Perjalanan status</h2>
                        <p class="sheet__sub">Tidak bisa dilompati atau dikembalikan</p>
                    </div>
                    <x-status-badge :status="$laporan->status" />
                </div>

                <div class="timeline">
                    @foreach ($langkah as $i => $item)
                        @php $kelas = $i < $posisi ? 'is-done' : ($i === $posisi ? 'is-now' : ''); @endphp

                        <div class="timeline__item {{ $kelas }}">
                            <span class="timeline__dot" aria-hidden="true">
                                @if ($i < $posisi)
                                    <x-icon name="check" :size="11" />
                                @endif
                            </span>
                            <div class="timeline__body">
                                <p class="timeline__title">{{ $item['judul'] }}</p>
                                <p class="timeline__desc">{{ $item['desc'] }}</p>
                                @if ($item['waktu'])
                                    <time class="timeline__time" datetime="{{ $item['waktu']->toIso8601String() }}">
                                        {{ \App\Support\Waktu::tanggalJam($item['waktu']) }}
                                    </time>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($laporan->tanggapan)
                    <div class="note mt-20">
                        <p class="note__head">
                            <x-icon name="message" :size="14" />
                            Catatan terakhir untuk warga
                        </p>
                        <p class="note__text">{{ $laporan->tanggapan }}</p>
                        <p class="note__time">Diperbarui {{ \App\Support\Waktu::relatif($laporan->updated_at) }}</p>
                    </div>
                @endif
            </section>
        </div>

        <aside class="stack">
            <section class="sheet sticky">
                <div class="sheet__head" style="margin-bottom: 12px">
                    <div>
                        <h2 class="sheet__title">Tindak lanjut</h2>
                        <p class="sheet__sub">
                            @if ($laporan->status === 'selesai')
                                Status akhir
                            @else
                                Berikutnya: {{ \App\Models\Laporan::STATUSES[$berikutnya] ?? '-' }}
                            @endif
                        </p>
                    </div>
                    <x-status-badge :status="$laporan->status" />
                </div>

                <form method="POST" action="{{ route('admin.laporan.status', $laporan) }}" class="form" data-lock>
                    @csrf
                    @method('PATCH')

                    <x-form.textarea
                        name="tanggapan"
                        label="Catatan untuk warga"
                        :value="$laporan->tanggapan"
                        :rows="4"
                        :maxlength="2000"
                        hint="Tulis hasil pengecekan atau langkah berikutnya. Pelapor membaca catatan ini."
                    />

                    <div class="row row--wrap" style="gap: 6px">
                        @if ($berikutnya)
                            <button type="submit" name="status" value="{{ $berikutnya }}" class="btn btn--ink">
                                @if ($berikutnya === 'proses')
                                    Tandai diproses
                                @else
                                    Tandai selesai
                                @endif
                            </button>
                        @endif

                        <button type="submit" name="status" value="{{ $laporan->status }}" class="btn btn--ghost">
                            Simpan catatan
                        </button>
                    </div>
                </form>

                <p class="sheet__foot">
                    @if ($laporan->status === 'selesai')
                        Status selesai bersifat akhir. Catatan masih bisa ditambahkan.
                    @else
                        Warga melihat perubahan status dan catatan ini segera setelah disimpan.
                    @endif
                </p>
            </section>

            <section class="sheet">
                <h2 class="sheet__title">Pelapor</h2>

                <div class="person" style="margin-top: 12px; padding-bottom: 12px">
                    <x-avatar :user="$laporan->pelapor" size="lg" />
                    <div class="mw-0">
                        <p class="person__name">{{ $laporan->pelapor->name }}</p>
                        <p class="person__mail">{{ $laporan->pelapor->email }}</p>
                    </div>
                </div>

                <dl class="dl mt-12">
                    <div class="dl__row">
                        <dt>WhatsApp</dt>
                        <dd>{{ $laporan->pelapor->no_whatsapp ? $laporan->pelapor->nomorWhatsappTampil() : 'Belum diisi' }}</dd>
                    </div>
                    <div class="dl__row">
                        <dt>Alamat</dt>
                        <dd>{{ $laporan->pelapor->alamat ?: 'Belum diisi' }}</dd>
                    </div>
                    <div class="dl__row">
                        <dt>Tanggal kejadian</dt>
                        <dd>{{ \App\Support\Waktu::tanggalPanjang($laporan->tanggal) }}</dd>
                    </div>
                </dl>

                <div class="row row--wrap mt-16" style="gap: 6px">
                    @if ($wa !== '')
                        <a class="btn btn--ghost btn--sm" href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener">
                            <x-icon name="whatsapp" :size="14" />
                            WhatsApp
                        </a>
                    @endif
                    <a class="btn btn--quiet btn--sm" href="{{ route('admin.laporan.index', ['q' => $laporan->pelapor->email]) }}">
                        Laporan pelapor ini
                    </a>
                </div>
            </section>
        </aside>
    </div>
@endsection
