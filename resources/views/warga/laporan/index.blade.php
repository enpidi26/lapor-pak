@extends('layouts.app')

@section('crumb', 'Ruang warga')
@section('heading', 'Laporan saya')
@section('actions')
    <a class="btn btn--ink" href="{{ route('laporan.create') }}">
        <x-icon name="plus" :size="15" />
        Buat laporan
    </a>
@endsection

@section('content')
    @include('partials.list-laporan', [
        'laporans' => $laporans,
        'filters' => $filters,
        'jumlahStatus' => $jumlahStatus,
        'prefix' => 'warga',
    ])
@endsection
