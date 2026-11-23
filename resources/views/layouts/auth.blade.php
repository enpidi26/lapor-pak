<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#2563eb">
    <meta name="color-scheme" content="light">

    <title>@yield('title', 'Selamat Datang') · Lapor Pak</title>
    <meta name="description" content="@yield('description', 'Masuk atau daftar akun Lapor Pak — Suara Warga, Langkah Perubahan.')">

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <link rel="preload" href="{{ asset('fonts/jakarta-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/dmsans-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    <script>document.documentElement.classList.add('js');window.setTimeout(function(){if(!document.documentElement.dataset.jsReady){document.documentElement.classList.remove('js')}},2500)</script>
    <link rel="stylesheet" href="{{ asset('css/lapor-pak.css') }}?v={{ filemtime(public_path('css/lapor-pak.css')) }}">
</head>
<body>
    <div class="auth">
        <aside class="auth__aside">
            <x-logo :href="route('home')" light />

            <div style="position:relative;z-index:1;max-width:520px">
                <span class="auth__badge">
                    <span class="dot" aria-hidden="true"></span>
                    Bersama membangun lingkungan lebih baik
                </span>
                <h1 class="auth__title">
                    Perubahan dimulai dari suara Anda<span class="accent">.</span>
                </h1>
                <p class="auth__lead">
                    Sampaikan pengaduan, pantau perkembangannya, dan jadilah bagian dari solusi
                    untuk lingkungan kita.
                </p>
                <div class="auth__benefits">
                    @foreach (['Sampaikan pengaduan dengan mudah', 'Pantau proses secara transparan', 'Terhubung langsung dengan pengelola'] as $benefit)
                        <div class="auth__benefit">
                            <x-icon name="circle-check" :size="20" />
                            {{ $benefit }}
                        </div>
                    @endforeach
                </div>
            </div>

            <p class="auth__aside-foot">Lapor Pak — Suara Warga, Langkah Perubahan.</p>
            <div class="auth__aside-ring" aria-hidden="true"></div>
        </aside>

        <main class="auth__main">
            <div class="auth__card">
                <div style="margin-bottom:32px" class="auth__mobile-brand">
                    <x-logo :href="route('home')" />
                </div>

                <a href="{{ route('home') }}" class="auth__back">
                    <x-icon name="arrow-left" :size="16" />
                    Kembali ke beranda
                </a>

                @include('partials.flash')
                @yield('content')

                <p class="auth__foot">© {{ now()->year }} Lapor Pak. Hak cipta dilindungi.</p>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/lucide.min.js') }}" defer></script>
    <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}" defer></script>
</body>
</html>
