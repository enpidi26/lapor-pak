@extends('layouts.public')

@section('title', 'Halaman tidak ditemukan')

@section('content')
    <section class="error-page">
        <div class="error-page__code" aria-hidden="true">404</div>

        <div class="error-page__body">
            <span class="label">halaman tidak ditemukan</span>
            <h1 class="display--sm mt-12">Halaman ini tidak ada.</h1>
            <p class="lead mt-12">
                Tautannya mungkin sudah berubah atau salah ketik. Kembali ke beranda, atau buka
                halaman laporanmu.
            </p>

            <div class="row row--wrap mt-20">
                <a class="btn btn--ghost" href="{{ route('home') }}">Beranda</a>

                @auth
                    <a class="btn btn--ink" href="{{ route(auth()->user()->dashboardRoute()) }}">
                        {{ auth()->user()->isAdmin() ? 'Ruang pengurus' : 'Ruang saya' }}
                    </a>
                @else
                    <a class="btn btn--ink" href="{{ route('login') }}">Masuk</a>
                @endauth
            </div>
        </div>
    </section>
@endsection
