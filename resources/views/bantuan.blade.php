@extends('layouts.public')

@section('title', 'Bantuan')
@section('description', 'Pusat bantuan Lapor Pak: cara melapor, arti status, aturan perubahan, dan privasi data.')

@section('content')
    <section class="section" style="padding-top:48px">
        <div class="container">
            <div class="page-head">
                <div>
                    <span class="eyebrow">Bantuan</span>
                    <h1 style="margin-top:12px">Pusat Bantuan</h1>
                    <p>Panduan singkat: cara melapor, arti status, aturan perubahan, sampai siapa yang bisa melihat laporan Anda.</p>
                </div>

                @auth
                    <a class="btn btn--ghost" href="{{ route(auth()->user()->dashboardRoute()) }}">
                        {{ auth()->user()->isAdmin() ? 'Ruang pengurus' : 'Dashboard warga' }}
                    </a>
                @else
                    <a class="btn btn--primary" href="{{ route('register') }}">Daftar akun</a>
                @endauth
            </div>

            @include('partials.bantuan-body')
        </div>
    </section>
@endsection
