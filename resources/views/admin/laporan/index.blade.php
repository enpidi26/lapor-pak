@extends('layouts.app')

@section('crumb', 'Ruang pengurus')
@section('heading', 'Laporan masuk')
@section('actions')
    <a class="btn btn--ghost" href="{{ route('admin.dashboard') }}">Ringkasan</a>
    <a class="btn btn--ink" href="{{ route('admin.laporan.index', ['status' => 'pending']) }}">
        <x-icon name="clock" :size="15" />
        Antrean ({{ $jumlahStatus['pending'] }})
    </a>
@endsection

@section('content')
    @include('partials.list-laporan', [
        'laporans' => $laporans,
        'filters' => $filters,
        'jumlahStatus' => $jumlahStatus,
        'prefix' => 'admin',
    ])
@endsection
