@php
    $admin = auth()->user()->isAdmin();
@endphp

<nav class="tabbar" aria-label="Navigasi cepat">
    <div class="tabbar__inner">
        @if ($admin)
            <a class="tab {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">
                <x-icon name="layout-dashboard" :size="22" />
                Dashboard
            </a>
            <a class="tab {{ request()->routeIs('admin.laporan.*') ? 'is-active' : '' }}" href="{{ route('admin.laporan.index') }}">
                <x-icon name="files" :size="22" />
                Pengaduan
            </a>
            <a class="tab {{ request()->routeIs('bantuan') ? 'is-active' : '' }}" href="{{ route('bantuan') }}">
                <x-icon name="circle-help" :size="22" />
                Bantuan
            </a>
            <a class="tab {{ request()->routeIs('profil.*') ? 'is-active' : '' }}" href="{{ route('profil.edit') }}">
                <x-icon name="user-round" :size="22" />
                Profil
            </a>
        @else
            <a class="tab {{ request()->routeIs('beranda') ? 'is-active' : '' }}" href="{{ route('beranda') }}">
                <x-icon name="layout-dashboard" :size="22" />
                Dashboard
            </a>
            <a class="tab {{ request()->routeIs('laporan.index', 'laporan.show', 'laporan.edit') ? 'is-active' : '' }}" href="{{ route('laporan.index') }}">
                <x-icon name="files" :size="22" />
                Pengaduan
            </a>
            <a class="tab--cta" href="{{ route('laporan.create') }}" aria-label="Buat pengaduan">
                <span><x-icon name="plus" :size="22" /></span>
                Lapor
            </a>
            <a class="tab {{ request()->routeIs('bantuan') ? 'is-active' : '' }}" href="{{ route('bantuan') }}">
                <x-icon name="circle-help" :size="22" />
                Bantuan
            </a>
            <a class="tab {{ request()->routeIs('profil.*') ? 'is-active' : '' }}" href="{{ route('profil.edit') }}">
                <x-icon name="user-round" :size="22" />
                Profil
            </a>
        @endif
    </div>
</nav>
