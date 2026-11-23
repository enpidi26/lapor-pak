@props(['kategori'])

@php
    $meta = \App\Models\Laporan::KATEGORI[$kategori] ?? \App\Models\Laporan::KATEGORI['lainnya'];
@endphp

<span class="report-row__icon" aria-hidden="true">
    <x-icon :name="$meta['icon']" :size="20" />
</span>
