@php
    $user = auth()->user();
@endphp

<header class="pub-nav">
    <div class="pub-nav__inner">
        <x-logo />

        <nav class="pub-nav__links" aria-label="Navigasi beranda">
            <a href="{{ route('home') }}#tentang">Tentang</a>
            <a href="{{ route('home') }}#cara-kerja">Cara Melapor</a>
            <a href="{{ route('home') }}#status">Status Pengaduan</a>
            <a href="{{ route('bantuan') }}">Bantuan</a>
        </nav>

        <div class="pub-nav__actions">
            @auth
                <a class="btn btn--primary btn--sm" href="{{ route($user->dashboardRoute()) }}">
                    Dashboard
                    <x-icon name="arrow-up-right" :size="15" class="icon--arrow" />
                </a>
            @else
                <a class="btn btn--quiet btn--sm" href="{{ route('login') }}">Masuk</a>
                <a class="btn btn--primary btn--sm" href="{{ route('register') }}">
                    Daftar
                    <x-icon name="arrow-up-right" :size="15" class="icon--arrow" />
                </a>
            @endauth
        </div>
    </div>
</header>
