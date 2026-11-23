@extends('layouts.app')

@section('crumb', $laporan->kode())
@section('heading', 'Detail laporan')
@section('actions')
    <a class="btn btn--ghost" href="{{ route('laporan.index') }}">Kembali</a>
    @if ($laporan->isEditable())
        <a class="btn btn--ink" href="{{ route('laporan.edit', $laporan) }}">
            <x-icon name="edit" :size="15" />
            Ubah
        </a>
    @endif
@endsection

@section('content')
    @php
        $langkah = [
            ['kunci' => 'pending', 'judul' => 'Laporan dikirim', 'desc' => 'Masuk antrean pengurus RT.', 'waktu' => $laporan->created_at],
            ['kunci' => 'proses', 'judul' => 'Sedang ditinjau', 'desc' => 'Pengurus mulai menindaklanjuti.', 'waktu' => null],
            ['kunci' => 'selesai', 'judul' => 'Selesai', 'desc' => 'Masalah ditangani dan hasilnya dicatat.', 'waktu' => null],
        ];
        $posisi = array_search($laporan->status, array_column($langkah, 'kunci'), true);
    @endphp

    <header class="page-head">
        <div class="page-head__main">
            <div class="row" style="gap: 8px">
                <x-status-badge :status="$laporan->status" />
                <span class="mono faint" style="font-size: 11.5px">{{ $laporan->kode() }}</span>
            </div>
            <h1 class="h1 mt-12">{{ $laporan->judul }}</h1>
            <p class="page-head__meta">
                Dikirim {{ \App\Support\Waktu::tanggalJam($laporan->created_at) }}
                <span class="list__sep"></span>
                {{ $laporan->labelKategori() }}
            </p>
        </div>

        @if ($laporan->isEditable())
            <div class="page-head__actions">
                <a class="btn btn--ghost" href="{{ route('laporan.edit', $laporan) }}">
                    <x-icon name="edit" :size="15" />
                    Ubah
                </a>
                <button type="button" class="btn btn--danger" data-dialog-open="dialog-hapus">
                    <x-icon name="trash" :size="15" />
                    Hapus
                </button>
            </div>
        @endif
    </header>

    <div class="split">
        <div class="stack">
            <section class="sheet">
                <div class="sheet__head">
                    <div>
                        <h2 class="sheet__title">Status penanganan</h2>
                        <p class="sheet__sub">{{ \App\Models\Laporan::STATUS_DESKRIPSI[$laporan->status] }}</p>
                    </div>
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
                            Catatan pengurus
                        </p>
                        <p class="note__text">{{ $laporan->tanggapan }}</p>
                        <p class="note__time">Diperbarui {{ \App\Support\Waktu::relatif($laporan->updated_at) }}</p>
                    </div>
                @else
                    <p class="sheet__foot">
                        Belum ada catatan. Catatan muncul setelah laporan ditinjau pengurus.
                    </p>
                @endif
            </section>

            <section class="sheet">
                <div class="sheet__head" style="margin-bottom: 12px">
                    <div>
                        <h2 class="sheet__title">Cerita laporan</h2>
                        <p class="sheet__sub">Yang kamu sampaikan ke pengurus</p>
                    </div>
                </div>

                <p class="detail-text">{{ $laporan->detail }}</p>
            </section>

            @if ($laporan->foto)
                <section class="sheet">
                    <div class="sheet__head" style="margin-bottom: 12px">
                        <div>
                            <h2 class="sheet__title">Foto</h2>
                            <p class="sheet__sub">Hanya terlihat oleh kamu dan pengurus</p>
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
        </div>

        <aside class="stack">
            <section class="sheet">
                <h2 class="sheet__title">Ringkasan</h2>
                <dl class="dl mt-12">
                    <div class="dl__row">
                        <dt>Nomor laporan</dt>
                        <dd class="mono">{{ $laporan->kode() }}</dd>
                    </div>
                    <div class="dl__row">
                        <dt>Jenis</dt>
                        <dd>{{ $laporan->labelKategori() }}</dd>
                    </div>
                    <div class="dl__row">
                        <dt>Tanggal kejadian</dt>
                        <dd>{{ \App\Support\Waktu::tanggalPanjang($laporan->tanggal) }}</dd>
                    </div>
                    <div class="dl__row">
                        <dt>Dikirim</dt>
                        <dd>{{ \App\Support\Waktu::tanggalJam($laporan->created_at) }}</dd>
                    </div>
                    <div class="dl__row">
                        <dt>Diperbarui</dt>
                        <dd>{{ \App\Support\Waktu::tanggalJam($laporan->updated_at) }}</dd>
                    </div>
                </dl>
            </section>

            <section class="sheet tile--mint">
                <h2 class="sheet__title">
                    {{ $laporan->isEditable() ? 'Masih bisa diubah' : 'Laporan sudah ditinjau' }}
                </h2>
                <p class="body mt-8">
                    @if ($laporan->isEditable())
                        Ubah judul, cerita, tanggal, atau foto sebelum pengurus meninjau.
                    @else
                        Isi laporan dikunci agar riwayat penanganan tetap jujur.
                    @endif
                </p>

                @if ($laporan->isEditable())
                    <div class="row row--wrap mt-16">
                        <a class="btn btn--ink" href="{{ route('laporan.edit', $laporan) }}">
                            <x-icon name="edit" :size="15" />
                            Ubah laporan
                        </a>
                    </div>
                @else
                    <a class="btn btn--ghost btn--sm mt-16" href="https://wa.me/{{ preg_replace('/\D+/', '', config('lapor.whatsapp')) }}" target="_blank" rel="noopener">
                        <x-icon name="whatsapp" :size="14" />
                        Hubungi pengurus
                    </a>
                @endif
            </section>

            <section class="sheet">
                <h2 class="sheet__title">Belum paham statusnya?</h2>
                <p class="body mt-8">
                    Halaman bantuan menjelaskan arti Menunggu, Diproses, dan Selesai.
                </p>
                <a class="sheet__link mt-12" href="{{ route('bantuan') }}#status">
                    Baca arti status
                    <x-icon name="arrow-right" :size="14" />
                </a>
            </section>
        </aside>
    </div>

    @if ($laporan->isEditable())
        <dialog id="dialog-hapus" aria-labelledby="judul-hapus">
            <div class="dialog__panel">
                <h2 class="dialog__title" id="judul-hapus">Hapus laporan ini?</h2>
                <p class="dialog__text">
                    &ldquo;{{ $laporan->judul }}&rdquo; beserta fotonya akan dihapus permanen.
                    Tindakan ini tidak bisa dibatalkan.
                </p>

                <div class="dialog__actions">
                    <button type="button" class="btn btn--ghost" data-dialog-close>Batal</button>
                    <form method="POST" action="{{ route('laporan.destroy', $laporan) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn--danger-solid">Hapus</button>
                    </form>
                </div>
            </div>
        </dialog>
    @endif
@endsection
