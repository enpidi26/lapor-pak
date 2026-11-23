@props(['status'])

<span class="badge badge--{{ $status }}">
    <span class="dot" aria-hidden="true"></span>
    {{ \App\Models\Laporan::STATUSES[$status] ?? $status }}
</span>
