@props(['href' => null, 'light' => false])

<a href="{{ $href ?? route('home') }}" class="brand {{ $light ? 'brand--light' : '' }}" aria-label="Lapor Pak — Suara Warga, Langkah Perubahan">
    <span class="brand__mark" aria-hidden="true">
        <x-icon name="messages-square" :size="23" />
    </span>
    <span class="brand__name">Lapor&nbsp;Pak<span class="dot">.</span></span>
</a>
