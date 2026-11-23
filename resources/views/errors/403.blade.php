@extends('layouts.public')

@section('title', 'Akses terbatas')

@section('content')
    <section class="error-page">
        <div class="error-page__code" aria-hidden="true">403</div>

        <div class="error-page__body">
            <span class="label">akses terbatas</span>
            <h1 class="display--sm mt-12">Halaman ini bukan untuk akunmu.</h1>
            <p class="lead mt-12">
                Laporan hanya bisa dibuka oleh pelapor dan pengurus RT. Kalau kamu merasa seharusnya
                punya akses, hubungi pengurus {{ config('lapor.rt') }}.
            </p>

            <div class="row row--wrap mt-20">
                <a class="btn btn--ghost" href="{{ route('home') }}">Beranda</a>

                @auth
                    <a class="btn btn--ink" href="{{ route(auth()->user()->dashboardRoute()) }}">
                        Halaman saya
                    </a>
                @else
                    <a class="btn btn--ink" href="{{ route('login') }}">Masuk</a>
                @endauth
            </div>
        </div>
    </section>
@endsection
