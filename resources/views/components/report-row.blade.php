@props(['laporan', 'href', 'showPelapor' => false])

<a href="{{ $href }}" class="report-row">
    <x-kategori-tile :kategori="$laporan->kategori" />

    <span class="report-row__main">
        <span class="report-row__title">{{ $laporan->judul }}</span>
        <span class="report-row__meta">
            <span>{{ $laporan->kode() }}</span>
            <span class="report-row__sep" aria-hidden="true"></span>

            @if ($showPelapor && $laporan->pelapor)
                <span>{{ $laporan->pelapor->name }}</span>
                <span class="report-row__sep" aria-hidden="true"></span>
            @endif

            <span>{{ \App\Support\Waktu::tanggal($laporan->created_at) }}</span>
            <span class="report-row__sep" aria-hidden="true"></span>
            <span>{{ $laporan->metaKategori()['pendek'] }}</span>

            @if ($laporan->foto)
                <span class="report-row__sep" aria-hidden="true"></span>
                <span class="row" style="gap:4px">
                    <x-icon name="image" :size="13" />
                    Foto
                </span>
            @endif
        </span>
    </span>

    <span class="report-row__right">
        <x-status-badge :status="$laporan->status" />
        <span class="report-row__chev" aria-hidden="true">
            <x-icon name="chevron-right" :size="18" />
        </span>
    </span>
</a>
