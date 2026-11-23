@extends('layouts.app')

@section('crumb', 'Ruang pengurus')
@section('heading', 'Ringkasan lingkungan')
@section('actions')
    <a class="btn btn--ghost" href="{{ route('admin.laporan.index', ['status' => 'pending']) }}">
        <x-icon name="clock" :size="15" />
        Antrean ({{ $status['pending'] }})
    </a>
    <a class="btn btn--ink" href="{{ route('admin.laporan.index') }}">Semua laporan</a>
@endsection

@section('content')
    @php
        $aktif = $status['pending'] + $status['proses'];
        $maksSeri = max(1, max(array_column($seri, 'total')));
    @endphp

    @if ($tertua && $usiaTertua >= 2)
        <div class="alert alert--warn" style="margin-bottom: 16px" data-flash data-flash-sticky>
            <span class="alert__icon" aria-hidden="true">
                <x-icon name="clock" :size="14" />
            </span>
            <div class="alert__body">
                <p class="alert__title">Menunggu {{ $usiaTertua }} hari</p>
                &ldquo;{{ \Illuminate\Support\Str::limit($tertua->judul, 80) }}&rdquo; —
                <a class="link" href="{{ route('admin.laporan.show', $tertua) }}">tinjau sekarang</a>.
            </div>
        </div>
    @endif

    <div class="cols cols--4">
        <article class="sheet fig">
            <p class="fig__label">laporan masuk</p>
            <p class="fig__value">{{ $status['total'] }}</p>
            <p class="fig__note">{{ $warga }} warga terdaftar</p>
        </article>

        <article class="sheet fig">
            <p class="fig__label">menunggu</p>
            <p class="fig__value">{{ $status['pending'] }}</p>
            <p class="fig__note">
                @if ($usiaTertua > 0)
                    Tertua {{ $usiaTertua }} hari
                @else
                    Antrean bersih
                @endif
            </p>
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
                    <p class="sheet__sub">{{ $hari }} hari terakhir · {{ array_sum(array_column($seri, 'total')) }} laporan</p>
                </div>

                <div class="tabs" aria-label="Rentang waktu">
                    @foreach ([7, 14, 30] as $p)
                        <a class="{{ $hari === $p ? 'is-active' : '' }}" href="{{ route('admin.dashboard', ['hari' => $p]) }}">{{ $p }}h</a>
                    @endforeach
                </div>
            </div>

            @include('partials.chart', ['seri' => $seri])
        </section>

        <aside class="stack">
            <section class="sheet tile--mint">
                <div class="sheet__head" style="margin-bottom: 14px">
                    <div>
                        <h2 class="sheet__title">Sebaran status</h2>
                        <p class="sheet__sub">Dari {{ $status['total'] }} laporan</p>
                    </div>
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
                        <span class="faint">· {{ $status['total'] ? round($status['selesai'] / $status['total'] * 100) : 0 }}%</span>
                    </span>
                    <span class="legend__item">
                        <span class="legend__dot" style="background: var(--ink)"></span>
                        Diproses <span class="legend__value">{{ $status['proses'] }}</span>
                        <span class="faint">· {{ $status['total'] ? round($status['proses'] / $status['total'] * 100) : 0 }}%</span>
                    </span>
                    <span class="legend__item">
                        <span class="legend__dot" style="background: var(--line-3)"></span>
                        Menunggu <span class="legend__value">{{ $status['pending'] }}</span>
                        <span class="faint">· {{ $status['total'] ? round($status['pending'] / $status['total'] * 100) : 0 }}%</span>
                    </span>
                </div>

                <p class="sheet__foot">
                    Status bergerak satu langkah: Menunggu → Diproses → Selesai. Warga melihat
                    setiap perubahan beserta catatannya.
                </p>
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
        </aside>
    </div>

    <div class="split section-gap">
        <section class="sheet">
            <div class="sheet__head">
                <div>
                    <h2 class="sheet__title">Laporan terbaru</h2>
                    <p class="sheet__sub">Enam laporan terakhir yang masuk</p>
                </div>
                <a class="sheet__link" href="{{ route('admin.laporan.index') }}">
                    Semua laporan
                    <x-icon name="arrow-right" :size="14" />
                </a>
            </div>

            @if ($terbaru->isEmpty())
                <x-empty-state
                    icon="inbox"
                    title="Belum ada laporan"
                    text="Laporan yang dikirim warga akan muncul di sini."
                />
            @else
                <div class="list">
                    @foreach ($terbaru as $laporan)
                        <x-report-row
                            :laporan="$laporan"
                            :href="route('admin.laporan.show', $laporan)"
                            :show-pelapor="true"
                        />
                    @endforeach
                </div>
            @endif
        </section>

        <aside class="stack">
            <section class="sheet tile--cream">
                <h2 class="sheet__title">Antrean tertua</h2>
                <p class="sheet__sub">Supaya tidak ada warga menunggu terlalu lama</p>

                @if ($tertua)
                    <div class="row between mt-12">
                        <x-status-badge status="pending" />
                        <span class="mono faint">{{ $tertua->kode() }}</span>
                    </div>

                    <a class="list__title list__title--link mt-12" href="{{ route('admin.laporan.show', $tertua) }}">
                        {{ $tertua->judul }}
                    </a>

                    <p class="small mt-8">
                        {{ $tertua->pelapor->name }} · {{ $tertua->labelKategori() }} ·
                        {{ $usiaTertua }} hari
                    </p>

                    <a class="btn btn--ink btn--block mt-16" href="{{ route('admin.laporan.show', $tertua) }}">
                        Tinjau laporan ini
                    </a>
                @else
                    <p class="body mt-12">
                        Tidak ada laporan yang menunggu. Semua sudah ditinjau.
                    </p>
                @endif
            </section>

            <section class="sheet">
                <h2 class="sheet__title">Cara menindaklanjuti</h2>
                <div class="list mt-12">
                    <div class="list__row" style="padding-top: 0">
                        <span class="list__tile"><x-icon name="eye" :size="17" /></span>
                        <span class="list__main">
                            <span class="list__title">Baca laporan</span>
                            <span class="list__meta">Periksa lokasi, tanggal, dan foto bila ada</span>
                        </span>
                    </div>
                    <div class="list__row">
                        <span class="list__tile"><x-icon name="message" :size="17" /></span>
                        <span class="list__main">
                            <span class="list__title">Tulis catatan</span>
                            <span class="list__meta">Jelaskan kondisi terbaru atau langkah berikutnya</span>
                        </span>
                    </div>
                    <div class="list__row" style="border-bottom: 0">
                        <span class="list__tile"><x-icon name="check" :size="17" /></span>
                        <span class="list__main">
                            <span class="list__title">Ubah status</span>
                            <span class="list__meta">Tandai Diproses, lalu Selesai setelah tuntas</span>
                        </span>
                    </div>
                </div>
            </section>
        </aside>
    </div>
@endsection
