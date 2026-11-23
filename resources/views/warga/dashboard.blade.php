@extends('layouts.app')

@section('crumb', 'Ruang warga')
@section('heading', 'Beranda')
@section('actions')
    <a class="btn btn--ink" href="{{ route('laporan.create') }}">
        <x-icon name="plus" :size="15" />
        Buat laporan
    </a>
@endsection

@section('content')
    @php
        $aktif = $status['pending'] + $status['proses'];
    @endphp

    @if ($status['total'] === 0)
        <div class="split">
            <section class="sheet sheet--xl">
                <span class="label">mulai dari sini</span>
                <h1 class="h1 mt-12">Kirim laporan pertama.</h1>
                <p class="lead mt-8">
                    Lampu depan mati, sampah menumpuk, atau saluran air mampet. Tulis apa adanya:
                    patokan lokasi, sejak kapan masalahnya terjadi, dan siapa yang terdampak.
                    Foto bersifat opsional.
                </p>

                <div class="row row--wrap mt-20">
                    <a class="btn btn--ink btn--lg" href="{{ route('laporan.create') }}">Buat laporan</a>
                    <a class="btn btn--ghost btn--lg" href="{{ route('laporan.index') }}">Lihat laporan saya</a>
                </div>

                <p class="small mt-20">
                    Laporan yang belum ditinjau masih bisa kamu ubah atau hapus sendiri.
                </p>
            </section>

            <aside class="stack">
                <section class="sheet">
                    <h2 class="sheet__title">Sebelum mengirim</h2>
                    <div class="bullets">
                        <div class="bullet">
                            <span class="bullet__icon"><x-icon name="map-pin" :size="13" /></span>
                            <div>
                                <h4>Sebutkan patokan</h4>
                                <p>Nama gang, nomor rumah tetangga, atau bangunan terdekat.</p>
                            </div>
                        </div>
                        <div class="bullet">
                            <span class="bullet__icon"><x-icon name="clock" :size="13" /></span>
                            <div>
                                <h4>Sejak kapan terjadi</h4>
                                <p>Tanggal kejadian membantu pengurus menentukan prioritas.</p>
                            </div>
                        </div>
                        <div class="bullet">
                            <span class="bullet__icon"><x-icon name="image" :size="13" /></span>
                            <div>
                                <h4>Satu foto situasi</h4>
                                <p>Ambil dari jarak yang menunjukkan kondisi keseluruhan.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="sheet tile--cream">
                    <h2 class="sheet__title">Jenis masalah yang bisa dilaporkan</h2>
                    <div class="list mt-12">
                        @foreach (\App\Models\Laporan::KATEGORI as $kunci => $meta)
                            <div class="list__row" style="padding: 7px 0">
                                <span class="list__tile" style="width: 30px; height: 30px">
                                    <x-icon :name="$meta['icon']" :size="14" />
                                </span>
                                <span class="list__main">
                                    <span class="list__title" style="font-size: 12.5px">{{ $meta['label'] }}</span>
                                </span>
                            </div>
                        @endforeach
                    </div>
                </section>
            </aside>
        </div>
    @else
        <div class="cols cols--4">
            <article class="sheet fig">
                <p class="fig__label">laporan masuk</p>
                <p class="fig__value">{{ $status['total'] }}</p>
                <p class="fig__note">Sejak akun dibuat</p>
            </article>

            <article class="sheet fig">
                <p class="fig__label">menunggu</p>
                <p class="fig__value">{{ $status['pending'] }}</p>
                <p class="fig__note">Di antrean pengurus</p>
            </article>

            <article class="sheet tile--mint fig">
                <p class="fig__label">diproses</p>
                <p class="fig__value">{{ $status['proses'] }}</p>
                <p class="fig__note">Sedang ditangani</p>
            </article>

            <article class="sheet tile--ink fig">
                <p class="fig__label">selesai</p>
                <p class="fig__value">{{ $status['selesai'] }}<small>{{ $selesai }}%</small></p>
                <p class="fig__note">
                    @if ($delta === null)
                        Belum ada pembanding bulan lalu
                    @elseif ($delta > 0)
                        <span class="delta delta--up">
                            <x-icon name="trend-up" :size="11" />
                            {{ $delta }}% dari bulan lalu
                        </span>
                    @elseif ($delta < 0)
                        <span class="delta delta--down">
                            <x-icon name="trend-down" :size="11" />
                            {{ abs($delta) }}% dari bulan lalu
                        </span>
                    @else
                        <span class="delta delta--flat">Sama dengan bulan lalu</span>
                    @endif
                </p>
            </article>
        </div>

        <div class="split section-gap">
            <section class="sheet">
                <div class="sheet__head">
                    <div>
                        <h2 class="sheet__title">Laporan masuk</h2>
                        <p class="sheet__sub">{{ $hari }} hari terakhir</p>
                    </div>

                    <div class="tabs" aria-label="Rentang waktu">
                        @foreach ([7, 14, 30] as $p)
                            <a class="{{ $hari === $p ? 'is-active' : '' }}" href="{{ route('beranda', ['hari' => $p]) }}">{{ $p }}h</a>
                        @endforeach
                    </div>
                </div>

                @include('partials.chart', ['seri' => $seri])
            </section>

            <aside class="stack">
                <section class="sheet tile--mint">
                    <div class="sheet__head" style="margin-bottom: 14px">
                        <div>
                            <h2 class="sheet__title">Tingkat penyelesaian</h2>
                            <p class="sheet__sub">Laporanmu yang sudah selesai</p>
                        </div>
                        <span class="fig__value num" style="font-size: 30px">{{ $selesai }}%</span>
                    </div>

                    <div class="bar-h" aria-hidden="true">
                        @if ($status['selesai'] > 0)
                            <span class="bar-h__seg bar-h__seg--selesai" style="flex: {{ $status['selesai'] }} 1 0"></span>
                        @endif
                        @if ($status['proses'] > 0)
                            <span class="bar-h__seg bar-h__seg--proses" style="flex: {{ $status['proses'] }} 1 0"></span>
                        @endif
                        @if ($status['pending'] > 0)
                            <span class="bar-h__seg bar-h__seg--pending" style="flex: {{ $status['pending'] }} 1 0"></span>
                        @endif
                    </div>

                    <div class="legend mt-12">
                        <span class="legend__item">
                            <span class="legend__dot" style="background: var(--green)"></span>
                            Selesai <span class="legend__value">{{ $status['selesai'] }}</span>
                        </span>
                        <span class="legend__item">
                            <span class="legend__dot" style="background: var(--ink)"></span>
                            Diproses <span class="legend__value">{{ $status['proses'] }}</span>
                        </span>
                        <span class="legend__item">
                            <span class="legend__dot" style="background: var(--line-3)"></span>
                            Menunggu <span class="legend__value">{{ $status['pending'] }}</span>
                        </span>
                    </div>
                </section>

                <section class="sheet">
                    <div class="sheet__head" style="margin-bottom: 14px">
                        <div>
                            <h2 class="sheet__title">Penyelesaian 30 hari</h2>
                            <p class="sheet__sub">Laporan yang ditandai selesai</p>
                        </div>
                        <span class="fig__value num" style="font-size: 30px">{{ array_sum($sparkline) }}</span>
                    </div>

                    <div class="spark" role="img" aria-label="Grafik penyelesaian laporan 30 hari terakhir">
                        @php $maksSpark = max(1, max($sparkline) ?: 1); @endphp
                        @foreach ($sparkline as $n)
                            <i
                                class="{{ $n === 0 ? 'is-empty' : '' }}"
                                style="height: {{ $n === 0 ? 4 : max(6, round($n / $maksSpark * 40)) }}px"
                            ></i>
                        @endforeach
                    </div>
                </section>

                @if ($kabar)
                    <section class="sheet tile--cream">
                        <div class="sheet__head" style="margin-bottom: 12px">
                            <div>
                                <h2 class="sheet__title">Catatan terakhir</h2>
                                <p class="sheet__sub mono">{{ $kabar->kode() }}</p>
                            </div>
                            <x-status-badge :status="$kabar->status" />
                        </div>

                        <p class="small">
                            {{ \Illuminate\Support\Str::limit($kabar->tanggapan, 150) }}
                        </p>
                        <p class="tiny mt-8">
                            {{ \App\Support\Waktu::relatif($kabar->updated_at) }} ·
                            <a class="link" href="{{ route('laporan.show', $kabar) }}">Buka laporan</a>
                        </p>
                    </section>
                @endif
            </aside>
        </div>

        <div class="split section-gap">
            <section class="sheet">
                <div class="sheet__head">
                    <div>
                        <h2 class="sheet__title">Laporan terbaru</h2>
                        <p class="sheet__sub">Lima laporan terakhir</p>
                    </div>
                    <a class="sheet__link" href="{{ route('laporan.index') }}">
                        Semua laporan
                        <x-icon name="arrow-right" :size="14" />
                    </a>
                </div>

                <div class="list">
                    @foreach ($terbaru as $laporan)
                        <x-report-row :laporan="$laporan" :href="route('laporan.show', $laporan)" />
                    @endforeach
                </div>
            </section>

            <aside class="stack">
                <section class="sheet">
                    <h2 class="sheet__title">Butuh bantuan</h2>
                    <div class="list mt-12">
                        <a class="list__row" href="{{ route('bantuan') }}#mulai" style="padding-top: 0">
                            <span class="list__tile"><x-icon name="edit" :size="17" /></span>
                            <span class="list__main">
                                <span class="list__title">Cara melapor</span>
                                <span class="list__meta">Empat langkah singkat</span>
                            </span>
                            <span class="list__chev"><x-icon name="chevron-right" :size="16" /></span>
                        </a>
                        <a class="list__row" href="{{ route('bantuan') }}#status">
                            <span class="list__tile"><x-icon name="clock" :size="17" /></span>
                            <span class="list__main">
                                <span class="list__title">Arti status</span>
                                <span class="list__meta">Menunggu, diproses, selesai</span>
                            </span>
                            <span class="list__chev"><x-icon name="chevron-right" :size="16" /></span>
                        </a>
                        <a class="list__row" href="{{ route('bantuan') }}#kontak" style="border-bottom: 0">
                            <span class="list__tile"><x-icon name="whatsapp" :size="17" /></span>
                            <span class="list__main">
                                <span class="list__title">Hubungi pengurus</span>
                                <span class="list__meta">WhatsApp {{ config('lapor.rt') }}</span>
                            </span>
                            <span class="list__chev"><x-icon name="chevron-right" :size="16" /></span>
                        </a>
                    </div>
                </section>

                @if ($aktif > 0)
                    <section class="sheet tile--cream">
                        <h2 class="sheet__title">Sedang berjalan</h2>
                        <p class="body mt-8">
                            {{ $aktif }} laporan belum selesai. Pengurus meninjau maksimal
                            {{ config('lapor.jam_tinjau') }} jam pada hari kerja. Tidak perlu
                            mengirim ulang laporan yang sama.
                        </p>
                    </section>
                @endif
            </aside>
        </div>
    @endif
@endsection
