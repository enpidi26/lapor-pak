<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#2563eb">
    <meta name="color-scheme" content="light">

    <title>@yield('title', 'Dashboard') · Lapor Pak</title>
    <meta name="description" content="@yield('description', 'Lapor Pak — Suara Warga, Langkah Perubahan. Saluran pengaduan warga '.config('lapor.rt').'.')">

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <link rel="preload" href="{{ asset('fonts/jakarta-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/dmsans-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    <script>document.documentElement.classList.add('js');window.setTimeout(function(){if(!document.documentElement.dataset.jsReady){document.documentElement.classList.remove('js')}},2500)</script>
    <link rel="stylesheet" href="{{ asset('css/lapor-pak.css') }}?v={{ filemtime(public_path('css/lapor-pak.css')) }}">
</head>
<body>
    <a href="#konten" class="skip-link">Langsung ke konten</a>

    @include('layouts.sidebar')

    <div class="scrim" data-scrim data-sidebar-close></div>

    <div class="app-main">
        @include('layouts.topbar')

        <main class="content" id="konten">
            @include('partials.flash')
            @yield('content')
        </main>

        <footer class="app-foot">
            <p>© {{ now()->year }} Lapor Pak. Hak cipta dilindungi.</p>
            <p>Suara Warga, Langkah Perubahan.</p>
        </footer>
    </div>

    @include('partials.tabbar')

    <script src="{{ asset('js/lucide.min.js') }}" defer></script>
    <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}" defer></script>
</body>
</html>
