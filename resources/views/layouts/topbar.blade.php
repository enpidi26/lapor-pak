@php
    $user = auth()->user();
@endphp

<header class="topbar">
    <div class="topbar__left">
        <button type="button" class="topbar__menu" data-sidebar-open aria-expanded="false" aria-controls="sidebar" aria-label="Buka navigasi">
            <x-icon name="menu" :size="22" />
        </button>
        <div class="topbar__title">
            <h1>@yield('heading', 'Dashboard')</h1>
            <p>@yield('subheading', 'Layanan pengaduan masyarakat')</p>
        </div>
    </div>

    <div class="topbar__right">
        <span class="topbar__date">
            <x-icon name="calendar-days" :size="15" />
            {{ now()->locale('id')->translatedFormat('d F Y') }}
        </span>
        <span class="topbar__sep" aria-hidden="true"></span>
        <a class="topbar__user" href="{{ route('profil.edit') }}">
            <x-avatar :user="$user" />
            <span class="topbar__who">
                <strong>{{ $user->name }}</strong>
                <span>{{ $user->isAdmin() ? 'Administrator' : 'Warga' }}</span>
            </span>
        </a>
    </div>
</header>
