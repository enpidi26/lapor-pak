@props([
    'seri',
    'maks' => null,
    'labelKaki' => null,
])

@php
    $maks = $maks ?? max(1, max(array_column($seri, 'total')));
    $total = array_sum(array_column($seri, 'total'));
    $terakhir = count($seri) - 1;
@endphp

<div class="chart">
    <div class="chart__plot" role="img" aria-label="Laporan per hari, total {{ $total }} laporan dalam {{ count($seri) }} hari.">
        @foreach ($seri as $i => $h)
            @php $tinggi = $h['total'] > 0 ? max(8, round($h['total'] / $maks * 100, 1)) : 0; @endphp

            <div class="chart__col">
                @if ($h['total'] > 0)
                    <div
                        class="chart__bar"
                        style="height: {{ $tinggi }}%; animation-delay: {{ min($i * 45, 500) }}ms"
                        title="{{ $h['labelPanjang'] }} — {{ $h['total'] }} laporan: {{ $h['selesai'] }} selesai, {{ $h['proses'] }} diproses, {{ $h['pending'] }} menunggu"
                    >
                        @if ($h['selesai'] > 0)
                            <span class="chart__seg chart__seg--selesai" style="flex: {{ $h['selesai'] }} 1 0"></span>
                        @endif
                        @if ($h['proses'] > 0)
                            <span class="chart__seg chart__seg--proses" style="flex: {{ $h['proses'] }} 1 0"></span>
                        @endif
                        @if ($h['pending'] > 0)
                            <span class="chart__seg chart__seg--pending" style="flex: {{ $h['pending'] }} 1 0"></span>
                        @endif
                    </div>
                @else
                    <span class="chart__seg--empty" style="width: 100%" title="{{ $h['labelPanjang'] }} — belum ada laporan"></span>
                @endif
            </div>
        @endforeach
    </div>

    <div class="chart__axis" aria-hidden="true">
        @foreach ($seri as $i => $h)
            <span class="{{ $i === $terakhir ? 'is-now' : '' }}">{{ $h['label'] }}</span>
        @endforeach
    </div>

    <div class="chart__foot">
        <div class="legend">
            <span class="legend__item"><span class="legend__dot" style="background: var(--emerald-500)"></span> Selesai</span>
            <span class="legend__item"><span class="legend__dot" style="background: var(--blue-500)"></span> Diproses</span>
            <span class="legend__item"><span class="legend__dot" style="background: var(--amber-200)"></span> Menunggu</span>
        </div>
        <span class="text-faint" style="font-size:12.5px">{{ $labelKaki ?? ($total.' laporan') }}</span>
    </div>
</div>
