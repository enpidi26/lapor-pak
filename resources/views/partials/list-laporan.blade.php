@props(['laporans', 'filters', 'jumlahStatus', 'prefix' => 'warga'])

@php
    $adaFilter = $filters['q'] !== '' || $filters['status'] !== '' || $filters['kategori'] !== '' || $filters['urut'] !== 'baru';
    $buku = $prefix === 'admin' ? 'admin.' : '';
    $dasar = $prefix === 'admin' ? route('admin.laporan.index') : route('laporan.index');
    $urlStatus = fn (string $s) => route($buku.'laporan.index', array_filter(array_merge(
        request()->except(['status', 'page']),
        ['status' => $s]
    )));
    $detail = fn ($l) => route($buku.'laporan.show', $l);
    $adalahAdmin = $prefix === 'admin';
@endphp

<div class="filters">
    <div class="filters__row">
        <form method="GET" action="{{ $dasar }}" class="search-form" role="search">
            @foreach (request()->except(['q', 'page']) as $k => $v)
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
            @endforeach
            <span class="search">
                <x-icon name="search" />
                <input
                    type="search"
                    name="q"
                    value="{{ $filters['q'] }}"
                    class="input"
                    placeholder="{{ $adalahAdmin ? 'Cari judul, nama, atau email pelapor' : 'Cari judul atau isi laporan' }}"
                    aria-label="Cari laporan"
                >
                @if ($filters['q'] !== '')
                    <a class="search__clear" href="{{ route($buku.'laporan.index', array_filter(request()->except(['q', 'page']))) }}" aria-label="Hapus kata kunci">
                        <x-icon name="x" />
                    </a>
                @endif
            </span>
        </form>

        <form method="GET" action="{{ $dasar }}" class="filters__selects">
            @foreach (request()->except(['kategori', 'urut', 'page']) as $k => $v)
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
            @endforeach

            <select name="kategori" class="select" aria-label="Jenis masalah" data-auto-submit>
                <option value="">Semua jenis</option>
                @foreach (\App\Models\Laporan::KATEGORI as $k => $m)
                    <option value="{{ $k }}" @selected($filters['kategori'] === $k)>{{ $m['label'] }}</option>
                @endforeach
            </select>

            <select name="urut" class="select" aria-label="Urutan" data-auto-submit>
                <option value="baru" @selected($filters['urut'] === 'baru')>Terbaru</option>
                @if ($adalahAdmin)
                    <option value="lama" @selected($filters['urut'] === 'lama')>Terlama masuk</option>
                    <option value="terlama" @selected($filters['urut'] === 'terlama')>Kejadian terlama</option>
                @else
                    <option value="lama" @selected($filters['urut'] === 'lama')>Terlama</option>
                @endif
            </select>

            <button type="submit" class="btn btn--ghost btn--sm">Terapkan</button>
        </form>
    </div>

    <div class="pills" aria-label="Filter status">
        <a class="pill {{ $filters['status'] === '' ? 'is-active' : '' }}" href="{{ $urlStatus('') }}">
            Semua <span class="pill__n">{{ $jumlahStatus['semua'] }}</span>
        </a>
        @foreach (\App\Models\Laporan::STATUSES as $k => $label)
            <a class="pill {{ $filters['status'] === $k ? 'is-active' : '' }}" href="{{ $urlStatus($k) }}">
                {{ $label }} <span class="pill__n">{{ $jumlahStatus[$k] }}</span>
            </a>
        @endforeach
    </div>
</div>

<section class="sheet">
    <div class="sheet__head">
        <div>
            <h2 class="sheet__title">{{ $adaFilter ? 'Hasil filter' : ($adalahAdmin ? 'Daftar laporan' : 'Semua laporan') }}</h2>
            <p class="sheet__sub">
                {{ $laporans->total() }} laporan
                @if ($filters['q'] !== '')
                    · &ldquo;{{ $filters['q'] }}&rdquo;
                @endif
            </p>
        </div>

        @if ($adaFilter)
            <a class="sheet__link" href="{{ $dasar }}">
                <x-icon name="refresh" :size="14" />
                Hapus filter
            </a>
        @endif
    </div>

    @if ($laporans->isEmpty())
        @if ($adaFilter)
            <x-empty-state
                icon="search"
                title="Tidak ada laporan yang cocok"
                text="Coba kata kunci lain, atau hapus filter untuk melihat semua laporan."
            >
                <a class="btn btn--ghost btn--sm" href="{{ $dasar }}">Hapus filter</a>
            </x-empty-state>
        @else
            <x-empty-state
                icon="inbox"
                :title="$adalahAdmin ? 'Belum ada laporan masuk' : 'Belum ada laporan'"
                :text="$adalahAdmin ? 'Begitu warga mengirim laporan, daftarnya muncul di sini.' : 'Kalau ada masalah di lingkungan, laporkan supaya bisa segera ditangani pengurus RT.'"
            >
                @unless ($adalahAdmin)
                    <a class="btn btn--ink btn--sm" href="{{ route('laporan.create') }}">Buat laporan</a>
                @endunless
            </x-empty-state>
        @endif
    @else
        <div class="list">
            @foreach ($laporans as $laporan)
                <x-report-row
                    :laporan="$laporan"
                    :href="$detail($laporan)"
                    :show-pelapor="$adalahAdmin"
                />
            @endforeach
        </div>

        {{ $laporans->links('partials.pagination') }}
    @endif
</section>
