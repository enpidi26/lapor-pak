<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#2563eb">
    <meta name="color-scheme" content="light">

    <title>@yield('title', 'Beranda') · Lapor Pak</title>
    <meta name="description" content="@yield('description', 'Lapor Pak membantu warga menyampaikan pengaduan lingkungan dengan mudah dan memantau penanganannya secara transparan.')">

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Lapor Pak">
    <meta property="og:title" content="@yield('title', 'Beranda') · Lapor Pak">
    <meta property="og:description" content="@yield('description', 'Suara Warga, Langkah Perubahan.')">
    <meta property="og:image" content="{{ asset('og-image.svg') }}">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="preload" href="{{ asset('fonts/jakarta-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/dmsans-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    <script>document.documentElement.classList.add('js');window.setTimeout(function(){if(!document.documentElement.dataset.jsReady){document.documentElement.classList.remove('js')}},2500)</script>
    <link rel="stylesheet" href="{{ asset('css/lapor-pak.css') }}?v={{ filemtime(public_path('css/lapor-pak.css')) }}">
</head>
<body>
    <a href="#konten" class="skip-link">Langsung ke konten</a>

    @include('partials.public-header')

    <main id="konten" class="pub-main">
        @include('partials.flash')
        @yield('content')
    </main>

    @include('partials.public-footer')

    <script src="{{ asset('js/lucide.min.js') }}" defer></script>
    <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}" defer></script>
</body>
</html>
